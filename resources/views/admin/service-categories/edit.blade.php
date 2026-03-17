@extends('layouts.app')
@section('title', isset($serviceCategory) ? 'Edit Kategori' : 'Tambah Kategori')
@section('header')<h2>{{ isset($serviceCategory) ? 'Edit Kategori' : 'Tambah Kategori' }}</h2>@endsection
@section('actions')<a href="{{ route('admin.service-categories.index') }}" class="btn-outline btn-sm">← Kembali</a>@endsection
@section('content')
<div style="max-width:540px">
    <form method="POST" action="{{ isset($serviceCategory) ? route('admin.service-categories.update',$serviceCategory) : route('admin.service-categories.store') }}" class="card" style="padding:1.5rem;display:flex;flex-direction:column;gap:1rem">
        @csrf @if(isset($serviceCategory)) @method('PUT') @endif
        <div><label class="form-label">Nama Kategori *</label><input type="text" name="name" required value="{{ old('name',$serviceCategory->name??'') }}" class="form-input">@error('name')<p class="form-error">{{ $message }}</p>@enderror</div>
        <div><label class="form-label">Deskripsi</label><textarea name="description" rows="3" class="form-input">{{ old('description',$serviceCategory->description??'') }}</textarea></div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem">
            <div><label class="form-label">Icon (class)</label><input type="text" name="icon" value="{{ old('icon',$serviceCategory->icon??'') }}" placeholder="bi-droplet" class="form-input"></div>
            <div><label class="form-label">Urutan</label><input type="number" name="sort_order" value="{{ old('sort_order',$serviceCategory->sort_order??0) }}" class="form-input"></div>
        </div>
        <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer"><input type="checkbox" name="is_active" value="1" {{ old('is_active',$serviceCategory->is_active??true)?'checked':'' }} style="accent-color:var(--gold);width:16px;height:16px"><span style="font-size:.85rem;font-weight:500">Aktif</span></label>
        <div style="display:flex;gap:.5rem;padding-top:.5rem"><button type="submit" class="btn-gold">Simpan</button><a href="{{ route('admin.service-categories.index') }}" class="btn-outline">Batal</a></div>
    </form>
</div>
@endsection
