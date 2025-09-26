@extends('layouts.app')

@section('content')
<div class="card p-4 text-center">
    <h2 class="mb-4">🎵 Rekomendasi Genre Musik</h2>
    <ul class="list-group list-group-flush mb-4">
        @foreach($conclusions as $genre => $cf)
            <li class="list-group-item bg-transparent text-white d-flex justify-content-between">
                <span>{{ $genre }}</span>
                <span>CF = {{ number_format($cf, 2) }}</span>
            </li>
        @endforeach
    </ul>

    <h3 class="mb-3">🔥 Top 3 Lagu Genre {{ $topGenre }}</h3>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach($topSongs as $song)
            <div class="col">
                <div class="card h-100 shadow-lg border-0 text-white rounded-3" 
                    style="background: rgba(255, 255, 255, 0.12); backdrop-filter: blur(12px);">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div class="mb-3">
                            <h5 class="card-title fw-bold">
                                🎶 {{ $song['song'] }}
                            </h5>
                            <p class="card-text text-light mb-1">👤 {{ $song['artist'] }}</p>
                            <span class="badge bg-warning text-dark shadow-sm">
                                {{ $topGenre }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>



@endsection
