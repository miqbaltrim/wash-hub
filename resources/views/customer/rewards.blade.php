@extends('layouts.app')
@section('title', 'Rewards')
@section('header')<h2 class="font-semibold text-xl text-gray-800">Rewards Saya</h2>@endsection
@section('content')
    <!-- Reward Info -->
    <div class="bg-gradient-to-r from-yellow-400 to-orange-500 rounded-2xl p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold">Program Loyalitas</h3>
                <p class="text-yellow-100 mt-1">Cuci 10x dapat 1x cuci GRATIS! Berlaku untuk semua jenis cuci.</p>
            </div>
            <div class="text-center">
                <p class="text-4xl font-bold">{{ $stats['available_rewards'] }}</p>
                <p class="text-yellow-100 text-sm">Cuci Gratis Tersedia</p>
            </div>
        </div>
    </div>

    <!-- Progress -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6">
        <h3 class="font-semibold text-gray-800 mb-3">Progress Saat Ini</h3>
        <div class="flex gap-2 mb-3">
            @for($i = 1; $i <= 10; $i++)
            @php $filled = $i <= ($customer->total_washes % 10 ?: ($customer->total_washes > 0 && $customer->total_washes % 10 == 0 ? 10 : 0)); @endphp
            <div class="flex-1 h-12 rounded-lg {{ $filled ? 'bg-indigo-500' : 'bg-gray-100' }} flex flex-col items-center justify-center transition-all">
                @if($filled)
                    <span class="text-white text-lg">✓</span>
                @else
                    <span class="text-gray-400 text-sm font-medium">{{ $i }}</span>
                @endif
            </div>
            @endfor
        </div>
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-600">Total cuci: <span class="font-bold">{{ $stats['total_washes'] }}</span></p>
            @if($stats['washes_until_next'] > 0 && $stats['washes_until_next'] < 10)
                <p class="text-sm text-gray-600"><span class="font-bold text-indigo-600">{{ $stats['washes_until_next'] }}</span> cuci lagi untuk reward berikutnya</p>
            @endif
        </div>

        @if($stats['available_rewards'] > 0)
        <form method="POST" action="{{ route('customer.rewards.claim') }}" class="mt-4">
            @csrf
            <button type="submit" class="w-full py-3 bg-yellow-500 text-white font-bold rounded-lg hover:bg-yellow-600 transition text-lg" onclick="return confirm('Klaim 1x cuci gratis sekarang?')">
                🎉 Klaim Cuci Gratis Sekarang!
            </button>
            <p class="text-xs text-gray-500 text-center mt-2">Tunjukkan kode klaim ke kasir saat datang</p>
        </form>
        @endif
    </div>

    <!-- Claim History -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-5 border-b border-gray-200">
            <h3 class="font-semibold text-gray-800">Riwayat Reward</h3>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50"><tr>
                <th class="text-left py-3 px-4 font-medium text-gray-500">Tanggal Klaim</th>
                <th class="text-left py-3 px-4 font-medium text-gray-500">Tipe</th>
                <th class="text-center py-3 px-4 font-medium text-gray-500">Cuci ke-</th>
                <th class="text-center py-3 px-4 font-medium text-gray-500">Status</th>
                <th class="text-left py-3 px-4 font-medium text-gray-500">Digunakan</th>
            </tr></thead>
            <tbody>
            @forelse($claims as $claim)
            <tr class="border-t border-gray-100">
                <td class="py-3 px-4 text-gray-600">{{ $claim->claimed_at?->format('d M Y H:i') ?? '-' }}</td>
                <td class="py-3 px-4 font-medium">{{ ucfirst(str_replace('_', ' ', $claim->reward_type)) }}</td>
                <td class="py-3 px-4 text-center">{{ $claim->washes_at_claim }}</td>
                <td class="py-3 px-4 text-center">
                    @php $sc = ['claimed'=>'bg-yellow-100 text-yellow-700','used'=>'bg-green-100 text-green-700','expired'=>'bg-gray-100 text-gray-700','cancelled'=>'bg-red-100 text-red-700']; @endphp
                    <span class="px-2 py-0.5 rounded text-xs font-medium {{ $sc[$claim->status] ?? '' }}">{{ ucfirst($claim->status) }}</span>
                </td>
                <td class="py-3 px-4 text-gray-600 text-xs">
                    @if($claim->used_at) {{ $claim->used_at->format('d M Y H:i') }}
                    @elseif($claim->status === 'claimed') <span class="text-yellow-600 font-medium">Belum digunakan</span>
                    @else - @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="py-8 text-center text-gray-500">Belum ada riwayat reward</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
