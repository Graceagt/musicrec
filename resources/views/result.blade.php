{{-- resources/views/result.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>🎧 Hasil Rekomendasi Musik</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 20px;
            text-align: center;
        }
        h1, h2 {
            color: #333;
        }
        .mood-section {
            margin-bottom: 40px;
        }
        .card {
            display: inline-block;
            background: #fff;
            border-radius: 12px;
            margin: 10px;
            padding: 15px;
            width: 200px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            vertical-align: top;
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .artwork {
            width: 100%;
            border-radius: 8px;
        }
        .track-name {
            font-weight: bold;
            margin: 10px 0 5px;
        }
        .artist-name {
            color: #555;
            margin-bottom: 10px;
        }
        audio {
            width: 100%;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <h1>🎧 Hasil Rekomendasi Musik</h1>

    @foreach($topSongs as $mood => $songs)
        <div class="mood-section">
            <h2>{{ ucfirst($mood) }}</h2>
            @foreach($songs as $song)
                <div class="card">
                    <img class="artwork" src="{{ $song['artwork'] ?? asset('images/default-artwork.png') }}" alt="Artwork">
                    <div class="track-name">{{ $song['track_name'] }}</div>
                    <div class="artist-name">{{ $song['artist_name'] }}</div>
                    @if($song['preview_url'])
                        <audio controls preload="none">
                            <source src="{{ $song['preview_url'] }}" type="audio/mpeg">
                            Browser Anda tidak mendukung audio.
                        </audio>
                    @else
                        <span style="color:#999; font-size:12px;">No Preview</span>
                    @endif
                </div>
            @endforeach
        </div>
    @endforeach

    <a href="{{ route('music.index') }}" style="display:inline-block; margin-top:20px; text-decoration:none; color:#1db954; font-weight:bold;">⬅️ Kembali</a>
</body>
</html>
