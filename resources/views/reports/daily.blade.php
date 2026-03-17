@extends('layouts.app')
@section('title', 'Laporan Harian')
@section('header')<h2>Laporan Harian</h2><p>{{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}</p>@endsection
@section('actions')<a href="{{ route('reports.export-pdf',['type'=>'daily','date'=>$date]) }}" class="btn-dark btn-sm">📄 Export PDF</a>@endsection
@section('content')
<div class="card" style="padding:1rem;margin-bottom:1rem">
    <form method="GET" style="display:flex;align-items:center;gap:.5rem"><label class="form-label" style="margin:0;white-space:nowrap">Tanggal:</label><input type="date" name="date" value="{{ $date }}" class="form-input" style="width:200px"><button type="submit" class="btn-gold btn-sm">Tampilkan</button></form>
</div>
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem">
    <div class="card stat-card"><p class="stat-label">Total Transaksi</p><p class="stat-value">{{ $summary['total_transactions'] }}</p></div>
    <div class="card stat-card"><p class="stat-label">Total Revenue</p><p class="stat-value" style="color:var(--gold-dark)">Rp {{ number_format($summary['total_revenue'],0,',','.') }}</p></div>
    <div class="card stat-card"><p class="stat-label">Total Diskon</p><p class="stat-value" style="color:#dc2626">Rp {{ number_format($summary['total_discount'],0,',','.') }}</p></div>
    <div class="card stat-card"><p class="stat-label">Cuci Gratis</p><p class="stat-value">{{ $summary['total_free'] }}</p></div>
</div>
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:.75rem;margin-bottom:1.5rem">
    <div style="background:var(--gold-50);border:1px solid var(--gold-100);border-radius:10px;padding:.75rem"><p style="font-size:.7rem;color:var(--gold-dark);font-weight:600">Cash</p><p style="font-weight:700">Rp {{ number_format($summary['total_cash'],0,',','.') }}</p></div>
    <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:.75rem"><p style="font-size:.7rem;color:#2563eb;font-weight:600">Debit</p><p style="font-weight:700">Rp {{ number_format($summary['total_debit'],0,',','.') }}</p></div>
    <div style="background:#faf5ff;border:1px solid #e9d5ff;border-radius:10px;padding:.75rem"><p style="font-size:.7rem;color:#9333ea;font-weight:600">E-Wallet</p><p style="font-weight:700">Rp {{ number_format($summary['total_ewallet'],0,',','.') }}</p></div>
    <div style="background:var(--stone-100);border:1px solid var(--stone-200);border-radius:10px;padding:.75rem"><p style="font-size:.7rem;color:var(--stone-500);font-weight:600">Transfer</p><p style="font-weight:700">Rp {{ number_format($summary['total_transfer'],0,',','.') }}</p></div>
</div>
<div class="card" style="overflow:hidden">
    <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--stone-200)"><h3 style="font-size:.9rem;font-weight:700">Daftar Transaksi</h3></div>
    <table>
        <thead><tr><th>Waktu</th><th>Invoice</th><th>Customer</th><th>Plat</th><th>Layanan</th><th style="text-align:center">Bayar</th><th style="text-align:right">Total</th></tr></thead>
        <tbody>
        @forelse($transactions as $t)
        <tr>
            <td style="font-size:.78rem;color:var(--stone-500)">{{ $t->created_at->format('H:i') }}</td>
            <td class="mono"><a href="{{ route('transactions.show',$t) }}" style="color:var(--gold-dark);text-decoration:none">{{ $t->invoice_number }}</a></td>
            <td>{{ $t->customerProfile->user->name ?? 'Walk-in' }}</td>
            <td style="font-weight:700">{{ $t->plate_number }}</td>
            <td style="font-size:.78rem">{{ $t->details->pluck('service_name')->join(', ') }}</td>
            <td style="text-align:center"><span class="badge badge-gray">{{ strtoupper(str_replace('_',' ',$t->payment_method)) }}</span></td>
            <td style="text-align:right;font-weight:700">{{ $t->is_reward_claim?'GRATIS':'Rp '.number_format($t->grand_total,0,',','.') }}</td>
        </tr>
        @empty<tr><td colspan="7"><div class="empty-state"><p>Tidak ada transaksi</p></div></td></tr>@endforelse
        </tbody>
    </table>
</div>
@endsection
