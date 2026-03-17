<x-guest-layout>
    <div class="form-header">
        <h2>Daftar Member</h2>
        <p>Buat akun dan mulai kumpulkan reward</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="form-group">
            <label class="form-label" for="name">Nama Lengkap</label>
            <input 
                id="name" 
                class="form-input {{ $errors->has('name') ? 'error' : '' }}" 
                type="text" 
                name="name" 
                value="{{ old('name') }}" 
                required 
                autofocus 
                autocomplete="name" 
                placeholder="Masukkan nama lengkap"
            >
            @error('name')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email & Phone -->
        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input 
                    id="email" 
                    class="form-input {{ $errors->has('email') ? 'error' : '' }}" 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    autocomplete="username" 
                    placeholder="nama@email.com"
                >
                @error('email')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="phone">Nomor HP</label>
                <input 
                    id="phone" 
                    class="form-input {{ $errors->has('phone') ? 'error' : '' }}" 
                    type="text" 
                    name="phone" 
                    value="{{ old('phone') }}" 
                    required 
                    placeholder="08xxxxxxxxxx"
                >
                @error('phone')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Password & Confirm -->
        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input 
                    id="password" 
                    class="form-input {{ $errors->has('password') ? 'error' : '' }}" 
                    type="password" 
                    name="password" 
                    required 
                    autocomplete="new-password" 
                    placeholder="Min. 8 karakter"
                >
                @error('password')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="password_confirmation">Konfirmasi</label>
                <input 
                    id="password_confirmation" 
                    class="form-input {{ $errors->has('password_confirmation') ? 'error' : '' }}" 
                    type="password" 
                    name="password_confirmation" 
                    required 
                    autocomplete="new-password" 
                    placeholder="Ulangi password"
                >
                @error('password_confirmation')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Submit -->
        <button type="submit" class="btn-primary" style="margin-top: 0.5rem;">Daftar Sekarang</button>
    </form>

    <div class="promo-banner">
        <p>🎉 Cuci 10x dapat 1x cuci <strong>GRATIS</strong> — Berlaku untuk semua layanan!</p>
    </div>

    <div class="form-footer">
        Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
    </div>

    <div class="pricelist-link">
        <a href="{{ url('/pricelist') }}">Lihat Daftar Harga Layanan &rarr;</a>
    </div>
</x-guest-layout>
