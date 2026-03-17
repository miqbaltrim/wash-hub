@extends('layouts.app')
@section('title', 'Laporan Bulanan')
@section('header')
    <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800">Laporan Bulanan</h2>
        <a href="{{ route('reports.export-pdf', ['type' => 'monthly', 'month' => $month, 'year' => $year]) }}" class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700">📄 Export PDF</a>
    </div>
@endsection
@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
        <form method="GET" class="flex items-center gap-3">
            <select name="month" class="rounded-lg border-gray-300 text-sm">
                @for($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                @endfor
            </select>
            <select name="year" class="rounded-lg border-gray-300 text-sm">
                @for($y = now()->year; $y >= now()->year - 3; $y--)
                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700">Tampilkan</button>
        </form>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4"><p class="text-sm text-gray-500">Total Transaksi</p><p class="text-2xl font-bold">{{ $summary['total_transactions'] }}</p></div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4"><p class="text-sm text-gray-500">Total Revenue</p><p class="text-2xl font-bold text-green-600">Rp {{ number_format($summary['total_revenue'],0,',','.') }}</p></div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4"><p class="text-sm text-gray-500">Rata-rata / Hari</p><p class="text-2xl font-bold text-indigo-600">Rp {{ number_format($summary['avg_daily_revenue'],0,',','.') }}</p></div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4"><p class="text-sm text-gray-500">Total Diskon</p><p class="text-2xl font-bold text-red-500">Rp {{ number_format($summary['total_discount'],0,',','.') }}</p></div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-800 mb-4">Revenue Harian</h3>
            <canvas id="dailyChart" height="250"></canvas>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-800 mb-4">Metode Pembayaran</h3>
            <canvas id="paymentChart" height="250"></canvas>
        </div>
    </div>

    <!-- Service Popularity -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6">
        <h3 class="font-semibold text-gray-800 mb-4">Layanan Terpopuler</h3>
        <table class="w-full text-sm">
            <thead><tr class="border-b border-gray-200">
                <th class="text-left py-2 font-medium text-gray-500">#</th>
                <th class="text-left py-2 font-medium text-gray-500">Layanan</th>
                <th class="text-center py-2 font-medium text-gray-500">Jumlah</th>
                <th class="text-right py-2 font-medium text-gray-500">Revenue</th>
            </tr></thead>
            <tbody>
            @foreach($servicePopularity as $i => $svc)
            <tr class="border-b border-gray-50">
                <td class="py-2">{{ $i + 1 }}</td>
                <td class="py-2 font-medium">{{ $svc->service_name }}</td>
                <td class="py-2 text-center"><span class="bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded text-xs font-medium">{{ $svc->total_qty }}x</span></td>
                <td class="py-2 text-right font-semibold">Rp {{ number_format($svc->total_revenue,0,',','.') }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <!-- Daily Breakdown -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-200"><h3 class="font-semibold text-gray-800">Detail Per Hari</h3></div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50"><tr>
                <th class="text-left py-3 px-4 font-medium text-gray-500">Tanggal</th>
                <th class="text-center py-3 px-4 font-medium text-gray-500">Transaksi</th>
                <th class="text-right py-3 px-4 font-medium text-gray-500">Revenue</th>
                <th class="text-right py-3 px-4 font-medium text-gray-500">Diskon</th>
            </tr></thead>
            <tbody>
            @foreach($dailyData as $day)
            <tr class="border-t border-gray-100 hover:bg-gray-50">
                <td class="py-3 px-4">{{ \Carbon\Carbon::parse($day->date)->format('d M Y (D)') }}</td>
                <td class="py-3 px-4 text-center font-medium">{{ $day->total_trx }}</td>
                <td class="py-3 px-4 text-right font-semibold text-green-600">Rp {{ number_format($day->revenue,0,',','.') }}</td>
                <td class="py-3 px-4 text-right text-red-500">Rp {{ number_format($day->discount,0,',','.') }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
@push('head')<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>@endpush
@push('scripts')
<script>
new Chart(document.getElementById('dailyChart'), {
    type: 'line',
    data: {
        labels: {!! json_encode($dailyData->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d'))) !!},
        datasets: [{
            label: 'Revenue', data: {!! json_encode($dailyData->pluck('revenue')) !!},
            borderColor: 'rgb(99, 102, 241)', backgroundColor: 'rgba(99, 102, 241, 0.1)', fill: true, tension: 0.3
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { callback: v => 'Rp ' + (v/1000) + 'k' } } } }
});
new Chart(document.getElementById('paymentChart'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($paymentBreakdown->pluck('payment_method')->map(fn($m) => strtoupper(str_replace('_', ' ', $m)))) !!},
        datasets: [{ data: {!! json_encode($paymentBreakdown->pluck('total')) !!}, backgroundColor: ['#10b981','#6366f1','#f59e0b','#8b5cf6','#ec4899','#06b6d4'] }]
    },
    options: { responsive: true }
});
</script>
@endpush
