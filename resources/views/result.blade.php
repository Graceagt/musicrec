<!DOCTYPE html>
<html>
<head>
    <title>🎧 Hasil Rekomendasi Musik</title>
    <link href="{{ asset('css/result.css') }}" rel="stylesheet">
</head>
<body>
    <h1>🎧 Hasil Rekomendasi Musik</h1>

    @foreach($topSongs as $mood => $songs)
        <div class="mood-section mood-{{ strtolower($mood) }}">
            <h2>{{ ucfirst($mood) }}</h2>
            <div class="d-flex flex-wrap justify-content-center">
                @foreach($songs as $song)
                    <div class="card">
                        <img class="artwork" src="{{ str_replace('100x100bb', '600x600bb', $song['artwork']) ?? asset('images/default-artwork.png') }}" alt="Artwork">
                        <div class="track-name">{{ $song['track_name'] ?? 'Unknown' }}</div>
                        <div class="artist-name">{{ $song['artist_name'] ?? 'Unknown' }}</div>
                        <div class="mood-score">
                            {{-- Relevansi: {{ $song['score'] ?? '-' }}<br> --}}
                            Genre: {{ isset($song['genres']) ? implode(', ', $song['genres']) : '-' }}
                        </div>

                        @if(!empty($song['preview_url']))
                            <audio controls preload="none">
                                <source src="{{ $song['preview_url'] }}" type="audio/mpeg">
                                Browser Anda tidak mendukung audio.
                            </audio>
                        @else
                            <span style="color:#ccc; font-size:12px;">No Preview</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    <a href="{{ route('music.index') }}" class="btn-back">⬅ Kembali</a>
</body>
</html>