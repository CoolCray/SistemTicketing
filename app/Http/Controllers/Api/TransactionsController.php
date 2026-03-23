<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketEmail;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionsExport;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionsController extends Controller
{
    public function export() 
    {
        return Excel::download(new TransactionsExport, 'data_transaksi.xlsx');
    }

    public function downloadTicket($id)
    {
        $transaction = Transaction::with(['customers', 'packages', 'seats'])->find($id);

        if (!$transaction) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaksi tidak ditemukan.'
            ], 404);
        }

        $transactionsCollection = Transaction::with(['customers', 'packages', 'seats'])
            ->where('customer_id', $transaction->customer_id)
            ->where('package_id', $transaction->package_id)
            ->where('created_at', $transaction->created_at)
            ->get();
            
        $pdf = Pdf::loadView('pdf.ticket', [
            'customer' => $transaction->customers,
            'transactions' => $transactionsCollection
        ])->setPaper('A4', 'portrait');

        return $pdf->download('E-Ticket_'.$transaction->customers->name.'.pdf');
    }

    public function index()
    {
        $transactions = Transaction::with(['customers', 'packages', 'seats', 'transaction_additionals'])
            ->orderBy('created_at', 'desc')
            ->get();

        $grouped = $transactions->groupBy(function ($item) {
            return $item->customer_id . '_' . $item->package_id . '_' . $item->created_at->format('Y-m-d H:i');
        })->map(function ($group) {
            $first = $group->first();

            // Combine seats
            $seatNumbers = $group->map(function ($t) {
                return $t->seats ? $t->seats->seat_number : null;
            })->filter()->implode(', ');

            $first->setAttribute('grouped_seats', $seatNumbers);

            // Combine additionals
            $additionals = $group->flatMap(function ($t) {
                return $t->transaction_additionals;
            })->unique('id')->values();

            $first->setRelation('transaction_additionals', $additionals);

            // Calculate Price
            $packagePrice = $first->packages ? $first->packages->price : 0;
            $additionalPrice = $additionals->sum(function($a) {
                return $a->price * $a->pivot->jumlah;
            });
            $first->setAttribute('total_price', $packagePrice + $additionalPrice);

            return $first;
        })->values();

        // Paginate manually
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $perPage = 10;

        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $grouped->forPage($page, $perPage)->values(),
            $grouped->count(),
            $perPage,
            $page,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );

        return response()->json($paginated);
    }

    public function store(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id',
            'seats' => 'required|array',
            'seats.*' => 'exists:seats,id',
            'customer.name' => 'required|string',
            'customer.email' => 'required|email|unique:customers,email',
            'customer.phone_number' => 'required|string',
            'customer.name_student' => 'nullable|string',
            'customer.nis' => 'nullable|string',
            'additionals' => 'nullable|array',
            'additionals.*' => 'exists:additionals,id',
        ], [
            'customer.email.unique' => 'Email ini sudah terdaftar. Silakan gunakan email lain.'
        ]);

        $bookedSeats = Transaction::whereIn('seat_id', $request->seats)->exists();
        if ($bookedSeats) {
            return response()->json([
                'status' => 'error',
                'message' => 'Beberapa kursi yang dipilih sudah dipesan.'
            ], 400);
        }

        $customer = Customer::updateOrCreate(
            ['email' => $request->customer['email']],
            [
                'name' => $request->customer['name'],
                'phone_number' => $request->customer['phone_number'],
                'name_student' => $request->customer['name_student'] ?? null,
                'nis' => $request->customer['nis'] ?? null,
            ]
        );

        $transactions = [];
        $totalSeats = count($request->seats);
        $isFirstSeat = true;

        foreach ($request->seats as $seatId) {
            $transaction = Transaction::create([
                'customer_id' => $customer->id,
                'package_id' => $request->package_id,
                'seat_id' => $seatId,
                'additional_id' => null,
                'checkin_token' => Str::uuid()->toString(),
                'checkout_token' => Str::uuid()->toString(),
            ]);

            if ($isFirstSeat && $request->has('additionals') && is_array($request->additionals)) {
                $pivotData = [];
                foreach ($request->additionals as $addon) {
                    if (is_array($addon)) {
                        $pivotData[$addon['id']] = ['jumlah' => $addon['jumlah'] ?? 1];
                    } else if (is_numeric($addon)) {
                        $pivotData[$addon] = ['jumlah' => 1];
                    }
                }

                $transaction->transaction_additionals()->attach($pivotData);
            }

            $isFirstSeat = false;
            $transactions[] = $transaction;
        }

        // Load relationships before sending email
        $transactionsCollection = Transaction::with(['packages', 'seats'])->whereIn('id', collect($transactions)->pluck('id'))->get();
        
        // Send email to the customer
        try {
            Mail::to($customer->email)->send(new TicketEmail($customer, $transactionsCollection));
        } catch (\Exception $e) {
            Log::error('Failed to send ticket email: ' . $e->getMessage());
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Booking berhasil dibuat.',
            'data' => $transactions
        ], 201);
    }
    public function update(Request $request, $id)
    {
        $transaction = Transaction::with('customers')->find($id);

        if (!$transaction) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaksi tidak ditemukan.'
            ], 404);
        }

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:customers,email,' . $transaction->customer_id,
            'phone_number' => 'required|string',
            'name_student' => 'nullable|string',
            'nis' => 'nullable|string',
        ]);

        $customer = $transaction->customers;

        if ($customer) {
            $customer->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'name_student' => $request->name_student,
                'nis' => $request->nis,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data pemesan berhasil diupdate.'
        ]);
    }

    public function destroy($id)
    {
        $transaction = Transaction::find($id);
        if (!$transaction) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaksi tidak ditemukan.'
            ], 404);
        }

        Transaction::where('customer_id', $transaction->customer_id)
            ->where('package_id', $transaction->package_id)
            ->where('created_at', $transaction->created_at)
            ->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Seluruh data kursi untuk transaksi ini berhasil dihapus.'
        ]);
    }

    public function search(Request $request)
    {
        $searchTerm = $request->query('query') ?? $request->query('q');

        $transactionsQuery = Transaction::with(['customers', 'packages', 'seats', 'transaction_additionals'])
            ->orderBy('created_at', 'desc');

        if ($searchTerm) {
            $transactionsQuery->whereHas('customers', function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%")
                  ->orWhere('phone_number', 'like', "%{$searchTerm}%");
            })->orWhereHas('packages', function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%");
            });
        }

        $transactions = $transactionsQuery->get();

        $grouped = $transactions->groupBy(function ($item) {
            return $item->customer_id . '_' . $item->package_id . '_' . $item->created_at->format('Y-m-d H:i');
        })->map(function ($group) {
            $first = $group->first();


            $seatNumbers = $group->map(function ($t) {
                return $t->seats ? $t->seats->seat_number : null;
            })->filter()->implode(', ');

            $first->setAttribute('grouped_seats', $seatNumbers);

            // Combine additionals
            $additionals = $group->flatMap(function ($t) {
                return $t->transaction_additionals;
            })->unique('id')->values();

            $first->setRelation('transaction_additionals', $additionals);

            // Calculate Price
            $packagePrice = $first->packages ? $first->packages->price : 0;
            $additionalPrice = $additionals->sum(function($a) {
                return $a->price * $a->pivot->jumlah;
            });
            $first->setAttribute('total_price', $packagePrice + $additionalPrice);

            return $first;
        })->values();

        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $perPage = 10;

        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $grouped->forPage($page, $perPage)->values(),
            $grouped->count(),
            $perPage,
            $page,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );

        return response()->json($paginated);
    }
}
