@extends('layouts.app')
@section('title', 'Poin Saya')
@section('header')<h2 class="font-semibold text-xl text-gray-800">Poin Saya</h2>@endsection
@section('content')
    <!-- Points Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 text-center">
            <p class="text-4xl font-bold text-indigo-600">{{ $customer->total_points }}</p>
            <p class="text-sm text-gray-500 mt-1">Poin Saat Ini</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 text-center">
            <p class="text-4xl font-bold text-green-600">{{ $customer->lifetime_points }}</p>
            <p class="text-sm text-gray-500 mt-1">Total Poin Diperoleh</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 text-center">
            <p class="text-4xl font-bold text-purple-600">{{ $customer->total_washes }}</p>
            <p class="text-sm text-gray-500 mt-1">Total Cuci</p>
        </div>
    </div>

    <!-- Points History -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-5 border-b border-gray-200">
            <h3 class="font-semibold text-gray-800">Riwayat Poin</h3>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50"><tr>
                <th class="text-left py-3 px-4 font-medium text-gray-500">Tanggal</th>
                <th class="text-left py-3 px-4 font-medium text-gray-500">Keterangan</th>
                <th class="text-center py-3 px-4 font-medium text-gray-500">Tipe</th>
                <th class="text-right py-3 px-4 font-medium text-gray-500">Poin</th>
                <th class="text-right py-3 px-4 font-medium text-gray-500">Saldo</th>
            </tr></thead>
            <tbody>
            @forelse($histories as $h)
            <tr class="border-t border-gray-100 hover:bg-gray-50">
                <td class="py-3 px-4 text-gray-600 text-xs">{{ $h->created_at->format('d M Y H:i') }}</td>
                <td class="py-3 px-4">{{ $h->description }}</td>
                <td class="py-3 px-4 text-center">
                    @php $tc = ['earned'=>'bg-green-100 text-green-700','redeemed'=>'bg-red-100 text-red-700','expired'=>'bg-gray-100 text-gray-700','adjusted'=>'bg-yellow-100 text-yellow-700']; @endphp
                    <span class="px-2 py-0.5 rounded text-xs font-medium {{ $tc[$h->type] ?? '' }}">{{ ucfirst($h->type) }}</span>
                </td>
                <td class="py-3 px-4 text-right font-semibold {{ $h->type === 'earned' ? 'text-green-600' : 'text-red-600' }}">
                    {{ $h->type === 'earned' ? '+' : '-' }}{{ $h->points }}
                </td>
                <td class="py-3 px-4 text-right text-gray-600">{{ $h->balance_after }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="py-8 text-center text-gray-500">Belum ada riwayat poin</td></tr>
            @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-200">{{ $histories->links() }}</div>
    </div>
@endsection
