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
    <ul class="list-group">
        @foreach($topSongs as $song)
            <li class="list-group-item d-flex justify-content-between">
                <span>{{ $song['song'] }} - {{ $song['artist'] }}</span>
            </li>
        @endforeach
    </ul>

    <a href="/music-expert" class="btn btn-custom mt-4">🔙 Kembali</a>
</div>
@endsection
