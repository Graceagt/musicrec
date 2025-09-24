@extends('layouts.app')

@section('content')
<div class="card p-4">
    <h2 class="text-center mb-4">🎶 Sistem Pakar Rekomendasi Musik</h2>
    <form method="POST" action="/music-expert/recommend">
        @csrf
        @foreach($questions as $key => $label)
            <div class="mb-4">
                <label class="form-label">{{ $label }}</label>
                <div class="input-group">
                    <button type="button" class="btn btn-outline-warning"
                            onclick="stepDown('{{ $key }}')">−</button>
                    
                    <input type="number" class="form-control text-center" 
                           id="{{ $key }}" name="{{ $key }}" 
                           value="0" min="0" max="1" step="0.1" readonly>
                    
                    <button type="button" class="btn btn-outline-warning"
                            onclick="stepUp('{{ $key }}')">+</button>
                </div>
            </div>
        @endforeach

        <div class="text-center">
            <button type="submit" class="btn btn-custom mt-3 px-4 py-2">Dapatkan Rekomendasi</button>
        </div>
    </form>
</div>

<script>
    // fungsi tambah/kurang
    function stepUp(id) {
        let input = document.getElementById(id);
        let val = parseFloat(input.value);
        if (val < 1) {
            input.value = (val + 0.1).toFixed(1);
        }
    }
    function stepDown(id) {
        let input = document.getElementById(id);
        let val = parseFloat(input.value);
        if (val > 0) {
            input.value = (val - 0.1).toFixed(1);
        }
    }
</script>
@endsection
