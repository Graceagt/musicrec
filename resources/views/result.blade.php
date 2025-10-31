{{-- resources/views/result.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>🎧 Hasil Rekomendasi Musik</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            margin: 0;
            padding: 40px 20px;
            text-align: center;
            color: #fff;
            background: linear-gradient(270deg, #ff69b4, #667eea, #6a0dad);
            background-size: 600% 600%;
            animation: gradientMove 15s ease infinite;
        }

        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        h1 {
            font-weight: 700;
            margin-bottom: 30px;
            text-shadow: 0 0 20px rgba(255,255,255,0.4);
            animation: titleEntrance 1.5s ease-out, titleGlow 3s ease-in-out infinite alternate;
            letter-spacing: 1px;
            position: relative;
            display: inline-block;
        }

        /* ✨ animasi muncul halus + sedikit bounce */
        @keyframes titleEntrance {
            0% {
                opacity: 0;
                transform: translateY(-40px) scale(0.95);
            }
            60% {
                opacity: 1;
                transform: translateY(10px) scale(1.02);
            }
            100% {
                transform: translateY(0) scale(1);
            }
        }

        /* 🌈 efek glow lembut berdenyut */
        @keyframes titleGlow {
            0% {
                text-shadow: 0 0 10px #ffeb3b, 0 0 20px #ffca28, 0 0 40px rgba(255, 202, 40, 0.4);
            }
            50% {
                text-shadow: 0 0 20px #ffd54f, 0 0 40px #ffe082, 0 0 60px rgba(255, 255, 200, 0.6);
            }
            100% {
                text-shadow: 0 0 10px #ffeb3b, 0 0 25px #ffca28, 0 0 40px rgba(255, 202, 40, 0.4);
            }
        }


        h2 {
            font-weight: 700;
            text-transform: uppercase;
            margin: 40px auto 25px;
            display: inline-block;
            padding: 10px 30px;
            border-radius: 30px;
        }

        /* Warna per mood */
        .mood-happy h2 { background: linear-gradient(90deg, #fff176, #ffd54f); color: #222; animation: pulse 1.5s infinite; }
        .mood-sad h2 { background: linear-gradient(90deg, #90caf9, #64b5f6); color: #fff; animation: fadeMood 3s ease-in-out infinite; }
        .mood-energetic h2 { background: linear-gradient(90deg, #ff8a65, #ffb74d); color: #222; animation: flash 1s infinite; }
        .mood-calm h2 { background: linear-gradient(90deg, #a5f2f3, #80deea); color: #003d5b; animation: wave 4s ease-in-out infinite; }
        .mood-romantic h2 { background: linear-gradient(90deg, #f48fb1, #ce93d8); color: #fff; animation: heartbeat 2s infinite; }
        .mood-focus h2 { background: linear-gradient(90deg, #b2f7ef, #80cbc4); color: #004d40; animation: fadeZoom 3s infinite; }

        /* Animasi mood */
        @keyframes pulse { 0%,100%{transform:scale(1);}50%{transform:scale(1.1);} }
        @keyframes fadeMood { 0%,100%{opacity:0.8;}50%{opacity:1;} }
        @keyframes flash { 0%,100%{filter:brightness(1);}50%{filter:brightness(1.3);} }
        @keyframes wave { 0%,100%{transform:translateY(0);}50%{transform:translateY(6px);} }
        @keyframes heartbeat { 0%,100%{transform:scale(1);}25%{transform:scale(1.1);}75%{transform:scale(1.05);} }
        @keyframes fadeZoom { 0%,100%{transform:scale(1);opacity:0.9;}50%{transform:scale(1.05);opacity:1;} }

        .mood-section { margin-bottom: 50px; }

        .card {
            display: inline-block;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 15px;
            margin: 15px;
            padding: 15px;
            width: 230px;
            backdrop-filter: blur(12px);
            color: #000;
            transition: transform 0.3s, background 0.3s;
            box-shadow: 0 4px 12px rgba(0,0,0,0.25);
        }

        .card:hover {
            transform: translateY(-8px);
            background: rgba(255, 255, 255, 0.3);
        }

        .artwork {
            width: 100%;
            border-radius: 10px;
            margin-bottom: 10px;
            transition: transform 0.3s;
        }

        .card:hover .artwork { transform: scale(1.05); }

        .track-name { font-weight: 700; font-size: 16px; color: #111; margin-bottom: 5px; }
        .artist-name { color: #333; font-size: 14px; margin-bottom: 10px; }

        .play-btn {
            background: #ffca28;
            border: none;
            color: #000;
            font-weight: bold;
            border-radius: 25px;
            padding: 8px 18px;
            transition: all 0.3s;
            cursor: pointer;
        }

        .play-btn:hover { background: #ffc107; transform: scale(1.1); }
        .playing { box-shadow: 0 0 20px #ffca28; }

        .timeline {
            width: 100%;
            height: 6px;
            border-radius: 5px;
            background: rgba(0,0,0,0.15);
            cursor: pointer;
            appearance: none;
            margin-top: 8px;
            margin-bottom: 2px;
        }

        .timeline::-webkit-slider-thumb {
            appearance: none;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #ffca28;
            cursor: pointer;
        }

        .time-display {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: #222;
        }

        .btn-back {
            background-color: #ffca28;
            color: #000;
            font-weight: bold;
            border-radius: 10px;
            padding: 10px 20px;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-back:hover { background-color: #ffc107; transform: translateY(-3px); }
    </style>
</head>
<body>
    <h1>🎧 Hasil Rekomendasi Musik</h1>

    @foreach($topSongs as $mood => $songs)
        <div class="mood-section mood-{{ strtolower($mood) }}">
            <h2>{{ ucfirst($mood) }}</h2>
            <div class="d-flex flex-wrap justify-content-center">
                @foreach($songs as $song)
                    <div class="card">
                        <img class="artwork" src="{{ $song['artwork'] ?? asset('images/default-artwork.png') }}" alt="Artwork">
                        <div class="track-name">{{ $song['track_name'] }}</div>
                        <div class="artist-name">{{ $song['artist_name'] }}</div>

                        @if($song['preview_url'])
                            <button class="play-btn" onclick="playPreview('{{ $song['preview_url'] }}', this)">▶️ Play</button>

                            <!-- 🎵 Custom Timeline -->
                            <input type="range" class="timeline" min="0" value="0" step="0.1" oninput="seekAudio(this)">
                            <div class="time-display">
                                <span class="current-time">0:00</span>
                                <span class="total-time">0:00</span>
                            </div>

                            <audio preload="none">
                                <source src="{{ $song['preview_url'] }}" type="audio/mpeg">
                            </audio>
                        @else
                            <span style="color:#666; font-size:12px;">No Preview</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    <a href="{{ route('music.index') }}" class="btn-back">⬅️ Kembali</a>

    <script>
        let currentAudio = null;
        let currentButton = null;
        let currentTimeline = null;
        let currentTimeDisplay = null;
        let currentTotalTime = null;
        let updateInterval = null;

        function playPreview(url, btn) {
            if (currentAudio) {
                currentAudio.pause();
                currentAudio.currentTime = 0;
                if (currentButton) {
                    currentButton.textContent = '▶️ Play';
                    currentButton.closest('.card').classList.remove('playing');
                }
                if (updateInterval) clearInterval(updateInterval);
            }

            const card = btn.closest('.card');
            const audio = card.querySelector('audio');
            const timeline = card.querySelector('.timeline');
            const currentTimeSpan = card.querySelector('.current-time');
            const totalTimeSpan = card.querySelector('.total-time');

            if (audio.src !== url) {
                audio.querySelector('source').src = url;
                audio.load();
            }

            if (audio.paused) {
                audio.play();
                btn.textContent = '⏸️ Pause';
                card.classList.add('playing');
                currentAudio = audio;
                currentButton = btn;
                currentTimeline = timeline;
                currentTimeDisplay = currentTimeSpan;
                currentTotalTime = totalTimeSpan;

                audio.onloadedmetadata = () => {
                    timeline.max = audio.duration;
                    currentTotalTime.textContent = formatTime(audio.duration);
                };

                updateInterval = setInterval(() => {
                    if (!audio.paused) {
                        timeline.value = audio.currentTime;
                        currentTimeSpan.textContent = formatTime(audio.currentTime);
                    }
                }, 200);

                audio.onended = () => {
                    btn.textContent = '▶️ Play';
                    card.classList.remove('playing');
                    clearInterval(updateInterval);
                    timeline.value = 0;
                    currentTimeSpan.textContent = "0:00";
                };
            } else {
                audio.pause();
                btn.textContent = '▶️ Play';
                card.classList.remove('playing');
                clearInterval(updateInterval);
            }
        }

        function seekAudio(rangeInput) {
            if (currentAudio && currentTimeline === rangeInput) {
                currentAudio.currentTime = rangeInput.value;
            }
        }

        function formatTime(sec) {
            const minutes = Math.floor(sec / 60);
            const seconds = Math.floor(sec % 60);
            return `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
        }
    </script>
</body>
</html>
