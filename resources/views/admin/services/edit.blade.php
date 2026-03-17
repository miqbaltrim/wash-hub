@extends('layouts.app')
@section('title', isset($service) ? 'Edit Layanan' : 'Tambah Layanan')
@section('header')<h2>{{ isset($service) ? 'Edit Layanan' : 'Tambah Layanan' }}</h2>@endsection
@section('actions')<a href="{{ route('admin.services.index') }}" class="btn-outline btn-sm">← Kembali</a>@endsection
@section('content')
<div style="max-width:640px">
    <form method="POST" action="{{ isset($service) ? route('admin.services.update',$service) : route('admin.services.store') }}" class="card" style="padding:1.5rem;display:flex;flex-direction:column;gap:1rem">
        @csrf @if(isset($service)) @method('PUT') @endif
        <div><label class="form-label">Kategori *</label><select name="service_category_id" required class="form-input form-select">@foreach($categories as $cat)<option value="{{ $cat->id }}" {{ old('service_category_id',$service->service_category_id??'')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>@endforeach</select></div>
        <div><label class="form-label">Nama Layanan *</label><input type="text" name="name" required value="{{ old('name',$service->name??'') }}" class="form-input">@error('name')<p class="form-error">{{ $message }}</p>@enderror</div>
        <div><label class="form-label">Deskripsi</label><textarea name="description" rows="2" class="form-input">{{ old('description',$service->description??'') }}</textarea></div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem">
            <div><label class="form-label">Harga (Rp) *</label><input type="number" name="price" required min="0" value="{{ old('price',$service->price??'') }}" class="form-input"></div>
            <div><label class="form-label">Durasi (menit) *</label><input type="number" name="duration_minutes" required min="1" value="{{ old('duration_minutes',$service->duration_minutes??30) }}" class="form-input"></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:.75rem">
            <div><label class="form-label">Tipe Kendaraan</label><select name="vehicle_type" required class="form-input form-select">@foreach(['all'=>'Semua','motor'=>'Motor','mobil'=>'Mobil','suv'=>'SUV','truck'=>'Truck','bus'=>'Bus'] as $k=>$v)<option value="{{ $k }}" {{ old('vehicle_type',$service->vehicle_type??'all')==$k?'selected':'' }}>{{ $v }}</option>@endforeach</select></div>
            <div><label class="form-label">Poin</label><input type="number" name="points_earned" required min="0" value="{{ old('points_earned',$service->points_earned??1) }}" class="form-input"></div>
            <div><label class="form-label">Urutan</label><input type="number" name="sort_order" value="{{ old('sort_order',$service->sort_order??0) }}" class="form-input"></div>
        </div>
        <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer"><input type="checkbox" name="is_active" value="1" {{ old('is_active',$service->is_active??true)?'checked':'' }} style="accent-color:var(--gold);width:16px;height:16px"><span style="font-size:.85rem;font-weight:500">Aktif</span></label>
        <div style="display:flex;gap:.5rem;padding-top:.5rem"><button type="submit" class="btn-gold">Simpan</button><a href="{{ route('admin.services.index') }}" class="btn-outline">Batal</a></div>
    </form>
</div>
@endsection
