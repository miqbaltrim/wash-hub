@extends('layouts.app')
@section('title', 'Daftar Customer')
@section('header')<h2>Daftar Customer</h2>@endsection
@section('actions')<a href="{{ route('admin.customers.create') }}" class="btn-gold btn-sm">+ Tambah Customer</a>@endsection
@section('content')
<div class="card" style="padding:1rem;margin-bottom:1rem">
    <form method="GET" style="display:flex;gap:.5rem"><input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / HP / member code..." class="form-input" style="flex:1"><button type="submit" class="btn-dark btn-sm">Cari</button></form>
</div>
<div class="card" style="overflow:hidden">
    <table>
        <thead><tr><th>Member</th><th>Nama</th><th>HP</th><th style="text-align:center">Cuci</th><th style="text-align:center">Poin</th><th style="text-align:center">Reward</th><th style="text-align:center">Aksi</th></tr></thead>
        <tbody>
        @forelse($customers as $c)
        <tr>
            <td class="mono" style="color:var(--gold-dark);font-weight:600">{{ $c->member_code }}</td>
            <td style="font-weight:600">{{ $c->user->name ?? '-' }}</td>
            <td style="color:var(--stone-500)">{{ $c->phone }}</td>
            <td style="text-align:center;font-weight:700">{{ $c->total_washes }}</td>
            <td style="text-align:center"><span class="badge badge-gold">{{ $c->total_points }}</span></td>
            <td style="text-align:center">@if($c->getAvailableFreeWashes()>0)<span class="badge badge-green">{{ $c->getAvailableFreeWashes() }} free</span>@else<span style="color:var(--stone-300)">-</span>@endif</td>
            <td style="text-align:center">
                <a href="{{ route('admin.customers.show',$c) }}" class="btn-outline btn-sm" style="margin-right:4px">Detail</a>
                <a href="{{ route('admin.customers.edit',$c) }}" class="btn-outline btn-sm">Edit</a>
            </td>
        </tr>
        @empty<tr><td colspan="7"><div class="empty-state"><p>Belum ada customer</p></div></td></tr>@endforelse
        </tbody>
    </table>
    <div style="padding:.75rem 1rem;border-top:1px solid var(--stone-200)">{{ $customers->withQueryString()->links() }}</div>
</div>
@endsection
