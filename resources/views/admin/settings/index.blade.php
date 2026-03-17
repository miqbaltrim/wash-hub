@extends('layouts.app')
@section('title', 'Pengaturan')
@section('header')<h2>Pengaturan Aplikasi</h2>@endsection
@section('content')
<form method="POST" action="{{ route('admin.settings.update') }}" style="display:flex;flex-direction:column;gap:1rem;max-width:720px">@csrf
    @foreach($settings as $group => $items)
    <div class="card" style="padding:1.5rem">
        <h3 style="font-size:.9rem;font-weight:700;margin-bottom:1rem;text-transform:capitalize">{{ $group }}</h3>
        <div style="display:flex;flex-direction:column;gap:1rem">
            @foreach($items as $s)
            <div style="display:grid;grid-template-columns:1fr 2fr;gap:1rem;align-items:start">
                <div><label class="form-label" style="margin:0">{{ $s->description ?? $s->key }}</label><p style="font-size:.68rem;color:var(--stone-300);margin:.1rem 0 0;font-family:'Space Mono',monospace">{{ $s->key }}</p></div>
                @if($s->type==='textarea')<textarea name="settings[{{ $s->key }}]" rows="2" class="form-input">{{ $s->value }}</textarea>
                @elseif($s->type==='boolean')<select name="settings[{{ $s->key }}]" class="form-input form-select"><option value="1" {{ $s->value=='1'?'selected':'' }}>Ya</option><option value="0" {{ $s->value=='0'?'selected':'' }}>Tidak</option></select>
                @elseif($s->type==='number')<input type="number" name="settings[{{ $s->key }}]" value="{{ $s->value }}" class="form-input">
                @else<input type="text" name="settings[{{ $s->key }}]" value="{{ $s->value }}" class="form-input">@endif
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
    <div><button type="submit" class="btn-gold">Simpan Pengaturan</button></div>
</form>
@endsection
