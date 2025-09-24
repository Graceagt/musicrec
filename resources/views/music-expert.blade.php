@extends('layouts.app')

@section('content')
<div class="card p-4">
    <h2 class="text-center mb-4">🎶 Sistem Pakar Rekomendasi Musik</h2>
    <form method="POST" action="/music-expert/recommend">
        @csrf
        @foreach($questions as $key => $label)
            <div class="mb-4">
                <label class="form-label">{{ $label }}</label>
                <div class="d-flex align-items-center">
                    <input type="range" 
                           class="form-range me-3 flex-grow-1 slider-dynamic"
                           name="{{ $key }}" 
                           id="{{ $key }}"
                           min="0" max="1" step="0.1" value="0">
                    <span id="{{ $key }}_val" class="badge bg-warning text-dark">0</span>
                </div>
                <div class="d-flex justify-content-between">
                    <small>0</small>
                    <small>1</small>
                </div>
            </div>
        @endforeach
        <div class="text-center">
            <button type="submit" class="btn btn-custom mt-3 px-4 py-2">Dapatkan Rekomendasi</button>
        </div>
    </form>
</div>

<script>
    // Update nilai angka di samping slider
    document.querySelectorAll('.slider-dynamic').forEach(slider => {
        slider.addEventListener('input', function() {
            document.getElementById(this.id + '_val').innerText = this.value;
        });
    });
</script>
@endsection
