@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(-45deg, #74EBD5, #9FACE6, #FF9A9E, #74EBD5);
        background-size: 400% 400%;
        animation: gradientBG 10s ease infinite;
        font-family: 'Poppins', sans-serif;
        overflow-x: hidden;
        position: relative;
        color: #fff;
    }

    @keyframes gradientBG {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    /* 🌌 Floating bubbles */
    .bubble {
        position: absolute;
        border-radius: 50%;
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(5px);
        animation: floatUp 15s linear infinite;
    }

    @keyframes floatUp {
        from { transform: translateY(100vh) scale(0.8); opacity: 0; }
        50% { opacity: 0.6; }
        to { transform: translateY(-10vh) scale(1.2); opacity: 0; }
    }

    /* 🎶 Music equalizer bars */
    .music-bars {
        display: flex;
        justify-content: center;
        align-items: flex-end;
        gap: 6px;
        height: 30px;
        margin-bottom: 20px;
    }

    .bar {
        width: 6px;
        background: #FFD700;
        border-radius: 3px;
        animation: bounce 1s infinite;
    }

    .bar:nth-child(2) { animation-delay: 0.2s; }
    .bar:nth-child(3) { animation-delay: 0.4s; }
    .bar:nth-child(4) { animation-delay: 0.6s; }
    .bar:nth-child(5) { animation-delay: 0.8s; }

    @keyframes bounce {
        0%, 100% { height: 8px; }
        50% { height: 30px; }
    }

    /* ✨ Glow pulse */
    .card {
        background: rgba(255, 255, 255, 0.12) !important;
        backdrop-filter: blur(12px);
        border-radius: 12px;
        animation: pulseGlow 4s ease-in-out infinite;
    }

    @keyframes pulseGlow {
        0%, 100% { box-shadow: 0 0 10px rgba(255,255,255,0.2); }
        50% { box-shadow: 0 0 30px rgba(255,255,255,0.4); }
    }

    .list-group-item {
        background: transparent !important;
        border: none;
        color: #fff;
        font-weight: 500;
    }
</style>

<!-- 🌌 Bubbles background -->
<div class="bubble" style="width:80px; height:80px; left:10%; animation-duration: 18s;"></div>
<div class="bubble" style="width:40px; height:40px; left:30%; animation-duration: 12s;"></div>
<div class="bubble" style="width:60px; height:60px; left:50%; animation-duration: 20s;"></div>
<div class="bubble" style="width:100px; height:100px; left:70%; animation-duration: 25s;"></div>
<div class="bubble" style="width:50px; height:50px; left:85%; animation-duration: 15s;"></div>

<div class="card p-4 text-center text-white position-relative">
    <h2 class="mb-2">🎵 Rekomendasi Genre Musik</h2>
    
    <!-- 🎶 Music equalizer bars -->
    <div class="music-bars">
        <div class="bar"></div>
        <div class="bar"></div>
        <div class="bar"></div>
        <div class="bar"></div>
        <div class="bar"></div>
    </div>

    <ul class="list-group list-group-flush mb-4">
        @foreach($conclusions as $genre => $cf)
            <li class="list-group-item d-flex justify-content-between">
                <span>{{ $genre }}</span>
                <span>CF = {{ number_format($cf, 2) }}</span>
            </li>
        @endforeach
    </ul>

    <h3 class="mb-3">🔥 Top 3 Lagu Genre {{ $topGenre }}</h3>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach($topSongs as $song)
            <div class="col">
                <div class="card h-100 shadow-lg border-0 text-white rounded-3">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div class="mb-3">
                            <h5 class="card-title fw-bold">🎶 {{ $song['song'] }}</h5>
                            <p class="card-text text-light mb-1">👤 {{ $song['artist'] }}</p>
                            <span class="badge bg-warning text-dark shadow-sm">{{ $topGenre }}</span>

                            @if(!empty($song['preview_url']))
                                <audio controls class="w-100 mt-3">
                                    <source src="{{ $song['preview_url'] }}" type="audio/mp4">
                                    Browser kamu tidak mendukung audio.
                                </audio>
                            @else
                                <small class="text-muted d-block mt-2">⚠️ Preview tidak tersedia</small>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
