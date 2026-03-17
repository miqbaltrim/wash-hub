@extends('layouts.app')
@section('title', isset($serviceCategory) ? 'Edit Kategori' : 'Tambah Kategori')
@section('header')
    <h2 class="font-semibold text-xl text-gray-800">{{ isset($serviceCategory) ? 'Edit Kategori' : 'Tambah Kategori' }}</h2>
@endsection
@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ isset($serviceCategory) ? route('admin.service-categories.update', $serviceCategory) : route('admin.service-categories.store') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
        @csrf
        @if(isset($serviceCategory)) @method('PUT') @endif
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori *</label>
            <input type="text" name="name" required value="{{ old('name', $serviceCategory->name ?? '') }}" class="w-full rounded-lg border-gray-300 text-sm">
            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
            <textarea name="description" rows="3" class="w-full rounded-lg border-gray-300 text-sm">{{ old('description', $serviceCategory->description ?? '') }}</textarea>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Icon (class)</label>
                <input type="text" name="icon" value="{{ old('icon', $serviceCategory->icon ?? '') }}" placeholder="bi-droplet" class="w-full rounded-lg border-gray-300 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $serviceCategory->sort_order ?? 0) }}" class="w-full rounded-lg border-gray-300 text-sm">
            </div>
        </div>
        <div>
            <label class="flex items-center">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $serviceCategory->is_active ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 mr-2">
                <span class="text-sm text-gray-700">Aktif</span>
            </label>
        </div>
        <div class="flex items-center space-x-3 pt-2">
            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700">Simpan</button>
            <a href="{{ route('admin.service-categories.index') }}" class="px-6 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200">Batal</a>
        </div>
    </form>
</div>
@endsection
