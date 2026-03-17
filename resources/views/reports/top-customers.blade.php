@extends('layouts.app')
@section('title', 'Top Customers')
@section('header')<h2 class="font-semibold text-xl text-gray-800">Top Customers</h2>@endsection
@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50"><tr>
            <th class="text-center py-3 px-4 font-medium text-gray-500 w-12">#</th>
            <th class="text-left py-3 px-4 font-medium text-gray-500">Customer</th>
            <th class="text-left py-3 px-4 font-medium text-gray-500">Member</th>
            <th class="text-center py-3 px-4 font-medium text-gray-500">Total Cuci</th>
            <th class="text-center py-3 px-4 font-medium text-gray-500">Poin</th>
            <th class="text-right py-3 px-4 font-medium text-gray-500">Total Spending</th>
            <th class="text-center py-3 px-4 font-medium text-gray-500">Aksi</th>
        </tr></thead>
        <tbody>
        @foreach($customers as $i => $c)
        <tr class="border-t border-gray-100 hover:bg-gray-50">
            <td class="py-3 px-4 text-center">
                @if($i < 3)
                    <span class="w-7 h-7 rounded-full inline-flex items-center justify-center text-white text-xs font-bold {{ $i === 0 ? 'bg-yellow-500' : ($i === 1 ? 'bg-gray-400' : 'bg-amber-700') }}">{{ $i + 1 }}</span>
                @else
                    <span class="text-gray-500">{{ $i + 1 }}</span>
                @endif
            </td>
            <td class="py-3 px-4 font-medium">{{ $c->user->name ?? '-' }}</td>
            <td class="py-3 px-4 font-mono text-xs text-indigo-600">{{ $c->member_code }}</td>
            <td class="py-3 px-4 text-center font-semibold">{{ $c->transactions_count }}</td>
            <td class="py-3 px-4 text-center"><span class="bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded text-xs font-medium">{{ $c->total_points }}</span></td>
            <td class="py-3 px-4 text-right font-bold text-green-600">Rp {{ number_format($c->transactions_sum_grand_total ?? 0, 0, ',', '.') }}</td>
            <td class="py-3 px-4 text-center"><a href="{{ route('admin.customers.show', $c) }}" class="text-indigo-600 hover:underline text-xs">Detail</a></td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
