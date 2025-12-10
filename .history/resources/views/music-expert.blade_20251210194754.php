@extends('layouts.app')

@section('content')
<div class="music-background position-relative d-flex flex-column justify-content-center align-items-center" 
     style="min-height: 100vh; overflow: hidden;">

    <!-- 🌌 Background bintang -->
    <div class="stars"></div>

    <!-- 🎶 Judul -->
    <h1 class="text-center text-white fw-bold display-6 mb-4 animate-title">
        <span class="typing-text">🎧 Pilih Mood Musik</span>
    </h1>

    <!-- 💎 Card Form -->
    <div class="card glass-card text-center p-4 rounded-4 position-relative animate-float"
         style="width: 100%; max-width: 520px; overflow: hidden; color: #fff;">

        <p class="small text-light opacity-75 mb-4 animate-fade-in-delay">
            Pilih mood dan tingkat keyakinan Anda (CF 0.0 - 1.0)
        </p>

        @if(session('error'))
            <p class="text-danger fw-semibold">{{ session('error') }}</p>
        @endif

        <form id="moodForm" action="{{ route('music.recommend') }}" method="POST" class="animate-slide-up">
            @csrf

            <div class="slides-viewport" style="overflow: hidden; width: 100%;">
                <div class="slides-container" style="display:flex; width:100%; transition: transform 0.7s cubic-bezier(0.4, 0, 0.2, 1);">

                    @foreach($moods as $index => $mood)
                        <div class="question-slide {{ $loop->first ? 'active' : '' }}" data-order="{{ $loop->index }}" 
                             style="flex:0 0 100%; min-height: 300px; display:flex; flex-direction:column; justify-content:center; align-items:center; transition: opacity 0.4s ease;">

                            <label for="{{ $mood }}" class="form-label fs-5 mb-4 text-white">
                                {{ ucfirst($mood) }}
                            </label>

                            <div class="cf-options d-flex flex-wrap justify-content-center gap-3 mb-4">
                                @foreach($cfOptions as $value => $label)
                                    <label class="cf-box">
                                        <input type="radio" name="cf[{{ $mood }}]" value="{{ $value }}" required>
                                        <span>{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>

                            <div class="d-flex justify-content-center gap-3 mt-3">
                                <button type="button" id="backBtn_{{ $loop->index }}" 
                                        class="btn btn-back rounded-pill px-4" onclick="prevSlide()">Back</button>

                                @if (!$loop->last)
                                    <button type="button" id="nextBtn_{{ $loop->index }}" 
                                            class="btn btn-next fw-semibold rounded-pill px-4"
                                            onclick="nextSlide()">Next</button>
                                @else
                                    <button type="submit" class="btn btn-light text-black fw-semibold px-4 py-2 rounded-pill glow-btn">
                                        🎵 Rekomendasikan Musik
                                    </button>
                                @endif
                            </div>

                            <p class="mt-4 mb-0 small text-light opacity-75">
                                Pertanyaan {{ $loop->index + 1 }} dari {{ count($moods) }}
                            </p>
                        </div>
                    @endforeach

                </div>
            </div>
        </form>
    </div>
</div>

<!-- ====================== SCRIPT ====================== -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('.slides-container');
    const slides = Array.from(document.querySelectorAll('.question-slide'));
    const totalSlides = slides.length;
    if (!container || totalSlides === 0) return;

    let currentIndex = 0;

    function updateButtonsVisibility() {
        slides.forEach((s, idx) => {
            const backBtn = s.querySelector(`#backBtn_${idx}`);
            const nextBtn = s.querySelector(`#nextBtn_${idx}`);
            if (backBtn) backBtn.style.display = (idx === 0) ? 'none' : 'inline-block';
            if (nextBtn) nextBtn.style.display = (idx === totalSlides - 1) ? 'none' : 'inline-block';
        });
    }

    function updateSlidePosition() {
        container.style.transform = `translateX(-${currentIndex * 100}%)`;
        slides.forEach((slide, idx) => slide.classList.toggle('active', idx === currentIndex));
        updateButtonsVisibility();
    }

    window.nextSlide = function() {
        if (currentIndex < totalSlides - 1) {
            currentIndex++;
            updateSlidePosition();
        }
    };

    window.prevSlide = function() {
        if (currentIndex > 0) {
            currentIndex--;
            updateSlidePosition();
        }
    };

    updateSlidePosition();
});


</script>

<!-- ====================== STYLE ====================== -->
<style>
/* 🌌 Background animasi bintang */
.stars {
  position: absolute;
  width: 200%;
  height: 200%;
  background: radial-gradient(white 1px, transparent 1px);
  background-size: 3px 3px;
  animation: moveStars 100s linear infinite;
  opacity: 0.15;
}
@keyframes moveStars {
  from { transform: translate3d(0,0,0); }
  to { transform: translate3d(-500px, 500px, 0); }
}

/* ✨ Efek typing judul */
.typing-text {
  display: inline-block;
  border-right: 3px solid #fff;
  white-space: nowrap;
  overflow: hidden;
  width: 0;
  animation: typing 4s steps(40, end) forwards, blink 0.7s infinite;
}
@keyframes typing { from { width: 0; } to { width: 100%; } }
@keyframes blink { 50% { border-color: transparent; } }

/* 💎 Glass card */
.glass-card {
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.15);
    box-shadow: 0 8px 32px rgba(0,0,0,0.25);
    backdrop-filter: blur(15px);
    border-radius: 1.5rem;
}
.glass-card::before {
    content: "";
    position: absolute;
    inset: 0;
    padding: 2px;
    border-radius: inherit;
    background: linear-gradient(120deg, #fbbf24, #22d3ee, #a855f7, #fbbf24);
    background-size: 300% 300%;
    animation: borderGlow 8s ease infinite;
    mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    mask-composite: exclude;
}
@keyframes borderGlow {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* Animasi */
.animate-fade-in-delay { opacity: 0; animation: fadeIn 1s 0.3s forwards; }
.animate-slide-up { opacity: 0; animation: slideUp 1s 0.5s forwards; }
.animate-title { animation: fadeIn 1s ease-out; }
.animate-float { animation: floatCard 6s ease-in-out infinite; }

@keyframes fadeIn { from {opacity:0; transform:translateY(10px);} to {opacity:1; transform:translateY(0);} }
@keyframes slideUp { from {opacity:0; transform:translateY(20px);} to {opacity:1; transform:translateY(0);} }
@keyframes floatCard { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-8px)} }

/* Slide aktif */
.question-slide { opacity: 0; }
.question-slide.active { opacity: 1; }

/* Tombol */
.btn-next, .btn-back {
    border: 2px solid #fff;
    transition: 0.3s ease, transform 0.2s ease;
}
.btn-next { background:#fff; color:#000; }
.btn-next:hover { background:#000; color:#fff; transform:scale(1.07); }

.btn-back { background:transparent; color:#fff; }
.btn-back:hover { background:#fff; color:#000; transform:scale(1.07); }

/* Submit glowing */
.glow-btn:hover {
    box-shadow: 0 0 20px rgba(255,255,255,0.8);
    transform: scale(1.05);
}

/* CF Box Styling */
.cf-options {
    display: flex;
    flex-wrap: nowrap;
    justify-content: center;
    gap: 18px;
}
.cf-box {
    background: rgba(255,255,255,0.15);
    border: 2px solid rgba(255,255,255,0.25);
    padding: 25px 30px;
    border-radius: 12px;
    cursor: pointer;
    transition: 0.3s ease;
    color: #fff;
    font-weight: 500;
    min-width: 100px;
    text-align: center;
}
.cf-box:hover {
    background: rgba(255,255,255,0.25);
    transform: scale(1.05);
}
.cf-box input[type="radio"] { display:none; }
.cf-box input[type="radio"]:checked ~ span,
.cf-box:has(input[type="radio"]:checked) {
    background:#fff;
    color:#000 !important;
    border-color:#fff;
}
</style>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('.slides-container');
    const slides = Array.from(document.querySelectorAll('.question-slide'));
    const totalSlides = slides.length;
    if (!container || totalSlides === 0) return;

    let currentIndex = 0;

    function updateButtonsVisibility() {
        slides.forEach((s, idx) => {
            const backBtn = s.querySelector(`#backBtn_${idx}`);
            const nextBtn = s.querySelector(`#nextBtn_${idx}`);
            if (backBtn) backBtn.style.display = (idx === 0) ? 'none' : 'inline-block';
            if (nextBtn) nextBtn.style.display = (idx === totalSlides - 1) ? 'none' : 'inline-block';
        });
    }

    function updateSlidePosition() {
        container.style.transform = `translateX(-${currentIndex * 100}%)`;
        slides.forEach((slide, idx) => slide.classList.toggle('active', idx === currentIndex));
        updateButtonsVisibility();
    }

    window.nextSlide = function() {
        if (currentIndex < totalSlides - 1) {
            currentIndex++;
            updateSlidePosition();
        }
    };

    window.prevSlide = function() {
        if (currentIndex > 0) {
            currentIndex--;
            updateSlidePosition();
        }
    };

    updateSlidePosition();

    // ===== WARNING UNTUK FORM SUBMIT =====
    const moodForm = document.getElementById('moodForm');
    moodForm.addEventListener('submit', function(e) {
        const allCFs = moodForm.querySelectorAll('.cf-options');
        for (let cfGroup of allCFs) {
            const checked = cfGroup.querySelector('input[type="radio"]:checked');
            if (!checked) {
                e.preventDefault(); // hentikan submit
                alert('⚠️ Harap pilih salah satu mood pada setiap pertanyaan!');
                return false;
            }
        }
    });
});
</script>
