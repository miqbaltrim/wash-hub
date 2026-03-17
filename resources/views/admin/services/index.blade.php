@extends('layouts.app')
@section('title', 'Pricelist / Layanan')
@section('header')
    <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800">Pricelist / Layanan</h2>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.service-categories.index') }}" class="px-3 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200">Kategori</a>
            <a href="{{ route('admin.services.create') }}" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">+ Tambah Layanan</a>
        </div>
    </div>
@endsection
@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari layanan..." class="rounded-lg border-gray-300 text-sm flex-1 min-w-[200px]">
        <select name="category" class="rounded-lg border-gray-300 text-sm">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)<option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>@endforeach
        </select>
        <button type="submit" class="px-4 py-2 bg-gray-800 text-white text-sm rounded-lg">Filter</button>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50"><tr>
            <th class="text-left py-3 px-4 font-medium text-gray-500">Layanan</th>
            <th class="text-left py-3 px-4 font-medium text-gray-500">Kategori</th>
            <th class="text-right py-3 px-4 font-medium text-gray-500">Harga</th>
            <th class="text-center py-3 px-4 font-medium text-gray-500">Durasi</th>
            <th class="text-center py-3 px-4 font-medium text-gray-500">Tipe</th>
            <th class="text-center py-3 px-4 font-medium text-gray-500">Poin</th>
            <th class="text-center py-3 px-4 font-medium text-gray-500">Status</th>
            <th class="text-center py-3 px-4 font-medium text-gray-500">Aksi</th>
        </tr></thead>
        <tbody>
        @forelse($services as $svc)
        <tr class="border-t border-gray-100 hover:bg-gray-50">
            <td class="py-3 px-4"><p class="font-medium">{{ $svc->name }}</p><p class="text-xs text-gray-500">{{ Str::limit($svc->description, 40) }}</p></td>
            <td class="py-3 px-4 text-gray-600">{{ $svc->category->name ?? '-' }}</td>
            <td class="py-3 px-4 text-right font-bold text-indigo-600">Rp {{ number_format($svc->price, 0, ',', '.') }}</td>
            <td class="py-3 px-4 text-center">{{ $svc->duration_minutes }} min</td>
            <td class="py-3 px-4 text-center"><span class="bg-gray-100 text-gray-700 px-2 py-0.5 rounded text-xs">{{ ucfirst($svc->vehicle_type) }}</span></td>
            <td class="py-3 px-4 text-center">{{ $svc->points_earned }}</td>
            <td class="py-3 px-4 text-center"><span class="px-2 py-0.5 rounded text-xs font-medium {{ $svc->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $svc->is_active ? 'Aktif' : 'Off' }}</span></td>
            <td class="py-3 px-4 text-center">
                <a href="{{ route('admin.services.edit', $svc) }}" class="text-indigo-600 hover:underline text-xs mr-2">Edit</a>
                <form method="POST" action="{{ route('admin.services.destroy', $svc) }}" class="inline" onsubmit="return confirm('Hapus layanan ini?')">@csrf @method('DELETE')<button class="text-red-600 hover:underline text-xs">Hapus</button></form>
            </td>
        </tr>
        @empty
        <tr><td colspan="8" class="py-8 text-center text-gray-500">Belum ada layanan</td></tr>
        @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t border-gray-200">{{ $services->withQueryString()->links() }}</div>
</div>
@endsection
