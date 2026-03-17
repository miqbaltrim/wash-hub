<x-guest-layout>
    <div class="form-header">
        <h2>Selamat datang kembali</h2>
        <p>Masuk ke akun Wash Hub Anda</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="session-status">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div class="form-group">
            <label class="form-label" for="email">Email</label>
            <input 
                id="email" 
                class="form-input {{ $errors->has('email') ? 'error' : '' }}" 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                required 
                autofocus 
                autocomplete="username" 
                placeholder="nama@email.com"
            >
            @error('email')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <label class="form-label" for="password" style="margin-bottom: 0;">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="form-link" style="font-size: 0.78rem;">Lupa password?</a>
                @endif
            </div>
            <input 
                id="password" 
                class="form-input {{ $errors->has('password') ? 'error' : '' }}" 
                type="password" 
                name="password" 
                required 
                autocomplete="current-password" 
                placeholder="Masukkan password"
                style="margin-top: 0.5rem;"
            >
            @error('password')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="form-checkbox">
            <input id="remember_me" type="checkbox" name="remember">
            <label for="remember_me">Ingat saya di perangkat ini</label>
        </div>

        <!-- Submit -->
        <button type="submit" class="btn-primary">Masuk</button>
    </form>

    <div class="form-footer">
        Belum punya akun? <a href="{{ route('register') }}">Daftar Member Gratis</a>
    </div>

    <div class="pricelist-link">
        <a href="{{ url('/pricelist') }}">Lihat Daftar Harga Layanan &rarr;</a>
    </div>
</x-guest-layout>
