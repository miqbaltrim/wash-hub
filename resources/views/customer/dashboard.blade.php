@extends('layouts.app')
@section('title', 'My Dashboard')
@section('content')
    <!-- Welcome -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-6 mb-6 text-white">
        <h1 class="text-2xl font-bold">Halo, {{ $customer->user->name }}! 👋</h1>
        <p class="text-indigo-100 mt-1">Member Code: <span class="font-mono font-bold">{{ $customer->member_code }}</span></p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 text-center">
            <p class="text-3xl font-bold text-indigo-600">{{ $stats['total_washes'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Total Cuci</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 text-center">
            <p class="text-3xl font-bold text-green-600">{{ $stats['total_points'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Poin Saya</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 text-center">
            <p class="text-3xl font-bold text-yellow-600">{{ $stats['available_rewards'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Cuci Gratis</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 text-center">
            <p class="text-3xl font-bold text-purple-600">{{ $stats['washes_until_reward'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Cuci Lagi</p>
        </div>
    </div>

    <!-- Progress & Reward -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-800 mb-3">Progress Reward</h3>
            <p class="text-sm text-gray-500 mb-2">Cuci 10x dapat 1x cuci GRATIS!</p>
            <div class="flex gap-1 mb-3">
                @for($i = 1; $i <= 10; $i++)
                <div class="flex-1 h-8 rounded {{ $i <= ($customer->total_washes % 10 ?: ($customer->total_washes > 0 && $customer->total_washes % 10 == 0 ? 10 : 0)) ? 'bg-indigo-500' : 'bg-gray-200' }} flex items-center justify-center">
                    <span class="text-xs {{ $i <= ($customer->total_washes % 10 ?: 0) ? 'text-white font-bold' : 'text-gray-400' }}">{{ $i }}</span>
                </div>
                @endfor
            </div>
            @if($stats['available_rewards'] > 0)
            <form method="POST" action="{{ route('customer.rewards.claim') }}">
                @csrf
                <button type="submit" class="w-full py-2 bg-yellow-500 text-white font-semibold rounded-lg hover:bg-yellow-600 transition">🎉 Klaim Cuci Gratis!</button>
            </form>
            @else
            <p class="text-center text-sm text-gray-500 py-2">{{ $stats['washes_until_reward'] }} cuci lagi untuk reward berikutnya</p>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-800 mb-3">Kendaraan Saya</h3>
            @forelse($customer->vehicles as $v)
            <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                <div><p class="font-bold text-sm">{{ $v->plate_number }}</p><p class="text-xs text-gray-500">{{ $v->brand }} {{ $v->model }} • {{ ucfirst($v->vehicle_type) }} {{ $v->color ? '• '.$v->color : '' }}</p></div>
            </div>
            @empty
            <p class="text-sm text-gray-400">Belum ada kendaraan terdaftar</p>
            @endforelse
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold text-gray-800">Transaksi Terakhir</h3>
            <a href="{{ route('customer.transactions') }}" class="text-indigo-600 text-sm hover:underline">Lihat Semua →</a>
        </div>
        <div class="space-y-3">
            @forelse($customer->transactions as $trx)
            <div class="flex items-center justify-between py-2 border-b border-gray-50">
                <div>
                    <p class="text-sm font-medium">{{ $trx->details->pluck('service_name')->join(', ') }}</p>
                    <p class="text-xs text-gray-500">{{ $trx->transaction_date->format('d M Y') }} • {{ $trx->invoice_number }}</p>
                </div>
                <span class="font-semibold text-sm {{ $trx->is_reward_claim ? 'text-green-600' : 'text-gray-800' }}">
                    {{ $trx->is_reward_claim ? 'GRATIS' : 'Rp '.number_format($trx->grand_total,0,',','.') }}
                </span>
            </div>
            @empty
            <p class="text-sm text-gray-400">Belum ada transaksi</p>
            @endforelse
        </div>
    </div>
@endsection
