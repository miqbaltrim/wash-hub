@extends('layouts.app')
@section('title', 'Pricelist / Layanan')
@section('header')<h2>Pricelist / Layanan</h2>@endsection
@section('actions')
<a href="{{ route('admin.service-categories.index') }}" class="btn-outline btn-sm">📂 Kategori</a>
<a href="{{ route('admin.services.create') }}" class="btn-gold btn-sm">+ Tambah Layanan</a>
@endsection
@section('content')
<div class="card" style="padding:1rem;margin-bottom:1rem">
    <form method="GET" style="display:flex;gap:.5rem">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari layanan..." class="form-input" style="flex:1">
        <select name="category" class="form-input form-select" style="width:200px"><option value="">Semua Kategori</option>@foreach($categories as $cat)<option value="{{ $cat->id }}" {{ request('category')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>@endforeach</select>
        <button type="submit" class="btn-dark btn-sm">Filter</button>
    </form>
</div>
<div class="card" style="overflow:hidden">
    <table>
        <thead><tr><th>Layanan</th><th>Kategori</th><th style="text-align:right">Harga</th><th style="text-align:center">Durasi</th><th style="text-align:center">Tipe</th><th style="text-align:center">Poin</th><th style="text-align:center">Status</th><th style="text-align:center">Aksi</th></tr></thead>
        <tbody>
        @forelse($services as $s)
        <tr>
            <td><p style="font-weight:600;margin:0">{{ $s->name }}</p>@if($s->description)<p style="font-size:.7rem;color:var(--stone-500);margin:.1rem 0 0">{{ Str::limit($s->description,40) }}</p>@endif</td>
            <td>{{ $s->category->name ?? '-' }}</td>
            <td style="text-align:right;font-weight:700;color:var(--gold-dark)">Rp {{ number_format($s->price,0,',','.') }}</td>
            <td style="text-align:center">{{ $s->duration_minutes }}m</td>
            <td style="text-align:center"><span class="badge badge-gray">{{ ucfirst($s->vehicle_type) }}</span></td>
            <td style="text-align:center"><span class="badge badge-gold">+{{ $s->points_earned }}</span></td>
            <td style="text-align:center"><span class="badge {{ $s->is_active?'badge-green':'badge-red' }}">{{ $s->is_active?'Aktif':'Off' }}</span></td>
            <td style="text-align:center">
                <a href="{{ route('admin.services.edit',$s) }}" class="btn-outline btn-sm" style="margin-right:4px">Edit</a>
                <form method="POST" action="{{ route('admin.services.destroy',$s) }}" style="display:inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn-danger btn-sm">Hapus</button></form>
            </td>
        </tr>
        @empty<tr><td colspan="8"><div class="empty-state"><p>Belum ada layanan</p></div></td></tr>@endforelse
        </tbody>
    </table>
    <div style="padding:.75rem 1rem;border-top:1px solid var(--stone-200)">{{ $services->withQueryString()->links() }}</div>
</div>
@endsection
