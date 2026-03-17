@extends('layouts.app')
@section('title', 'Laporan Harian')
@section('header')
    <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800">Laporan Harian</h2>
        <a href="{{ route('reports.export-pdf', ['type' => 'daily', 'date' => $date]) }}" class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700">📄 Export PDF</a>
    </div>
@endsection
@section('content')
    <!-- Date Filter -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
        <form method="GET" class="flex items-center gap-3">
            <label class="text-sm font-medium text-gray-700">Tanggal:</label>
            <input type="date" name="date" value="{{ $date }}" class="rounded-lg border-gray-300 text-sm">
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700">Tampilkan</button>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Total Transaksi</p>
            <p class="text-2xl font-bold text-gray-800">{{ $summary['total_transactions'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Total Revenue</p>
            <p class="text-2xl font-bold text-green-600">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Total Diskon</p>
            <p class="text-2xl font-bold text-red-500">Rp {{ number_format($summary['total_discount'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Cuci Gratis</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $summary['total_free'] }}</p>
        </div>
    </div>

    <!-- Payment Breakdown -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-green-50 rounded-xl p-4 border border-green-200">
            <p class="text-xs text-green-600 font-medium">Cash</p>
            <p class="text-lg font-bold text-green-700">Rp {{ number_format($summary['total_cash'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
            <p class="text-xs text-blue-600 font-medium">Debit</p>
            <p class="text-lg font-bold text-blue-700">Rp {{ number_format($summary['total_debit'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-purple-50 rounded-xl p-4 border border-purple-200">
            <p class="text-xs text-purple-600 font-medium">E-Wallet</p>
            <p class="text-lg font-bold text-purple-700">Rp {{ number_format($summary['total_ewallet'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-indigo-50 rounded-xl p-4 border border-indigo-200">
            <p class="text-xs text-indigo-600 font-medium">Transfer</p>
            <p class="text-lg font-bold text-indigo-700">Rp {{ number_format($summary['total_transfer'], 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Transaction List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-200"><h3 class="font-semibold text-gray-800">Daftar Transaksi - {{ \Carbon\Carbon::parse($date)->format('d F Y') }}</h3></div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50"><tr>
                <th class="text-left py-3 px-4 font-medium text-gray-500">Waktu</th>
                <th class="text-left py-3 px-4 font-medium text-gray-500">Invoice</th>
                <th class="text-left py-3 px-4 font-medium text-gray-500">Customer</th>
                <th class="text-left py-3 px-4 font-medium text-gray-500">Plat</th>
                <th class="text-left py-3 px-4 font-medium text-gray-500">Layanan</th>
                <th class="text-center py-3 px-4 font-medium text-gray-500">Bayar</th>
                <th class="text-right py-3 px-4 font-medium text-gray-500">Total</th>
            </tr></thead>
            <tbody>
            @forelse($transactions as $trx)
            <tr class="border-t border-gray-100 hover:bg-gray-50">
                <td class="py-3 px-4 text-gray-500 text-xs">{{ $trx->created_at->format('H:i') }}</td>
                <td class="py-3 px-4 font-mono text-xs"><a href="{{ route('transactions.show', $trx) }}" class="text-indigo-600 hover:underline">{{ $trx->invoice_number }}</a></td>
                <td class="py-3 px-4">{{ $trx->customerProfile->user->name ?? 'Walk-in' }}</td>
                <td class="py-3 px-4 font-semibold">{{ $trx->plate_number }}</td>
                <td class="py-3 px-4 text-xs">{{ $trx->details->pluck('service_name')->join(', ') }}</td>
                <td class="py-3 px-4 text-center"><span class="bg-gray-100 px-2 py-0.5 rounded text-xs">{{ strtoupper(str_replace('_', ' ', $trx->payment_method)) }}</span></td>
                <td class="py-3 px-4 text-right font-semibold">{{ $trx->is_reward_claim ? 'GRATIS' : 'Rp '.number_format($trx->grand_total,0,',','.') }}</td>
            </tr>
            @empty
            <tr><td colspan="7" class="py-8 text-center text-gray-500">Tidak ada transaksi pada tanggal ini</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
