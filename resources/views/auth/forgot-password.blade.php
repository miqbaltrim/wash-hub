<x-guest-layout>
    <div class="form-header">
        <h2>Lupa Password?</h2>
        <p>Masukkan email Anda dan kami akan kirimkan link untuk reset password.</p>
    </div>

    @if (session('status'))
        <div class="session-status">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

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
                placeholder="nama@email.com"
            >
            @error('email')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn-primary">Kirim Link Reset</button>
    </form>

    <div class="form-footer">
        Ingat password? <a href="{{ route('login') }}">Kembali ke Login</a>
    </div>
</x-guest-layout>
