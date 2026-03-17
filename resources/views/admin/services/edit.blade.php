@extends('layouts.app')
@section('title', isset($service) ? 'Edit Layanan' : 'Tambah Layanan')
@section('header')<h2 class="font-semibold text-xl text-gray-800">{{ isset($service) ? 'Edit Layanan' : 'Tambah Layanan' }}</h2>@endsection
@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ isset($service) ? route('admin.services.update', $service) : route('admin.services.store') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
        @csrf
        @if(isset($service)) @method('PUT') @endif
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori *</label>
            <select name="service_category_id" required class="w-full rounded-lg border-gray-300 text-sm">
                @foreach($categories as $cat)<option value="{{ $cat->id }}" {{ old('service_category_id', $service->service_category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>@endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Layanan *</label>
            <input type="text" name="name" required value="{{ old('name', $service->name ?? '') }}" class="w-full rounded-lg border-gray-300 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
            <textarea name="description" rows="2" class="w-full rounded-lg border-gray-300 text-sm">{{ old('description', $service->description ?? '') }}</textarea>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp) *</label>
                <input type="number" name="price" required min="0" value="{{ old('price', $service->price ?? '') }}" class="w-full rounded-lg border-gray-300 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Durasi (menit) *</label>
                <input type="number" name="duration_minutes" required min="1" value="{{ old('duration_minutes', $service->duration_minutes ?? 30) }}" class="w-full rounded-lg border-gray-300 text-sm">
            </div>
        </div>
        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Kendaraan *</label>
                <select name="vehicle_type" required class="w-full rounded-lg border-gray-300 text-sm">
                    @foreach(['all'=>'Semua','motor'=>'Motor','mobil'=>'Mobil','suv'=>'SUV','truck'=>'Truck','bus'=>'Bus'] as $k=>$v)
                    <option value="{{ $k }}" {{ old('vehicle_type', $service->vehicle_type ?? 'all') == $k ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Poin *</label>
                <input type="number" name="points_earned" required min="0" value="{{ old('points_earned', $service->points_earned ?? 1) }}" class="w-full rounded-lg border-gray-300 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $service->sort_order ?? 0) }}" class="w-full rounded-lg border-gray-300 text-sm">
            </div>
        </div>
        <label class="flex items-center"><input type="checkbox" name="is_active" value="1" {{ old('is_active', $service->is_active ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 mr-2"><span class="text-sm text-gray-700">Aktif</span></label>
        <div class="flex items-center space-x-3 pt-2">
            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700">Simpan</button>
            <a href="{{ route('admin.services.index') }}" class="px-6 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200">Batal</a>
        </div>
    </form>
</div>
@endsection
