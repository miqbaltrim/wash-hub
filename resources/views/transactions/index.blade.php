@extends('layouts.app')
@section('title', 'Daftar Transaksi')
@section('header')<h2>Daftar Transaksi</h2>@endsection
@section('actions')<a href="{{ route('transactions.create') }}" class="btn-gold">+ Transaksi Baru</a>@endsection
@section('content')
<div class="card" style="padding:1rem;margin-bottom:1rem">
    <form method="GET" style="display:grid;grid-template-columns:2fr 1fr 1fr 1fr 1fr auto;gap:.5rem;align-items:end">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari invoice / plat..." class="form-input">
        <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input">
        <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input">
        <select name="status" class="form-input form-select"><option value="">Status</option><option value="paid" {{ request('status')=='paid'?'selected':'' }}>Paid</option><option value="cancelled" {{ request('status')=='cancelled'?'selected':'' }}>Cancelled</option></select>
        <select name="wash_status" class="form-input form-select"><option value="">Wash</option><option value="waiting" {{ request('wash_status')=='waiting'?'selected':'' }}>Menunggu</option><option value="in_progress" {{ request('wash_status')=='in_progress'?'selected':'' }}>Dicuci</option><option value="done" {{ request('wash_status')=='done'?'selected':'' }}>Selesai</option></select>
        <button type="submit" class="btn-dark">Filter</button>
    </form>
</div>
<div class="card" style="overflow:hidden">
    <table>
        <thead><tr><th>Invoice</th><th>Tanggal</th><th>Customer</th><th>Plat</th><th style="text-align:right">Total</th><th style="text-align:center">Bayar</th><th style="text-align:center">Status</th><th style="text-align:center">Aksi</th></tr></thead>
        <tbody>
        @forelse($transactions as $t)
        <tr>
            <td class="mono"><a href="{{ route('transactions.show',$t) }}" style="color:var(--gold-dark);text-decoration:none;font-weight:600">{{ $t->invoice_number }}</a></td>
            <td>{{ $t->transaction_date->format('d/m/Y') }}</td>
            <td style="font-weight:500">{{ $t->customerProfile->user->name ?? 'Walk-in' }}</td>
            <td style="font-weight:700">{{ $t->plate_number }}</td>
            <td style="text-align:right;font-weight:700">@if($t->is_reward_claim)<span style="color:#16a34a">GRATIS</span>@else Rp {{ number_format($t->grand_total,0,',','.') }}@endif</td>
            <td style="text-align:center"><span class="badge {{ $t->payment_status==='paid'?'badge-green':'badge-red' }}">{{ ucfirst($t->payment_status) }}</span></td>
            <td style="text-align:center">@php $wc=['waiting'=>'badge-yellow','in_progress'=>'badge-blue','done'=>'badge-green','picked_up'=>'badge-gray']; @endphp<span class="badge {{ $wc[$t->wash_status]??'' }}">{{ ucfirst(str_replace('_',' ',$t->wash_status)) }}</span></td>
            <td style="text-align:center"><a href="{{ route('transactions.show',$t) }}" class="btn-outline btn-sm">Detail</a></td>
        </tr>
        @empty<tr><td colspan="8" class="empty-state">Belum ada transaksi</td></tr>@endforelse
        </tbody>
    </table>
    <div style="padding:.75rem 1rem;border-top:1px solid var(--stone-200)">{{ $transactions->withQueryString()->links() }}</div>
</div>
@endsection
