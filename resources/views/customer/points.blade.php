@extends('layouts.customer')
@section('title', 'Poin Saya')
@section('header-extra')<p style="color:var(--stone-500);font-size:.82rem;margin-top:.25rem">Kelola poin reward Anda</p>@endsection
@section('content')
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:.6rem;margin-bottom:1.25rem">
    <div class="card-m-dark card-m-pad" style="text-align:center"><p style="font-size:1.5rem;font-weight:800;color:var(--gold)">{{ $customer->total_points }}</p><p style="font-size:.65rem;color:var(--stone-500)">Saat Ini</p></div>
    <div class="card-m card-m-pad" style="text-align:center"><p style="font-size:1.5rem;font-weight:800;color:var(--dark)">{{ $customer->lifetime_points }}</p><p style="font-size:.65rem;color:var(--stone-500)">Total Earned</p></div>
    <div class="card-m card-m-pad" style="text-align:center"><p style="font-size:1.5rem;font-weight:800;color:var(--dark)">{{ $customer->total_washes }}</p><p style="font-size:.65rem;color:var(--stone-500)">Total Cuci</p></div>
</div>
<div class="card-m">
    <div style="padding:1rem 1.25rem .5rem"><h3 style="font-size:.85rem;font-weight:700">Riwayat Poin</h3></div>
    @forelse($histories as $h)
    <div style="padding:.75rem 1.25rem;display:flex;justify-content:space-between;align-items:center;{{ !$loop->last?'border-bottom:1px solid var(--stone-100)':'' }}">
        <div>
            <p style="font-size:.82rem;font-weight:600;color:{{ $h->type==='earned'?'#16a34a':'#dc2626' }};margin:0">{{ $h->type==='earned'?'+':'-' }}{{ $h->points }} poin</p>
            <p style="font-size:.7rem;color:var(--stone-500);margin:.1rem 0 0">{{ Str::limit($h->description,40) }}</p>
        </div>
        <div style="text-align:right"><p style="font-size:.68rem;color:var(--stone-300);margin:0">{{ $h->created_at->format('d/m/Y') }}</p><p style="font-size:.68rem;color:var(--stone-500);margin:0">Saldo: {{ $h->balance_after }}</p></div>
    </div>
    @empty<p style="padding:1.25rem;text-align:center;color:var(--stone-500);font-size:.82rem">Belum ada riwayat poin</p>@endforelse
</div>
<div style="margin-top:1rem">{{ $histories->links() }}</div>
@endsection
