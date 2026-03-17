<x-guest-layout>
<div class="form-header"><h2>Daftar Member</h2><p>Buat akun dan mulai kumpulkan reward</p></div>
<form method="POST" action="{{ route('register') }}">@csrf
<div class="form-group"><label class="form-label">Nama Lengkap</label><input class="form-input {{ $errors->has('name')?'error':'' }}" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="Masukkan nama lengkap">@error('name')<p class="form-error">{{ $message }}</p>@enderror</div>
<div class="form-row"><div class="form-group"><label class="form-label">Email</label><input class="form-input" type="email" name="email" value="{{ old('email') }}" required placeholder="email@contoh.com">@error('email')<p class="form-error">{{ $message }}</p>@enderror</div>
<div class="form-group"><label class="form-label">Nomor HP</label><input class="form-input" type="text" name="phone" value="{{ old('phone') }}" required placeholder="08xxxxxxxxxx">@error('phone')<p class="form-error">{{ $message }}</p>@enderror</div></div>
<div class="form-row"><div class="form-group"><label class="form-label">Password</label><input class="form-input" type="password" name="password" required placeholder="Min. 8 karakter">@error('password')<p class="form-error">{{ $message }}</p>@enderror</div>
<div class="form-group"><label class="form-label">Konfirmasi</label><input class="form-input" type="password" name="password_confirmation" required placeholder="Ulangi password"></div></div>
<button type="submit" class="btn-primary" style="margin-top:.5rem">Daftar Sekarang</button>
</form>
<div class="promo-banner"><p>🎉 Cuci 10x dapat 1x cuci <strong>GRATIS</strong> — Berlaku semua layanan!</p></div>
<div class="form-footer">Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></div>
</x-guest-layout>
