@extends('layouts.app')
@section('title', 'Detail Customer')
@section('header')
    <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800">Detail Customer</h2>
        <a href="{{ route('admin.customers.index') }}" class="px-3 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200">← Kembali</a>
    </div>
@endsection
@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="space-y-6">
        <!-- Profile Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 text-center">
            <div class="w-16 h-16 rounded-full bg-indigo-100 flex items-center justify-center mx-auto mb-3">
                <span class="text-2xl font-bold text-indigo-600">{{ strtoupper(substr($customer->user->name, 0, 1)) }}</span>
            </div>
            <h3 class="font-bold text-lg text-gray-800">{{ $customer->user->name }}</h3>
            <p class="text-sm text-indigo-600 font-mono">{{ $customer->member_code }}</p>
            <p class="text-sm text-gray-500">{{ $customer->phone }}</p>
            <div class="grid grid-cols-3 gap-2 mt-4">
                <div class="bg-indigo-50 rounded-lg p-2"><p class="text-xs text-gray-500">Cuci</p><p class="font-bold text-indigo-600">{{ $customer->total_washes }}</p></div>
                <div class="bg-green-50 rounded-lg p-2"><p class="text-xs text-gray-500">Poin</p><p class="font-bold text-green-600">{{ $customer->total_points }}</p></div>
                <div class="bg-yellow-50 rounded-lg p-2"><p class="text-xs text-gray-500">Free</p><p class="font-bold text-yellow-600">{{ $customer->getAvailableFreeWashes() }}</p></div>
            </div>
            <div class="mt-4 bg-gray-50 rounded-lg p-3">
                <p class="text-xs text-gray-500 mb-1">Progress ke cuci gratis berikutnya</p>
                <div class="w-full bg-gray-200 rounded-full h-3"><div class="bg-indigo-600 h-3 rounded-full transition-all" style="width:{{ ($customer->total_washes % 10) * 10 }}%"></div></div>
                <p class="text-xs text-gray-600 mt-1">{{ $customer->total_washes % 10 }}/10 cuci</p>
            </div>
            <a href="{{ route('admin.customers.edit', $customer) }}" class="mt-4 inline-block px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 w-full">Edit Customer</a>
        </div>

        <!-- Vehicles -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-800 mb-3">Kendaraan</h3>
            @foreach($customer->vehicles as $v)
            <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                <div><p class="font-semibold text-sm">{{ $v->plate_number }}</p><p class="text-xs text-gray-500">{{ $v->brand }} {{ $v->model }} • {{ ucfirst($v->vehicle_type) }}</p></div>
            </div>
            @endforeach
            <form method="POST" action="{{ route('admin.customers.add-vehicle', $customer) }}" class="mt-4 pt-3 border-t border-gray-200">
                @csrf
                <p class="text-sm font-medium text-gray-700 mb-2">Tambah Kendaraan</p>
                <div class="grid grid-cols-2 gap-2">
                    <input type="text" name="plate_number" required placeholder="Plat" class="rounded-lg border-gray-300 text-xs uppercase">
                    <select name="vehicle_type" required class="rounded-lg border-gray-300 text-xs"><option value="mobil">Mobil</option><option value="motor">Motor</option><option value="suv">SUV</option></select>
                    <input type="text" name="brand" placeholder="Merk" class="rounded-lg border-gray-300 text-xs">
                    <input type="text" name="vehicle_model" placeholder="Model" class="rounded-lg border-gray-300 text-xs">
                </div>
                <button type="submit" class="mt-2 w-full py-1.5 bg-gray-800 text-white text-xs rounded-lg">+ Tambah</button>
            </form>
        </div>
    </div>

    <div class="lg:col-span-2 space-y-6">
        <!-- Recent Transactions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-800 mb-3">Transaksi Terakhir</h3>
            <table class="w-full text-sm">
                <thead><tr class="border-b border-gray-200">
                    <th class="text-left py-2 font-medium text-gray-500 text-xs">Invoice</th>
                    <th class="text-left py-2 font-medium text-gray-500 text-xs">Tanggal</th>
                    <th class="text-left py-2 font-medium text-gray-500 text-xs">Layanan</th>
                    <th class="text-right py-2 font-medium text-gray-500 text-xs">Total</th>
                </tr></thead>
                <tbody>
                @forelse($customer->transactions as $trx)
                <tr class="border-b border-gray-50">
                    <td class="py-2"><a href="{{ route('transactions.show', $trx) }}" class="text-indigo-600 hover:underline font-mono text-xs">{{ $trx->invoice_number }}</a></td>
                    <td class="py-2 text-xs text-gray-600">{{ $trx->transaction_date->format('d/m/Y') }}</td>
                    <td class="py-2">@foreach($trx->details as $d)<span class="bg-gray-100 text-xs px-1 rounded mr-1">{{ $d->service_name }}</span>@endforeach</td>
                    <td class="py-2 text-right font-semibold text-xs">{{ $trx->is_reward_claim ? 'GRATIS' : 'Rp '.number_format($trx->grand_total,0,',','.') }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="py-4 text-center text-gray-400 text-sm">Belum ada transaksi</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <!-- Point History -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-800 mb-3">Riwayat Poin</h3>
            <div class="space-y-2">
                @forelse($customer->pointHistories as $ph)
                <div class="flex items-center justify-between py-2 border-b border-gray-50">
                    <div>
                        <p class="text-sm {{ $ph->type === 'earned' ? 'text-green-600' : 'text-red-600' }} font-medium">{{ $ph->type === 'earned' ? '+' : '-' }}{{ $ph->points }} poin</p>
                        <p class="text-xs text-gray-500">{{ $ph->description }}</p>
                    </div>
                    <div class="text-right"><p class="text-xs text-gray-500">{{ $ph->created_at->format('d/m/Y H:i') }}</p><p class="text-xs text-gray-400">Saldo: {{ $ph->balance_after }}</p></div>
                </div>
                @empty
                <p class="text-sm text-gray-400">Belum ada riwayat poin</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
