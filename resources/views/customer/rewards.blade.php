@extends('layouts.customer')
@section('title', 'Rewards')
@section('content')
<div class="card-m-dark card-m-pad" style="margin-bottom:1rem;position:relative;overflow:hidden">
    <div style="position:absolute;right:-10px;top:-10px;font-size:4rem;opacity:.1">🎁</div>
    <p style="font-size:.72rem;text-transform:uppercase;letter-spacing:1px;color:var(--stone-500);margin-bottom:.5rem">Program Loyalitas</p>
    <p style="font-size:1.25rem;font-weight:800;color:white;margin-bottom:.25rem">Cuci 10x = 1x GRATIS</p>
    <p style="font-size:.78rem;color:var(--stone-500)">Berlaku untuk semua jenis cuci</p>
    <div style="display:flex;gap:6px;margin:1rem 0 .5rem">
        @for($i=1;$i<=10;$i++)
        @php $filled=$i<=($customer->total_washes%10?:($customer->total_washes>0&&$customer->total_washes%10==0?10:0)); @endphp
        <div style="flex:1;height:32px;border-radius:8px;background:{{ $filled?'var(--gold)':'rgba(255,255,255,.08)' }};display:flex;align-items:center;justify-content:center">
            @if($filled)<span style="color:var(--dark);font-weight:700;font-size:.75rem">✓</span>@else<span style="color:var(--stone-500);font-size:.7rem">{{ $i }}</span>@endif
        </div>
        @endfor
    </div>
    <p style="font-size:.78rem;color:var(--stone-500);text-align:center">{{ $stats['washes_until_next'] > 0 && $stats['washes_until_next'] < 10 ? $stats['washes_until_next'].' cuci lagi!' : '' }}</p>
</div>

@if($stats['available_rewards']>0)
<form method="POST" action="{{ route('customer.rewards.claim') }}" style="margin-bottom:1.25rem">@csrf
    <button type="submit" class="btn-gold" style="font-size:1rem;padding:1rem" onclick="return confirm('Klaim 1x cuci gratis sekarang?')">🎉 Klaim Cuci Gratis ({{ $stats['available_rewards'] }} tersedia)</button>
    <p style="text-align:center;font-size:.72rem;color:var(--stone-500);margin-top:.5rem">Tunjukkan ke kasir saat datang</p>
</form>
@endif

<div class="card-m">
    <div style="padding:1rem 1.25rem .5rem"><h3 style="font-size:.85rem;font-weight:700">Riwayat Reward</h3></div>
    @forelse($claims as $c)
    <div style="padding:.75rem 1.25rem;display:flex;justify-content:space-between;align-items:center;{{ !$loop->last?'border-bottom:1px solid var(--stone-100)':'' }}">
        <div>
            <p style="font-size:.82rem;font-weight:600;margin:0">{{ ucfirst(str_replace('_',' ',$c->reward_type)) }}</p>
            <p style="font-size:.7rem;color:var(--stone-500);margin:.1rem 0 0">{{ $c->claimed_at?->format('d M Y H:i') }}</p>
        </div>
        @php $sc=['claimed'=>['#fef3c7','#d97706'],'used'=>['#dcfce7','#16a34a'],'expired'=>['#f3f4f6','#6b7280']]; $s=$sc[$c->status]??['#f3f4f6','#6b7280']; @endphp
        <span style="background:{{ $s[0] }};color:{{ $s[1] }};padding:.2rem .5rem;border-radius:6px;font-size:.65rem;font-weight:600">{{ ucfirst($c->status) }}</span>
    </div>
    @empty<p style="padding:1.25rem;text-align:center;color:var(--stone-500);font-size:.82rem">Belum ada riwayat reward</p>@endforelse
</div>
@endsection
