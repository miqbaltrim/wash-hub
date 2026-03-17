@extends('layouts.app')
@section('title', isset($customer) ? 'Edit Customer' : 'Tambah Customer')
@section('header')<h2>{{ isset($customer) ? 'Edit Customer' : 'Tambah Customer' }}</h2>@endsection
@section('actions')<a href="{{ route('admin.customers.index') }}" class="btn-outline btn-sm">← Kembali</a>@endsection
@section('content')
<div style="max-width:720px">
    <form method="POST" action="{{ isset($customer) ? route('admin.customers.update',$customer) : route('admin.customers.store') }}" style="display:flex;flex-direction:column;gap:1rem">
        @csrf @if(isset($customer)) @method('PUT') @endif
        <div class="card" style="padding:1.5rem">
            <h3 style="font-size:.9rem;font-weight:700;margin-bottom:1rem">Data Pribadi</h3>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem">
                <div><label class="form-label">Nama *</label><input type="text" name="name" required value="{{ old('name',$customer->user->name??'') }}" class="form-input">@error('name')<p class="form-error">{{ $message }}</p>@enderror</div>
                <div><label class="form-label">No HP *</label><input type="text" name="phone" required value="{{ old('phone',$customer->phone??'') }}" class="form-input">@error('phone')<p class="form-error">{{ $message }}</p>@enderror</div>
                <div><label class="form-label">Email</label><input type="email" name="email" value="{{ old('email',$customer->user->email??'') }}" class="form-input">@error('email')<p class="form-error">{{ $message }}</p>@enderror</div>
                <div><label class="form-label">Jenis Kelamin</label><select name="gender" class="form-input form-select"><option value="">-</option><option value="male" {{ old('gender',$customer->gender??'')==='male'?'selected':'' }}>Laki-laki</option><option value="female" {{ old('gender',$customer->gender??'')==='female'?'selected':'' }}>Perempuan</option></select></div>
                <div><label class="form-label">Tanggal Lahir</label><input type="date" name="birth_date" value="{{ old('birth_date',isset($customer)?$customer->birth_date?->format('Y-m-d'):'') }}" class="form-input"></div>
                <div style="grid-column:1/-1"><label class="form-label">Alamat</label><textarea name="address" rows="2" class="form-input">{{ old('address',$customer->address??'') }}</textarea></div>
            </div>
        </div>
        @if(!isset($customer))
        <div class="card" style="padding:1.5rem">
            <h3 style="font-size:.9rem;font-weight:700;margin-bottom:1rem">Kendaraan (Opsional)</h3>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:.75rem">
                <div><label class="form-label">Plat Nomor</label><input type="text" name="plate_number" value="{{ old('plate_number') }}" class="form-input" style="text-transform:uppercase" placeholder="B 1234 XX"></div>
                <div><label class="form-label">Tipe</label><select name="vehicle_type" class="form-input form-select"><option value="mobil">Mobil</option><option value="motor">Motor</option><option value="suv">SUV</option><option value="truck">Truck</option></select></div>
                <div><label class="form-label">Merk</label><input type="text" name="brand" value="{{ old('brand') }}" class="form-input" placeholder="Toyota"></div>
                <div><label class="form-label">Model</label><input type="text" name="vehicle_model" value="{{ old('vehicle_model') }}" class="form-input" placeholder="Avanza"></div>
                <div><label class="form-label">Warna</label><input type="text" name="color" value="{{ old('color') }}" class="form-input" placeholder="Hitam"></div>
            </div>
        </div>
        @endif
        <div style="display:flex;gap:.5rem"><button type="submit" class="btn-gold">Simpan</button><a href="{{ route('admin.customers.index') }}" class="btn-outline">Batal</a></div>
    </form>
</div>
@endsection
