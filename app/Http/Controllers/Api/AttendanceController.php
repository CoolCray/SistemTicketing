<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Transaction;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendancesExport;

class AttendanceController extends Controller
{
    public function export()
    {
        return Excel::download(new AttendancesExport, 'data_kehadiran.xlsx');
    }

    public function index()
    {
        return Attendance::with(['transaction.customers', 'transaction.seats'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        return Attendance::with(['transaction.customers', 'transaction.seats'])
            ->whereHas('transaction.customers', function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->orWhereHas('transaction.seats', function($q) use ($query) {
                $q->where('seat_number', 'like', "%{$query}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function scan(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $token = $request->token;

        // Cek Check-in
        $transactionIn = Transaction::where('checkin_token', $token)->first();
        if ($transactionIn) {
            $attendance = Attendance::firstOrCreate(
                ['transaction_id' => $transactionIn->id],
                ['attendance_time' => now()]
            );

            if (!$attendance->wasRecentlyCreated && !$attendance->attendance_time) {
                $attendance->update(['attendance_time' => now()]);
            } else if (!$attendance->wasRecentlyCreated) {
                return response()->json(['status' => 'error', 'message' => 'Sudah melakukan Check-in.'], 400);
            }

            $attendance->load(['transaction.customers', 'transaction.seats']);

            return response()->json([
                'status' => 'success',
                'message' => 'Check-in berhasil!',
                'type' => 'checkin',
                'data' => $attendance
            ]);
        }

        // Cek Check-out
        $transactionOut = Transaction::where('checkout_token', $token)->first();
        if ($transactionOut) {
            $attendance = Attendance::where('transaction_id', $transactionOut->id)->first();
            
            if (!$attendance || !$attendance->attendance_time) {
                return response()->json(['status' => 'error', 'message' => 'Belum melakukan Check-in.'], 400);
            }

            if ($attendance->leave_time) {
                return response()->json(['status' => 'error', 'message' => 'Sudah melakukan Check-out.'], 400);
            }

            $attendance->update(['leave_time' => now()]);
            
            $attendance->load(['transaction.customers', 'transaction.seats']);

            return response()->json([
                'status' => 'success',
                'message' => 'Check-out berhasil!',
                'type' => 'checkout',
                'data' => $attendance
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'Token QR tidak valid.'], 404);
    }

    public function store(Request $request)
    {
        $data = Attendance::create($request->all());
        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil ditambahkan',
            'data' => $data
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = Attendance::find($id);
        $data->update($request->all());
        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diupdate',
            'data' => $data
        ]);
    }

    public function destroy($id)
    {
        $data = Attendance::find($id);
        $data->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus',
            'data' => $data
        ]);
    }
}
