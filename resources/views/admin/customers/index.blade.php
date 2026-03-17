@extends('layouts.app')
@section('title', 'Daftar Customer')
@section('header')
    <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800">Daftar Customer</h2>
        <a href="{{ route('admin.customers.create') }}" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">+ Tambah Customer</a>
    </div>
@endsection
@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
    <form method="GET" class="flex gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / HP / member code..." class="rounded-lg border-gray-300 text-sm flex-1">
        <button type="submit" class="px-4 py-2 bg-gray-800 text-white text-sm rounded-lg">Cari</button>
    </form>
</div>
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50"><tr>
            <th class="text-left py-3 px-4 font-medium text-gray-500">Member</th>
            <th class="text-left py-3 px-4 font-medium text-gray-500">Nama</th>
            <th class="text-left py-3 px-4 font-medium text-gray-500">HP</th>
            <th class="text-center py-3 px-4 font-medium text-gray-500">Total Cuci</th>
            <th class="text-center py-3 px-4 font-medium text-gray-500">Poin</th>
            <th class="text-center py-3 px-4 font-medium text-gray-500">Reward</th>
            <th class="text-center py-3 px-4 font-medium text-gray-500">Aksi</th>
        </tr></thead>
        <tbody>
        @forelse($customers as $c)
        <tr class="border-t border-gray-100 hover:bg-gray-50">
            <td class="py-3 px-4 font-mono text-xs text-indigo-600">{{ $c->member_code }}</td>
            <td class="py-3 px-4 font-medium">{{ $c->user->name ?? '-' }}</td>
            <td class="py-3 px-4 text-gray-600">{{ $c->phone }}</td>
            <td class="py-3 px-4 text-center font-semibold">{{ $c->total_washes }}</td>
            <td class="py-3 px-4 text-center"><span class="bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded text-xs font-medium">{{ $c->total_points }}</span></td>
            <td class="py-3 px-4 text-center">
                @if($c->getAvailableFreeWashes() > 0)<span class="bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded text-xs font-medium">{{ $c->getAvailableFreeWashes() }} free</span>@else<span class="text-gray-400 text-xs">-</span>@endif
            </td>
            <td class="py-3 px-4 text-center">
                <a href="{{ route('admin.customers.show', $c) }}" class="text-indigo-600 hover:underline text-xs mr-1">Detail</a>
                <a href="{{ route('admin.customers.edit', $c) }}" class="text-gray-600 hover:underline text-xs">Edit</a>
            </td>
        </tr>
        @empty
        <tr><td colspan="7" class="py-8 text-center text-gray-500">Belum ada customer</td></tr>
        @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t border-gray-200">{{ $customers->withQueryString()->links() }}</div>
</div>
@endsection
