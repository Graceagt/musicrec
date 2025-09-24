@extends('layouts.app')

@section('content')
<div class="card p-4 text-center">
    <h2 class="mb-4">🎵 Rekomendasi Genre Musik</h2>
    <ul class="list-group list-group-flush">
        @foreach($conclusions as $genre => $cf)
            <li class="list-group-item bg-transparent text-white d-flex justify-content-between">
                <span>{{ $genre }}</span>
                <span>CF = {{ number_format($cf, 2) }}</span>
            </li>
        @endforeach
    </ul>
    <a href="/music-expert" class="btn btn-custom mt-4">🔙 Kembali</a>
</div>
@endsection
