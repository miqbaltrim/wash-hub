@extends('layouts.app')
@section('title', 'Riwayat Cuci')
@section('header')<h2 class="font-semibold text-xl text-gray-800">Riwayat Cuci</h2>@endsection
@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50"><tr>
            <th class="text-left py-3 px-4 font-medium text-gray-500">Tanggal</th>
            <th class="text-left py-3 px-4 font-medium text-gray-500">Invoice</th>
            <th class="text-left py-3 px-4 font-medium text-gray-500">Layanan</th>
            <th class="text-center py-3 px-4 font-medium text-gray-500">Status</th>
            <th class="text-right py-3 px-4 font-medium text-gray-500">Total</th>
        </tr></thead>
        <tbody>
        @forelse($transactions as $trx)
        <tr class="border-t border-gray-100 hover:bg-gray-50">
            <td class="py-3 px-4 text-gray-600">{{ $trx->transaction_date->format('d M Y') }}</td>
            <td class="py-3 px-4 font-mono text-xs text-indigo-600">{{ $trx->invoice_number }}</td>
            <td class="py-3 px-4">
                @foreach($trx->details as $d)
                <span class="inline-block bg-gray-100 text-gray-700 text-xs rounded px-2 py-0.5 mr-1 mb-1">{{ $d->service_name }}</span>
                @endforeach
            </td>
            <td class="py-3 px-4 text-center">
                @php $wc = ['waiting'=>'bg-yellow-100 text-yellow-700','in_progress'=>'bg-blue-100 text-blue-700','done'=>'bg-green-100 text-green-700','picked_up'=>'bg-gray-100 text-gray-700']; @endphp
                <span class="px-2 py-0.5 rounded text-xs font-medium {{ $wc[$trx->wash_status] ?? '' }}">{{ ucfirst(str_replace('_', ' ', $trx->wash_status)) }}</span>
            </td>
            <td class="py-3 px-4 text-right font-semibold">
                @if($trx->is_reward_claim)
                    <span class="text-green-600">GRATIS</span>
                @else
                    Rp {{ number_format($trx->grand_total, 0, ',', '.') }}
                @endif
            </td>
        </tr>
        @empty
        <tr><td colspan="5" class="py-8 text-center text-gray-500">Belum ada riwayat cuci</td></tr>
        @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t border-gray-200">{{ $transactions->links() }}</div>
</div>
@endsection
