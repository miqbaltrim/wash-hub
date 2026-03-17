@extends('layouts.app')
@section('title', 'Laporan Custom')
@section('header')<h2 class="font-semibold text-xl text-gray-800">Laporan Custom Range</h2>@endsection
@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <label class="text-sm font-medium text-gray-700">Dari:</label>
            <input type="date" name="date_from" value="{{ $dateFrom }}" class="rounded-lg border-gray-300 text-sm">
            <label class="text-sm font-medium text-gray-700">Sampai:</label>
            <input type="date" name="date_to" value="{{ $dateTo }}" class="rounded-lg border-gray-300 text-sm">
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700">Tampilkan</button>
        </form>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4"><p class="text-sm text-gray-500">Total Transaksi</p><p class="text-2xl font-bold">{{ $summary['total_transactions'] }}</p></div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4"><p class="text-sm text-gray-500">Total Revenue</p><p class="text-2xl font-bold text-green-600">Rp {{ number_format($summary['total_revenue'],0,',','.') }}</p></div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4"><p class="text-sm text-gray-500">Rata-rata / Trx</p><p class="text-2xl font-bold text-indigo-600">Rp {{ number_format($summary['avg_per_transaction'],0,',','.') }}</p></div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4"><p class="text-sm text-gray-500">Total Diskon</p><p class="text-2xl font-bold text-red-500">Rp {{ number_format($summary['total_discount'],0,',','.') }}</p></div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50"><tr>
                <th class="text-left py-3 px-4 font-medium text-gray-500">Invoice</th>
                <th class="text-left py-3 px-4 font-medium text-gray-500">Tanggal</th>
                <th class="text-left py-3 px-4 font-medium text-gray-500">Customer</th>
                <th class="text-left py-3 px-4 font-medium text-gray-500">Layanan</th>
                <th class="text-right py-3 px-4 font-medium text-gray-500">Total</th>
            </tr></thead>
            <tbody>
            @forelse($transactions as $trx)
            <tr class="border-t border-gray-100 hover:bg-gray-50">
                <td class="py-3 px-4 font-mono text-xs text-indigo-600">{{ $trx->invoice_number }}</td>
                <td class="py-3 px-4 text-gray-600">{{ $trx->transaction_date->format('d/m/Y') }}</td>
                <td class="py-3 px-4">{{ $trx->customerProfile->user->name ?? 'Walk-in' }}</td>
                <td class="py-3 px-4 text-xs">{{ $trx->details->pluck('service_name')->join(', ') }}</td>
                <td class="py-3 px-4 text-right font-semibold">{{ $trx->is_reward_claim ? 'GRATIS' : 'Rp '.number_format($trx->grand_total,0,',','.') }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="py-8 text-center text-gray-500">Tidak ada transaksi dalam range ini</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
