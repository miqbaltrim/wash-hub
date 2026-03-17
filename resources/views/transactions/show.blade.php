@extends('layouts.app')
@section('title', 'Detail Transaksi')

@section('header')
    <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800">Detail Transaksi</h2>
        <div class="flex items-center space-x-2">
            <a href="{{ route('transactions.print', $transaction) }}" target="_blank" class="inline-flex items-center px-3 py-2 bg-gray-800 text-white text-sm rounded-lg hover:bg-gray-700">🖨 Cetak Struk</a>
            <a href="{{ route('transactions.index') }}" class="inline-flex items-center px-3 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200">← Kembali</a>
        </div>
    </div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Info -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-800 font-mono">{{ $transaction->invoice_number }}</h3>
                @php $wc = ['waiting'=>'bg-yellow-100 text-yellow-700','in_progress'=>'bg-blue-100 text-blue-700','done'=>'bg-green-100 text-green-700','picked_up'=>'bg-gray-100 text-gray-700']; @endphp
                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $wc[$transaction->wash_status] ?? '' }}">{{ ucfirst(str_replace('_', ' ', $transaction->wash_status)) }}</span>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div><p class="text-gray-500">Tanggal</p><p class="font-medium">{{ $transaction->transaction_date->format('d/m/Y') }}</p></div>
                <div><p class="text-gray-500">Kasir</p><p class="font-medium">{{ $transaction->cashier->name ?? '-' }}</p></div>
                <div><p class="text-gray-500">Plat Nomor</p><p class="font-bold text-lg">{{ $transaction->plate_number }}</p></div>
                <div><p class="text-gray-500">Tipe</p><p class="font-medium">{{ ucfirst($transaction->vehicle_type) }}</p></div>
            </div>
        </div>

        <!-- Items -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-800 mb-4">Detail Layanan</h3>
            <table class="w-full text-sm">
                <thead><tr class="border-b border-gray-200">
                    <th class="text-left py-2 font-medium text-gray-500">Layanan</th>
                    <th class="text-center py-2 font-medium text-gray-500">Qty</th>
                    <th class="text-right py-2 font-medium text-gray-500">Harga</th>
                    <th class="text-right py-2 font-medium text-gray-500">Subtotal</th>
                </tr></thead>
                <tbody>
                @foreach($transaction->details as $detail)
                <tr class="border-b border-gray-100">
                    <td class="py-3"><p class="font-medium">{{ $detail->service_name }}</p><p class="text-xs text-gray-500">{{ $detail->service_category }}</p></td>
                    <td class="py-3 text-center">{{ $detail->qty }}</td>
                    <td class="py-3 text-right">Rp {{ number_format($detail->unit_price, 0, ',', '.') }}</td>
                    <td class="py-3 text-right font-medium">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                </tbody>
                <tfoot class="border-t-2 border-gray-300">
                    <tr><td colspan="3" class="py-2 text-right text-gray-500">Subtotal</td><td class="py-2 text-right font-medium">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</td></tr>
                    @if($transaction->discount_amount > 0)
                    <tr><td colspan="3" class="py-1 text-right text-gray-500">Diskon{{ $transaction->discount_percent > 0 ? " ({$transaction->discount_percent}%)" : '' }}</td><td class="py-1 text-right text-red-500">- Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</td></tr>
                    @endif
                    <tr class="text-lg"><td colspan="3" class="py-2 text-right font-bold">TOTAL</td><td class="py-2 text-right font-bold text-indigo-600">
                        @if($transaction->is_reward_claim) GRATIS @else Rp {{ number_format($transaction->grand_total, 0, ',', '.') }} @endif
                    </td></tr>
                </tfoot>
            </table>
        </div>

        <!-- Payment Info -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-800 mb-4">Pembayaran</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div><p class="text-gray-500">Metode</p><p class="font-medium">{{ strtoupper(str_replace('_', ' ', $transaction->payment_method)) }}</p></div>
                <div><p class="text-gray-500">Status</p><p class="font-medium {{ $transaction->payment_status === 'paid' ? 'text-green-600' : 'text-red-600' }}">{{ ucfirst($transaction->payment_status) }}</p></div>
                <div><p class="text-gray-500">Dibayar</p><p class="font-medium">Rp {{ number_format($transaction->payment_amount, 0, ',', '.') }}</p></div>
                <div><p class="text-gray-500">Kembalian</p><p class="font-medium">Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</p></div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Customer Info -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-800 mb-3">Customer</h3>
            @if($transaction->customerProfile)
                <p class="font-medium text-gray-800">{{ $transaction->customerProfile->user->name ?? '-' }}</p>
                <p class="text-sm text-gray-500">{{ $transaction->customerProfile->member_code }}</p>
                <p class="text-sm text-gray-500">{{ $transaction->customerProfile->phone }}</p>
                <div class="mt-3 grid grid-cols-2 gap-2 text-center">
                    <div class="bg-indigo-50 rounded-lg p-2"><p class="text-xs text-gray-500">Poin</p><p class="font-bold text-indigo-600">{{ $transaction->customerProfile->total_points }}</p></div>
                    <div class="bg-green-50 rounded-lg p-2"><p class="text-xs text-gray-500">Cuci</p><p class="font-bold text-green-600">{{ $transaction->customerProfile->total_washes }}</p></div>
                </div>
                @if($transaction->points_earned > 0)
                <p class="mt-2 text-sm text-green-600 font-medium">+{{ $transaction->points_earned }} poin diperoleh</p>
                @endif
            @else
                <p class="text-sm text-gray-500">Walk-in customer</p>
            @endif
        </div>

        <!-- Status Update -->
        @if($transaction->payment_status === 'paid' && $transaction->wash_status !== 'picked_up')
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-800 mb-3">Update Status</h3>
            <form method="POST" action="{{ route('transactions.update-status', $transaction) }}">
                @csrf @method('PATCH')
                <select name="wash_status" class="w-full rounded-lg border-gray-300 text-sm mb-3">
                    <option value="waiting" {{ $transaction->wash_status === 'waiting' ? 'selected' : '' }}>Menunggu</option>
                    <option value="in_progress" {{ $transaction->wash_status === 'in_progress' ? 'selected' : '' }}>Sedang Dicuci</option>
                    <option value="done" {{ $transaction->wash_status === 'done' ? 'selected' : '' }}>Selesai</option>
                    <option value="picked_up" {{ $transaction->wash_status === 'picked_up' ? 'selected' : '' }}>Diambil</option>
                </select>
                <button type="submit" class="w-full py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700">Update Status</button>
            </form>
        </div>
        @endif

        <!-- Cancel -->
        @if($transaction->payment_status === 'paid')
        <form method="POST" action="{{ route('transactions.cancel', $transaction) }}" onsubmit="return confirm('Yakin batalkan transaksi ini? Poin akan di-rollback.')">
            @csrf
            <button type="submit" class="w-full py-2 bg-red-50 text-red-600 border border-red-200 text-sm rounded-lg hover:bg-red-100">Batalkan Transaksi</button>
        </form>
        @endif
    </div>
</div>
@endsection
