<!DOCTYPE html>
<html>
<head>
    <title>🎧 Hasil Rekomendasi Musik</title>
    <link href="{{ asset('css/result.css') }}" rel="stylesheet">
</head>

<body>
    <h1>🎧 Hasil Rekomendasi Musik</h1>

    {{-- ============================= --}}
    {{-- 🔵 Rangkuman Nilai CF --}}
    {{-- ============================= --}}
    <div class="cf-summary" style="margin-bottom: 30px; text-align:center;">
        <h2>📊 Rangkuman Certainty Factor</h2>

        {{-- List seluruh nilai CF --}}
        <ul style="list-style:none; padding:0;">
            @foreach($derivedFacts as $mood => $score)
                <li style="font-size:16px;">
                    <strong>{{ ucfirst($mood) }}:</strong> {{ $score }}
                </li>
            @endforeach
        </ul>

        {{-- Hitung mood utama & total CF --}}
        @php
            $topMood   = array_key_first($derivedFacts);
            $topScore  = $derivedFacts[$topMood];
            $totalCF   = array_sum($derivedFacts);
        @endphp

        <p style="margin-top:10px; font-size:18px;">
            ⭐ <strong>Mood Dominan:</strong> {{ ucfirst($topMood) }} ({{ $topScore }})
        </p>

        <p style="font-size:18px;">
            📌 <strong>Total CF:</strong> {{ $totalCF }}
        </p>
    </div>


    {{-- ============================= --}}
    {{-- 🔵 Rekomendasi Lagu --}}
    {{-- ============================= --}}
    @foreach($topSongs as $mood => $songs)
        <div class="mood-section mood-{{ strtolower($mood) }}">
            <h2>{{ ucfirst($mood) }}</h2>

            <div class="d-flex flex-wrap justify-content-center">

                @foreach($songs as $song)
                    <div class="card">
                        {{-- Artwork --}}
                        <img class="artwork"
                             src="{{ str_replace('100x100bb', '600x600bb', $song['artwork']) ?? asset('images/default-artwork.png') }}"
                             alt="Artwork">

                        {{-- Nama Lagu --}}
                        <div class="track-name">
                            {{ $song['track_name'] ?? 'Unknown' }}
                        </div>

                        {{-- Nama Artis --}}
                        <div class="artist-name">
                            {{ $song['artist_name'] ?? 'Unknown Artist' }}
                        </div>

                        {{-- Genre --}}
                        <div class="mood-score">
                            Genre: {{ isset($song['genres']) ? implode(', ', $song['genres']) : '-' }}
                        </div>

                        {{-- Preview Audio --}}
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
