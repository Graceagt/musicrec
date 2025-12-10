@extends('layouts.app')

@section('content')
<div class="music-background position-relative d-flex flex-column justify-content-center align-items-center" 
     style="min-height: 100vh; overflow: hidden;">

    <div class="stars"></div>

    <h1 class="text-center text-white fw-bold display-6 mb-4 animate-title">
        <span class="typing-text">🎧 Pilih Mood Musik</span>
    </h1>

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
                                        <input type="radio" name="cf[{{ $mood }}]" value="{{ $value }}">
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
                                            onclick="validateNext('{{ $mood }}')">Next</button>
                                @else
                                    <button type="submit" class="btn btn-light text-black fw-semibold px-4 py-2 rounded-pill glow-btn"
                                            onclick="return validateSubmit()">
                                        🎵 Rekomendasikan Musik
                                    </button>
                                @endif
                            </div>

                            <!-- ⚠️ WARNING -->
                            <p class="warning-msg text-danger fw-semibold mt-2 mb-0" style="display:none;">
                                ⚠️ Anda harus memilih salah satu nilai CF terlebih dahulu.
                            </p>

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

    window.prevSlide = function() {
        if (currentIndex > 0) {
            currentIndex--;
            updateSlidePosition();
        }
    };

    /* ==============================
       VALIDASI NEXT
    ===============================*/
    window.validateNext = function(moodName) {
        const radios = document.querySelectorAll(`input[name="cf[${moodName}]"]`);
        const warning = slides[currentIndex].querySelector('.warning-msg');

        let selected = false;
        radios.forEach(r => { if (r.checked) selected = true; });

        if (!selected) {
            warning.style.display = "block";
            warning.style.animation = "shake 0.4s";
            setTimeout(() => warning.style.animation = "", 400);
            return;
        }

        warning.style.display = "none";
        currentIndex++;
        updateSlidePosition();
    };

    /* ==============================
       VALIDASI SUBMIT
    ===============================*/
    window.validateSubmit = function() {
        for (let i = 0; i < totalSlides; i++) {
            const moodName = slides[i].querySelector("input[type='radio']").name;
            const moodKey = moodName.match(/\[(.*?)\]/)[1];
            const radios = document.querySelectorAll(`input[name="cf[${moodKey}]"]`);
            let selected = false;
            radios.forEach(r => { if (r.checked) selected = true; });

            if (!selected) {
                currentIndex = i;
                updateSlidePosition();
                const warning = slides[i].querySelector('.warning-msg');
                warning.style.display = "block";
                warning.style.animation = "shake 0.4s";
                setTimeout(() => warning.style.animation = "", 400);
                return false;
            }
        }
        return true;
    };

    updateSlidePosition();
});
</script>

<!-- ANIMASI SHAKE -->
<style>
@keyframes shake {
    0% { transform: translateX(0); }
    20% { transform: translateX(-6px); }
    40% { transform: translateX(6px); }
    60% { transform: translateX(-6px); }
    80% { transform: translateX(6px); }
    100% { transform: translateX(0); }
}
</style>

@endsection
