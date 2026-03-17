@extends('layouts.app')
@section('title', isset($customer) ? 'Edit Customer' : 'Tambah Customer')
@section('header')<h2 class="font-semibold text-xl text-gray-800">{{ isset($customer) ? 'Edit Customer' : 'Tambah Customer' }}</h2>@endsection
@section('content')
<div class="max-w-3xl">
    <form method="POST" action="{{ isset($customer) ? route('admin.customers.update', $customer) : route('admin.customers.store') }}" class="space-y-6">
        @csrf
        @if(isset($customer)) @method('PUT') @endif
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Data Pribadi</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Nama *</label><input type="text" name="name" required value="{{ old('name', $customer->user->name ?? '') }}" class="w-full rounded-lg border-gray-300 text-sm">@error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">No HP *</label><input type="text" name="phone" required value="{{ old('phone', $customer->phone ?? '') }}" class="w-full rounded-lg border-gray-300 text-sm">@error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Email</label><input type="email" name="email" value="{{ old('email', $customer->user->email ?? '') }}" class="w-full rounded-lg border-gray-300 text-sm">@error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label><select name="gender" class="w-full rounded-lg border-gray-300 text-sm"><option value="">-</option><option value="male" {{ old('gender', $customer->gender ?? '') == 'male' ? 'selected' : '' }}>Laki-laki</option><option value="female" {{ old('gender', $customer->gender ?? '') == 'female' ? 'selected' : '' }}>Perempuan</option></select></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label><input type="date" name="birth_date" value="{{ old('birth_date', isset($customer) ? $customer->birth_date?->format('Y-m-d') : '') }}" class="w-full rounded-lg border-gray-300 text-sm"></div>
                <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label><textarea name="address" rows="2" class="w-full rounded-lg border-gray-300 text-sm">{{ old('address', $customer->address ?? '') }}</textarea></div>
            </div>
        </div>
        @if(!isset($customer))
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Kendaraan (Opsional)</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Plat Nomor</label><input type="text" name="plate_number" value="{{ old('plate_number') }}" class="w-full rounded-lg border-gray-300 text-sm uppercase" placeholder="B 1234 XX"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Tipe</label><select name="vehicle_type" class="w-full rounded-lg border-gray-300 text-sm"><option value="mobil">Mobil</option><option value="motor">Motor</option><option value="suv">SUV</option><option value="truck">Truck</option></select></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Merk</label><input type="text" name="brand" value="{{ old('brand') }}" class="w-full rounded-lg border-gray-300 text-sm" placeholder="Toyota"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Model</label><input type="text" name="vehicle_model" value="{{ old('vehicle_model') }}" class="w-full rounded-lg border-gray-300 text-sm" placeholder="Avanza"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Warna</label><input type="text" name="color" value="{{ old('color') }}" class="w-full rounded-lg border-gray-300 text-sm" placeholder="Hitam"></div>
            </div>
        </div>
        @endif
        <div class="flex items-center space-x-3">
            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700">Simpan</button>
            <a href="{{ route('admin.customers.index') }}" class="px-6 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200">Batal</a>
        </div>
    </form>
</div>
@endsection
