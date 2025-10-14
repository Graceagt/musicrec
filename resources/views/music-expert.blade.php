@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/music-expert.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="music-background">
    <h1>🎧 Pilih Mood dan Tingkat Keyakinan (CF)</h1>

    @if(session('error'))
        <p class="text-danger fw-semibold">{{ session('error') }}</p>
    @endif

    <form action="{{ route('music.recommend') }}" method="POST">
        @csrf
        @foreach($moods as $mood)
            <div class="card glass-card">
                <label for="{{ $mood }}" class="form-label">{{ ucfirst($mood) }}</label>
                <select name="cf[{{ $mood }}]" id="{{ $mood }}" class="form-select" required>
                    <option value="" selected disabled>-- Pilih tingkat mood Anda --</option>
                    @foreach($cfOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        @endforeach

        <button type="submit" class="btn btn-success mt-3">🎵 Rekomendasikan Musik</button>
    </form>
</div>
@endsection
