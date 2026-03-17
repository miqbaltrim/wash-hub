@extends('layouts.app')
@section('title', 'Pengaturan')
@section('header')<h2 class="font-semibold text-xl text-gray-800">Pengaturan Aplikasi</h2>@endsection
@section('content')
<form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
    @csrf
    @foreach($settings as $group => $items)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="font-semibold text-gray-800 mb-4 capitalize">{{ $group }}</h3>
        <div class="space-y-4">
            @foreach($items as $setting)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-2 items-start">
                <div><label class="block text-sm font-medium text-gray-700">{{ $setting->description ?? $setting->key }}</label><p class="text-xs text-gray-400">{{ $setting->key }}</p></div>
                <div class="md:col-span-2">
                    @if($setting->type === 'textarea')
                        <textarea name="settings[{{ $setting->key }}]" rows="2" class="w-full rounded-lg border-gray-300 text-sm">{{ $setting->value }}</textarea>
                    @elseif($setting->type === 'boolean')
                        <select name="settings[{{ $setting->key }}]" class="w-full rounded-lg border-gray-300 text-sm"><option value="1" {{ $setting->value == '1' ? 'selected' : '' }}>Ya</option><option value="0" {{ $setting->value == '0' ? 'selected' : '' }}>Tidak</option></select>
                    @elseif($setting->type === 'number')
                        <input type="number" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}" class="w-full rounded-lg border-gray-300 text-sm">
                    @else
                        <input type="text" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}" class="w-full rounded-lg border-gray-300 text-sm">
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700">Simpan Pengaturan</button>
</form>
@endsection
