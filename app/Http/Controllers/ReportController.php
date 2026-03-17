<?php

namespace App\Http\Controllers;

use App\Models\TransactionHead;
use App\Models\TransactionDetail;
use App\Models\CustomerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    // ── Laporan Harian ──
    public function daily(Request $request)
    {
        $date = $request->get('date', now()->toDateString());

        $transactions = TransactionHead::with(['customerProfile.user', 'details', 'cashier'])
            ->where('transaction_date', $date)
            ->where('payment_status', 'paid')
            ->orderBy('created_at')
            ->get();

        $summary = [
            'total_transactions' => $transactions->count(),
            'total_revenue' => $transactions->sum('grand_total'),
            'total_cash' => $transactions->where('payment_method', 'cash')->sum('grand_total'),
            'total_debit' => $transactions->where('payment_method', 'debit')->sum('grand_total'),
            'total_ewallet' => $transactions->where('payment_method', 'ewallet')->sum('grand_total'),
            'total_transfer' => $transactions->where('payment_method', 'transfer')->sum('grand_total'),
            'total_free' => $transactions->where('is_reward_claim', true)->count(),
            'total_discount' => $transactions->sum('discount_amount'),
        ];

        return view('reports.daily', compact('transactions', 'summary', 'date'));
    }

    // ── Laporan Bulanan ──
    public function monthly(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $dailyData = TransactionHead::where('payment_status', 'paid')
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->select(
                DB::raw("transaction_date as date"),
                DB::raw("COUNT(*) as total_trx"),
                DB::raw("SUM(grand_total) as revenue"),
                DB::raw("SUM(discount_amount) as discount"),
            )
            ->groupBy('transaction_date')
            ->orderBy('transaction_date')
            ->get();

        $summary = [
            'total_transactions' => $dailyData->sum('total_trx'),
            'total_revenue' => $dailyData->sum('revenue'),
            'total_discount' => $dailyData->sum('discount'),
            'avg_daily_revenue' => $dailyData->count() > 0 ? $dailyData->avg('revenue') : 0,
        ];

        // Payment method breakdown
        $paymentBreakdown = TransactionHead::where('payment_status', 'paid')
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(grand_total) as total'))
            ->groupBy('payment_method')
            ->get();

        // Service popularity
        $servicePopularity = DB::table('transaction_details')
            ->join('transaction_heads', 'transaction_heads.id', '=', 'transaction_details.transaction_head_id')
            ->whereMonth('transaction_heads.transaction_date', $month)
            ->whereYear('transaction_heads.transaction_date', $year)
            ->where('transaction_heads.payment_status', 'paid')
            ->select(
                'transaction_details.service_name',
                DB::raw('SUM(transaction_details.qty) as total_qty'),
                DB::raw('SUM(transaction_details.subtotal) as total_revenue')
            )
            ->groupBy('transaction_details.service_name')
            ->orderByDesc('total_qty')
            ->get();

        return view('reports.monthly', compact(
            'dailyData', 'summary', 'paymentBreakdown',
            'servicePopularity', 'month', 'year'
        ));
    }

    // ── Laporan Custom Range ──
    public function custom(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->get('date_to', now()->toDateString());

        $transactions = TransactionHead::with(['customerProfile.user', 'details'])
            ->where('payment_status', 'paid')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->orderBy('transaction_date')
            ->get();

        $summary = [
            'total_transactions' => $transactions->count(),
            'total_revenue' => $transactions->sum('grand_total'),
            'total_discount' => $transactions->sum('discount_amount'),
            'avg_per_transaction' => $transactions->count() > 0 ? $transactions->avg('grand_total') : 0,
        ];

        return view('reports.custom', compact('transactions', 'summary', 'dateFrom', 'dateTo'));
    }

    // ── Export PDF ──
    public function exportPdf(Request $request)
    {
        $type = $request->get('type', 'daily');
        $date = $request->get('date', now()->toDateString());
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        if ($type === 'daily') {
            $transactions = TransactionHead::with(['customerProfile.user', 'details', 'cashier'])
                ->where('transaction_date', $date)
                ->where('payment_status', 'paid')
                ->get();

            $pdf = Pdf::loadView('reports.pdf.daily', compact('transactions', 'date'));
            return $pdf->download("laporan-harian-{$date}.pdf");
        }

        if ($type === 'monthly') {
            $transactions = TransactionHead::with(['details'])
                ->whereMonth('transaction_date', $month)
                ->whereYear('transaction_date', $year)
                ->where('payment_status', 'paid')
                ->get();

            $pdf = Pdf::loadView('reports.pdf.monthly', compact('transactions', 'month', 'year'));
            return $pdf->download("laporan-bulanan-{$year}-{$month}.pdf");
        }
    }

    // ── Top Customers ──
    public function topCustomers(Request $request)
    {
        $limit = $request->get('limit', 20);

        $customers = CustomerProfile::with('user')
            ->withCount(['transactions' => fn($q) => $q->where('payment_status', 'paid')])
            ->withSum(['transactions' => fn($q) => $q->where('payment_status', 'paid')], 'grand_total')
            ->orderByDesc('transactions_sum_grand_total')
            ->limit($limit)
            ->get();

        return view('reports.top-customers', compact('customers'));
    }
}