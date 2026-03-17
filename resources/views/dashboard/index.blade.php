@extends('layouts.app')
@section('title', 'Dashboard')

@section('header')
    <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        <a href="{{ route('transactions.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
            + Transaksi Baru
        </a>
    </div>
@endsection

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Transaksi Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $todayTransactions }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                    <span class="text-2xl">🧾</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Revenue Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                    <span class="text-2xl">💰</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Revenue Bulan Ini</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-indigo-50 rounded-lg flex items-center justify-center">
                    <span class="text-2xl">📊</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Customers</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalCustomers }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                    <span class="text-2xl">👥</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Revenue Chart -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Revenue 7 Hari Terakhir</h3>
            <canvas id="revenueChart" height="200"></canvas>
        </div>

        <!-- Top Services -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Layanan Terpopuler</h3>
            <div class="space-y-3">
                @forelse($topServices as $i => $svc)
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <span class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 text-xs flex items-center justify-center font-bold mr-3">{{ $i + 1 }}</span>
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $svc->service_name }}</p>
                            <p class="text-xs text-gray-500">{{ $svc->total_sold }}x terjual</p>
                        </div>
                    </div>
                    <span class="text-sm font-semibold text-gray-700">Rp {{ number_format($svc->revenue, 0, ',', '.') }}</span>
                </div>
                @empty
                <p class="text-sm text-gray-500">Belum ada data</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Active Washes -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Antrian Cuci Aktif ({{ $activeWashes->count() }})</h3>
        @if($activeWashes->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="border-b border-gray-200">
                    <th class="text-left py-3 px-3 font-medium text-gray-500">Invoice</th>
                    <th class="text-left py-3 px-3 font-medium text-gray-500">Customer</th>
                    <th class="text-left py-3 px-3 font-medium text-gray-500">Plat</th>
                    <th class="text-left py-3 px-3 font-medium text-gray-500">Layanan</th>
                    <th class="text-left py-3 px-3 font-medium text-gray-500">Status</th>
                    <th class="text-left py-3 px-3 font-medium text-gray-500">Aksi</th>
                </tr></thead>
                <tbody>
                @foreach($activeWashes as $wash)
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="py-3 px-3 font-mono text-xs">{{ $wash->invoice_number }}</td>
                    <td class="py-3 px-3">{{ $wash->customerProfile->user->name ?? 'Walk-in' }}</td>
                    <td class="py-3 px-3 font-semibold">{{ $wash->plate_number }}</td>
                    <td class="py-3 px-3">
                        @foreach($wash->details as $d)
                            <span class="inline-block bg-gray-100 text-gray-700 text-xs rounded px-2 py-0.5 mr-1 mb-1">{{ $d->service_name }}</span>
                        @endforeach
                    </td>
                    <td class="py-3 px-3">
                        @if($wash->wash_status === 'waiting')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Menunggu</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Sedang Dicuci</span>
                        @endif
                    </td>
                    <td class="py-3 px-3">
                        <form method="POST" action="{{ route('transactions.update-status', $wash) }}" class="inline">
                            @csrf @method('PATCH')
                            @if($wash->wash_status === 'waiting')
                                <input type="hidden" name="wash_status" value="in_progress">
                                <button class="text-blue-600 hover:text-blue-800 text-xs font-medium">Mulai Cuci</button>
                            @else
                                <input type="hidden" name="wash_status" value="done">
                                <button class="text-green-600 hover:text-green-800 text-xs font-medium">Selesai</button>
                            @endif
                        </form>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-gray-500 text-sm">Tidak ada antrian aktif saat ini.</p>
        @endif
    </div>
@endsection

@push('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
@endpush

@push('scripts')
<script>
const ctx = document.getElementById('revenueChart');
if (ctx) {
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($revenueChart->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))) !!},
            datasets: [{
                label: 'Revenue (Rp)',
                data: {!! json_encode($revenueChart->pluck('total')) !!},
                backgroundColor: 'rgba(99, 102, 241, 0.8)',
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { callback: v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v) } }
            }
        }
    });
}
</script>
@endpush
