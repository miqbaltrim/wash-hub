<?php

namespace App\Http\Controllers;

use App\Models\CustomerProfile;
use App\Models\TransactionHead;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();
        $thisMonth = now()->month;
        $thisYear = now()->year;

        // Stats hari ini
        $todayTransactions = TransactionHead::where('transaction_date', $today)
            ->where('payment_status', 'paid')->count();
        $todayRevenue = TransactionHead::where('transaction_date', $today)
            ->where('payment_status', 'paid')->sum('grand_total');

        // Stats bulan ini
        $monthlyRevenue = TransactionHead::whereMonth('transaction_date', $thisMonth)
            ->whereYear('transaction_date', $thisYear)
            ->where('payment_status', 'paid')->sum('grand_total');
        $monthlyTransactions = TransactionHead::whereMonth('transaction_date', $thisMonth)
            ->whereYear('transaction_date', $thisYear)
            ->where('payment_status', 'paid')->count();

        // Total customer
        $totalCustomers = CustomerProfile::count();

        // Transaksi aktif (belum selesai)
        $activeWashes = TransactionHead::whereIn('wash_status', ['waiting', 'in_progress'])
            ->where('payment_status', 'paid')
            ->with(['customerProfile.user', 'vehicle', 'details'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Revenue chart data (7 hari terakhir)
        $revenueChart = TransactionHead::where('payment_status', 'paid')
            ->where('transaction_date', '>=', now()->subDays(6)->toDateString())
            ->select(
                DB::raw("transaction_date as date"),
                DB::raw("SUM(grand_total) as total"),
                DB::raw("COUNT(*) as count")
            )
            ->groupBy('transaction_date')
            ->orderBy('transaction_date')
            ->get();

        // Top services bulan ini
        $topServices = DB::table('transaction_details')
            ->join('transaction_heads', 'transaction_heads.id', '=', 'transaction_details.transaction_head_id')
            ->whereMonth('transaction_heads.transaction_date', $thisMonth)
            ->whereYear('transaction_heads.transaction_date', $thisYear)
            ->where('transaction_heads.payment_status', 'paid')
            ->select('transaction_details.service_name', DB::raw('COUNT(*) as total_sold'), DB::raw('SUM(transaction_details.subtotal) as revenue'))
            ->groupBy('transaction_details.service_name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'todayTransactions', 'todayRevenue', 'monthlyRevenue',
            'monthlyTransactions', 'totalCustomers', 'activeWashes',
            'revenueChart', 'topServices'
        ));
    }
}