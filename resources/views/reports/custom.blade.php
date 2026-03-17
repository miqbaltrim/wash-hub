@extends('layouts.app')
@section('title', 'Laporan Custom')
@section('header')<h2>Laporan Custom Range</h2>@endsection
@section('content')
<div class="card" style="padding:1rem;margin-bottom:1rem">
    <form method="GET" style="display:flex;align-items:center;gap:.5rem"><label class="form-label" style="margin:0">Dari:</label><input type="date" name="date_from" value="{{ $dateFrom }}" class="form-input" style="width:180px"><label class="form-label" style="margin:0">Sampai:</label><input type="date" name="date_to" value="{{ $dateTo }}" class="form-input" style="width:180px"><button type="submit" class="btn-gold btn-sm">Tampilkan</button></form>
</div>
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem">
    <div class="card stat-card"><p class="stat-label">Total Transaksi</p><p class="stat-value">{{ $summary['total_transactions'] }}</p></div>
    <div class="card stat-card"><p class="stat-label">Revenue</p><p class="stat-value" style="color:var(--gold-dark)">Rp {{ number_format($summary['total_revenue'],0,',','.') }}</p></div>
    <div class="card stat-card"><p class="stat-label">Rata-rata/Trx</p><p class="stat-value">Rp {{ number_format($summary['avg_per_transaction'],0,',','.') }}</p></div>
    <div class="card stat-card"><p class="stat-label">Total Diskon</p><p class="stat-value" style="color:#dc2626">Rp {{ number_format($summary['total_discount'],0,',','.') }}</p></div>
</div>
<div class="card" style="overflow:hidden">
    <table>
        <thead><tr><th>Invoice</th><th>Tanggal</th><th>Customer</th><th>Layanan</th><th style="text-align:right">Total</th></tr></thead>
        <tbody>
        @forelse($transactions as $t)
        <tr><td class="mono" style="color:var(--gold-dark)">{{ $t->invoice_number }}</td><td>{{ $t->transaction_date->format('d/m/Y') }}</td><td>{{ $t->customerProfile->user->name ?? 'Walk-in' }}</td><td style="font-size:.78rem">{{ $t->details->pluck('service_name')->join(', ') }}</td><td style="text-align:right;font-weight:700">{{ $t->is_reward_claim?'GRATIS':'Rp '.number_format($t->grand_total,0,',','.') }}</td></tr>
        @empty<tr><td colspan="5"><div class="empty-state"><p>Tidak ada transaksi</p></div></td></tr>@endforelse
        </tbody>
    </table>
</div>
@endsection
