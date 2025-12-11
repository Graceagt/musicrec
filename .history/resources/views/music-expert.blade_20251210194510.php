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

    // 🚨 WARNING JIKA BELUM MEMILIH CF
    function validateCurrentSlide() {
        const slide = slides[currentIndex];
        const radios = slide.querySelectorAll('input[type="radio"]');
        let selected = false;

        radios.forEach(r => {
            if (r.checked) selected = true;
        });

        if (!selected) {
            alert("⚠️ Silakan pilih tingkat keyakinan (CF) terlebih dahulu!");
            return false;
        }
        return true;
    }

    window.nextSlide = function() {
        if (!validateCurrentSlide()) return; // Stop kalau belum pilih CF

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
/* Semua CSS dibiarkan sama persis tanpa perubahan */
...
</style>
@endsection
