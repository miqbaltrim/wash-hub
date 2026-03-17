@extends('layouts.customer')
@section('title', 'Dashboard')
@section('header-extra')
<div style="display:flex;align-items:center;gap:.75rem;margin-top:.75rem;padding-bottom:.5rem">
    <div style="width:44px;height:44px;border-radius:12px;background:var(--gold);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1.1rem;color:var(--dark)">{{ strtoupper(substr($customer->user->name,0,1)) }}</div>
    <div><p style="font-size:1rem;font-weight:700;color:white;margin:0">Halo, {{ explode(' ',$customer->user->name)[0] }}!</p><p style="font-size:.72rem;color:var(--stone-500);margin:0;font-family:'Space Mono',monospace">{{ $customer->member_code }}</p></div>
</div>
@endsection
@section('content')
<div class="card-m-gold card-m-pad" style="margin-bottom:1rem;position:relative;overflow:hidden">
    <div style="position:absolute;right:-20px;top:-20px;width:100px;height:100px;border-radius:50%;background:rgba(255,255,255,.1)"></div>
    <p style="font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:1px;opacity:.7;margin-bottom:.5rem">Loyalty Progress</p>
    <div style="display:flex;gap:6px;margin-bottom:.75rem">
        @for($i=1;$i<=10;$i++)
        @php $filled=$i<=($customer->total_washes%10?:($customer->total_washes>0&&$customer->total_washes%10==0?10:0)); @endphp
        <div style="flex:1;height:8px;border-radius:4px;background:{{ $filled?'var(--dark)':'rgba(0,0,0,.15)' }}"></div>
        @endfor
    </div>
    <div style="display:flex;justify-content:space-between;align-items:center">
        <span style="font-size:.78rem;font-weight:600">{{ $customer->total_washes%10 }}/10 menuju cuci gratis</span>
        @if($stats['available_rewards']>0)<span style="background:var(--dark);color:var(--gold-light);padding:.25rem .65rem;border-radius:6px;font-size:.72rem;font-weight:700">{{ $stats['available_rewards'] }} FREE!</span>@endif
    </div>
</div>
<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:.75rem;margin-bottom:1.25rem">
    <div class="card-m card-m-pad" style="text-align:center"><p style="font-size:1.75rem;font-weight:800;color:var(--dark)">{{ $stats['total_washes'] }}</p><p style="font-size:.72rem;color:var(--stone-500)">Total Cuci</p></div>
    <div class="card-m card-m-pad" style="text-align:center"><p style="font-size:1.75rem;font-weight:800;color:var(--gold)">{{ $stats['total_points'] }}</p><p style="font-size:.72rem;color:var(--stone-500)">Poin Saya</p></div>
</div>
@if($stats['available_rewards']>0)
<form method="POST" action="{{ route('customer.rewards.claim') }}" style="margin-bottom:1.25rem">@csrf<button type="submit" class="btn-gold" style="font-size:1rem;padding:1rem" onclick="return confirm('Klaim cuci gratis?')">🎉 Klaim Cuci Gratis!</button></form>
@endif
<div class="card-m" style="margin-bottom:1.25rem">
    <div style="padding:1rem 1.25rem .5rem"><h3 style="font-size:.85rem;font-weight:700;color:var(--dark)">Kendaraan Saya</h3></div>
    @forelse($customer->vehicles as $v)
    <div style="padding:.65rem 1.25rem;display:flex;align-items:center;gap:.75rem;{{ !$loop->last?'border-bottom:1px solid var(--stone-100)':'' }}">
        <div style="width:40px;height:40px;border-radius:10px;background:var(--stone-100);display:flex;align-items:center;justify-content:center;font-size:1.1rem">{{ $v->vehicle_type==='motor'?'🏍':'🚗' }}</div>
        <div style="flex:1"><p style="font-size:.85rem;font-weight:700;margin:0">{{ $v->plate_number }}</p><p style="font-size:.72rem;color:var(--stone-500);margin:0">{{ $v->brand }} {{ $v->model }}</p></div>
    </div>
    @empty<p style="padding:1.25rem;font-size:.82rem;color:var(--stone-500);text-align:center">Belum ada kendaraan</p>@endforelse
</div>
<div class="card-m">
    <div style="padding:1rem 1.25rem .5rem;display:flex;justify-content:space-between"><h3 style="font-size:.85rem;font-weight:700;color:var(--dark)">Transaksi Terakhir</h3><a href="{{ route('customer.transactions') }}" style="font-size:.75rem;color:var(--gold-dark);text-decoration:none;font-weight:600">Semua →</a></div>
    @forelse($customer->transactions as $trx)
    <div style="padding:.75rem 1.25rem;display:flex;justify-content:space-between;{{ !$loop->last?'border-bottom:1px solid var(--stone-100)':'' }}">
        <div><p style="font-size:.82rem;font-weight:600;margin:0">{{ $trx->details->pluck('service_name')->join(', ') }}</p><p style="font-size:.7rem;color:var(--stone-500);margin:.1rem 0 0">{{ $trx->transaction_date->format('d M Y') }}</p></div>
        <span style="font-size:.82rem;font-weight:700;color:{{ $trx->is_reward_claim?'#16a34a':'var(--dark)' }}">{{ $trx->is_reward_claim?'GRATIS':'Rp '.number_format($trx->grand_total,0,',','.') }}</span>
    </div>
    @empty<p style="padding:1.25rem;font-size:.82rem;color:var(--stone-500);text-align:center">Belum ada transaksi</p>@endforelse
</div>
@endsection
