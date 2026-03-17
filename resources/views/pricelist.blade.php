<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pricelist - {{ config('app.name', 'Wash Hub') }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50 font-sans antialiased">
    <!-- Header -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
        <div class="max-w-5xl mx-auto px-4 py-12 text-center">
            <h1 class="text-4xl font-bold mb-2">🚗 Wash Hub</h1>
            <p class="text-indigo-200 text-lg">Professional Car Wash Service</p>
            <p class="text-indigo-300 text-sm mt-2">{{ \App\Models\Setting::getValue('app_address', '') }}</p>
            <div class="mt-6">
                <a href="{{ route('login') }}" class="inline-block px-6 py-2 bg-white text-indigo-600 font-semibold rounded-lg hover:bg-indigo-50 transition mr-2">Login</a>
                <a href="{{ route('register') }}" class="inline-block px-6 py-2 bg-indigo-500 text-white font-semibold rounded-lg hover:bg-indigo-400 transition border border-indigo-400">Daftar Member</a>
            </div>
        </div>
    </div>

    <!-- Pricelist -->
    <div class="max-w-5xl mx-auto px-4 py-10">
        <h2 class="text-2xl font-bold text-gray-800 text-center mb-8">Daftar Harga Layanan</h2>

        @foreach($categories as $cat)
        <div class="mb-8">
            <h3 class="text-lg font-bold text-gray-700 mb-3 flex items-center">
                <span class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center mr-2 text-sm">{{ $loop->iteration }}</span>
                {{ $cat->name }}
                @if($cat->description)<span class="text-sm font-normal text-gray-500 ml-2">- {{ $cat->description }}</span>@endif
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($cat->activeServices as $svc)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="font-semibold text-gray-800">{{ $svc->name }}</h4>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $svc->duration_minutes }} menit • {{ $svc->vehicle_type === 'all' ? 'Semua kendaraan' : ucfirst($svc->vehicle_type) }}</p>
                            @if($svc->description)<p class="text-xs text-gray-400 mt-1">{{ $svc->description }}</p>@endif
                        </div>
                        <span class="text-lg font-bold text-indigo-600 whitespace-nowrap ml-3">Rp {{ number_format($svc->price, 0, ',', '.') }}</span>
                    </div>
                    @if($svc->points_earned > 0)
                    <div class="mt-2"><span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-0.5 rounded font-medium">+{{ $svc->points_earned }} poin</span></div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

        <!-- Loyalty Info -->
        <div class="bg-gradient-to-r from-yellow-400 to-orange-500 rounded-2xl p-6 text-white text-center mt-8">
            <h3 class="text-xl font-bold mb-2">🎉 Program Loyalitas</h3>
            <p class="text-yellow-100">Cuci 10x dapat 1x cuci <span class="font-bold text-white">GRATIS!</span></p>
            <p class="text-yellow-200 text-sm mt-1">Daftar member sekarang untuk mulai kumpulkan poin.</p>
            <a href="{{ route('register') }}" class="inline-block mt-4 px-6 py-2 bg-white text-orange-600 font-bold rounded-lg hover:bg-orange-50 transition">Daftar Sekarang</a>
        </div>
    </div>

    <!-- Footer -->
    <div class="bg-gray-800 text-gray-400 text-center py-6 text-sm mt-10">
        <p>&copy; {{ date('Y') }} {{ config('app.name', 'Wash Hub') }}. All rights reserved.</p>
        <p class="mt-1">{{ \App\Models\Setting::getValue('app_phone', '') }}</p>
    </div>
</body>
</html>
