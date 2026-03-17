@extends('layouts.app')
@section('title', 'Daftar Transaksi')

@section('header')
    <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800">Daftar Transaksi</h2>
        <a href="{{ route('transactions.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">+ Transaksi Baru</a>
    </div>
@endsection

@section('content')
    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari invoice / plat..." class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="rounded-lg border-gray-300 text-sm">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="rounded-lg border-gray-300 text-sm">
            <select name="status" class="rounded-lg border-gray-300 text-sm">
                <option value="">Semua Status</option>
                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <select name="wash_status" class="rounded-lg border-gray-300 text-sm">
                <option value="">Semua Wash</option>
                <option value="waiting" {{ request('wash_status') == 'waiting' ? 'selected' : '' }}>Menunggu</option>
                <option value="in_progress" {{ request('wash_status') == 'in_progress' ? 'selected' : '' }}>Sedang Cuci</option>
                <option value="done" {{ request('wash_status') == 'done' ? 'selected' : '' }}>Selesai</option>
                <option value="picked_up" {{ request('wash_status') == 'picked_up' ? 'selected' : '' }}>Diambil</option>
            </select>
            <button type="submit" class="bg-gray-800 text-white rounded-lg text-sm hover:bg-gray-700 transition">Filter</button>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left py-3 px-4 font-medium text-gray-500">Invoice</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-500">Tanggal</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-500">Customer</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-500">Plat</th>
                        <th class="text-right py-3 px-4 font-medium text-gray-500">Total</th>
                        <th class="text-center py-3 px-4 font-medium text-gray-500">Bayar</th>
                        <th class="text-center py-3 px-4 font-medium text-gray-500">Status Cuci</th>
                        <th class="text-center py-3 px-4 font-medium text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $trx)
                    <tr class="border-t border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-4 font-mono text-xs">
                            <a href="{{ route('transactions.show', $trx) }}" class="text-indigo-600 hover:underline">{{ $trx->invoice_number }}</a>
                        </td>
                        <td class="py-3 px-4 text-gray-600">{{ $trx->transaction_date->format('d/m/Y') }}</td>
                        <td class="py-3 px-4">{{ $trx->customerProfile->user->name ?? 'Walk-in' }}</td>
                        <td class="py-3 px-4 font-semibold">{{ $trx->plate_number }}</td>
                        <td class="py-3 px-4 text-right font-semibold">
                            @if($trx->is_reward_claim)
                                <span class="text-green-600">GRATIS</span>
                            @else
                                Rp {{ number_format($trx->grand_total, 0, ',', '.') }}
                            @endif
                        </td>
                        <td class="py-3 px-4 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $trx->payment_status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ucfirst($trx->payment_status) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            @php $wc = ['waiting'=>'bg-yellow-100 text-yellow-700','in_progress'=>'bg-blue-100 text-blue-700','done'=>'bg-green-100 text-green-700','picked_up'=>'bg-gray-100 text-gray-700']; @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $wc[$trx->wash_status] ?? '' }}">
                                {{ ucfirst(str_replace('_', ' ', $trx->wash_status)) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <a href="{{ route('transactions.show', $trx) }}" class="text-indigo-600 hover:text-indigo-800 text-xs font-medium">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="py-8 text-center text-gray-500">Belum ada transaksi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-200">{{ $transactions->withQueryString()->links() }}</div>
    </div>
@endsection
