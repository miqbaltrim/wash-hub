@extends('layouts.app')
@section('title', 'Kategori Layanan')
@section('header')
    <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800">Kategori Layanan</h2>
        <a href="{{ route('admin.service-categories.create') }}" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">+ Tambah Kategori</a>
    </div>
@endsection
@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50"><tr>
            <th class="text-left py-3 px-4 font-medium text-gray-500">Nama</th>
            <th class="text-left py-3 px-4 font-medium text-gray-500">Deskripsi</th>
            <th class="text-center py-3 px-4 font-medium text-gray-500">Jumlah Layanan</th>
            <th class="text-center py-3 px-4 font-medium text-gray-500">Status</th>
            <th class="text-center py-3 px-4 font-medium text-gray-500">Aksi</th>
        </tr></thead>
        <tbody>
        @forelse($categories as $cat)
        <tr class="border-t border-gray-100 hover:bg-gray-50">
            <td class="py-3 px-4 font-medium">{{ $cat->name }}</td>
            <td class="py-3 px-4 text-gray-600">{{ Str::limit($cat->description, 50) }}</td>
            <td class="py-3 px-4 text-center"><span class="bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded text-xs font-medium">{{ $cat->services_count }}</span></td>
            <td class="py-3 px-4 text-center">
                <span class="px-2 py-0.5 rounded text-xs font-medium {{ $cat->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $cat->is_active ? 'Aktif' : 'Nonaktif' }}</span>
            </td>
            <td class="py-3 px-4 text-center">
                <a href="{{ route('admin.service-categories.edit', $cat) }}" class="text-indigo-600 hover:underline text-xs mr-2">Edit</a>
                <form method="POST" action="{{ route('admin.service-categories.destroy', $cat) }}" class="inline" onsubmit="return confirm('Hapus kategori ini?')">@csrf @method('DELETE')
                    <button class="text-red-600 hover:underline text-xs">Hapus</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="5" class="py-8 text-center text-gray-500">Belum ada kategori</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
