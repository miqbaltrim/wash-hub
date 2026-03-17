<x-guest-layout>
<div class="form-header"><h2>Selamat datang kembali</h2><p>Masuk ke akun Wash Hub Anda</p></div>
@if(session('status'))<div class="session-status">{{ session('status') }}</div>@endif
<form method="POST" action="{{ route('login') }}">@csrf
<div class="form-group"><label class="form-label">Email</label><input class="form-input {{ $errors->has('email')?'error':'' }}" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@email.com">@error('email')<p class="form-error">{{ $message }}</p>@enderror</div>
<div class="form-group"><div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.4rem"><label class="form-label" style="margin:0">Password</label>@if(Route::has('password.request'))<a href="{{ route('password.request') }}" class="form-link">Lupa password?</a>@endif</div><input class="form-input" type="password" name="password" required placeholder="Masukkan password"></div>
<div class="form-checkbox"><input id="rem" type="checkbox" name="remember"><label for="rem">Ingat saya</label></div>
<button type="submit" class="btn-primary">Masuk</button>
</form>
<div class="form-footer">Belum punya akun? <a href="{{ route('register') }}">Daftar Member Gratis</a></div>
<div class="pricelist-link"><a href="{{ url('/pricelist') }}">Lihat Daftar Harga →</a></div>
</x-guest-layout>
