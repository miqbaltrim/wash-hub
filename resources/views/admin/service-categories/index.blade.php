@extends('layouts.app')
@section('title', 'Kategori Layanan')
@section('header')<h2>Kategori Layanan</h2>@endsection
@section('actions')<a href="{{ route('admin.service-categories.create') }}" class="btn-gold btn-sm">+ Tambah Kategori</a>@endsection
@section('content')
<div class="card" style="overflow:hidden">
    <table>
        <thead><tr><th>Nama</th><th>Deskripsi</th><th style="text-align:center">Layanan</th><th style="text-align:center">Status</th><th style="text-align:center">Aksi</th></tr></thead>
        <tbody>
        @forelse($categories as $cat)
        <tr>
            <td style="font-weight:600">{{ $cat->name }}</td>
            <td style="color:var(--stone-500)">{{ Str::limit($cat->description,50) }}</td>
            <td style="text-align:center"><span class="badge badge-gold">{{ $cat->services_count }}</span></td>
            <td style="text-align:center"><span class="badge {{ $cat->is_active?'badge-green':'badge-red' }}">{{ $cat->is_active?'Aktif':'Off' }}</span></td>
            <td style="text-align:center">
                <a href="{{ route('admin.service-categories.edit',$cat) }}" class="btn-outline btn-sm" style="margin-right:4px">Edit</a>
                <form method="POST" action="{{ route('admin.service-categories.destroy',$cat) }}" style="display:inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn-danger btn-sm">Hapus</button></form>
            </td>
        </tr>
        @empty<tr><td colspan="5"><div class="empty-state"><p>Belum ada kategori</p></div></td></tr>@endforelse
        </tbody>
    </table>
</div>
@endsection
