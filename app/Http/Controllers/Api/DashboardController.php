<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Total Tickets Sold
        $totalTicketsSold = Transaction::count();

        // 2. Total Check-ins
        $totalCheckins = Attendance::whereNotNull('attendance_time')->count();

        // 3. Total Revenue
        // Calculate revenue from packages
        // Group by customer, package, and created_at (minute) to count a package's price only once per booking
        $packageRevenue = DB::table('transactions')
            ->join('packages', 'transactions.package_id', '=', 'packages.id')
            ->select('packages.price')
            ->groupBy('transactions.customer_id', 'transactions.package_id', DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d %H:%i")'))
            ->get()
            ->sum('price');

        // Calculate revenue from additionals
        $additionalRevenue = DB::table('transaction_additionals')
            ->join('additionals', 'transaction_additionals.additional_id', '=', 'additionals.id')
            ->selectRaw('SUM(additionals.price * transaction_additionals.jumlah) as total_additional_revenue')
            ->value('total_additional_revenue');

        $totalRevenue = $packageRevenue + ($additionalRevenue ?? 0);

        // 4. Recent Transactions (last 10 unique bookings)
        $recentTransactionsRaw = Transaction::with(['customers', 'packages', 'seats'])
            ->orderBy('created_at', 'desc')
            ->limit(30) // fetch more to account for grouping
            ->get();
            
        // Group by user/transaction instance to avoid listing every single seat as a separate row
        $recentTransactions = $recentTransactionsRaw->groupBy(function ($item) {
            return $item->customer_id . '_' . $item->package_id . '_' . $item->created_at->format('Y-m-d H:i');
        })->map(function ($group) {
            $firstItem = $group->first();
            return [
                'id' => $firstItem->id,
                'customer_name' => $firstItem->customers->name ?? 'N/A',
                'customer_email' => $firstItem->customers->email ?? 'N/A',
                'package_name' => $firstItem->packages->name ?? 'N/A',
                'total_seats' => $group->count(),
                'seats' => $group->pluck('seats.seat_number')->implode(', '),
                'created_at' => $firstItem->created_at
            ];
        })->take(10)->values();

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_revenue' => $totalRevenue,
                'total_tickets_sold' => $totalTicketsSold,
                'total_checkins' => $totalCheckins,
                'recent_transactions' => $recentTransactions
            ]
        ], 200);
    }
}
