@extends('layouts.customer')
@section('title', 'Riwayat Cuci')
@section('header-extra')<p style="color:var(--stone-500);font-size:.82rem;margin-top:.25rem">Riwayat semua transaksi Anda</p>@endsection
@section('content')
@forelse($transactions as $trx)
<div class="card-m" style="margin-bottom:.75rem;padding:1rem 1.25rem">
    <div style="display:flex;justify-content:space-between;align-items:flex-start">
        <div><p style="font-size:.85rem;font-weight:700;margin:0;color:var(--dark)">{{ $trx->details->pluck('service_name')->join(', ') }}</p><p style="font-size:.7rem;color:var(--stone-500);margin:.2rem 0 0;font-family:'Space Mono',monospace">{{ $trx->invoice_number }}</p></div>
        <span style="font-size:.85rem;font-weight:800;color:{{ $trx->is_reward_claim?'#16a34a':'var(--dark)' }}">{{ $trx->is_reward_claim?'GRATIS':'Rp '.number_format($trx->grand_total,0,',','.') }}</span>
    </div>
    <div style="display:flex;align-items:center;gap:.5rem;margin-top:.6rem">
        <span style="font-size:.7rem;color:var(--stone-500)">{{ $trx->transaction_date->format('d M Y') }}</span>
        <span style="width:4px;height:4px;border-radius:50%;background:var(--stone-300)"></span>
        @php $wc=['waiting'=>['#fef3c7','#d97706'],'in_progress'=>['#dbeafe','#2563eb'],'done'=>['#dcfce7','#16a34a'],'picked_up'=>['#f3f4f6','#6b7280']]; $ws=$wc[$trx->wash_status]??['#f3f4f6','#6b7280']; @endphp
        <span style="background:{{ $ws[0] }};color:{{ $ws[1] }};padding:.15rem .5rem;border-radius:4px;font-size:.65rem;font-weight:600">{{ ucfirst(str_replace('_',' ',$trx->wash_status)) }}</span>
    </div>
</div>
@empty
<div style="text-align:center;padding:3rem 1rem"><p style="font-size:3rem;margin-bottom:.5rem">🚗</p><p style="color:var(--stone-500);font-size:.9rem">Belum ada riwayat cuci</p></div>
@endforelse
<div style="margin-top:1rem">{{ $transactions->links() }}</div>
@endsection
