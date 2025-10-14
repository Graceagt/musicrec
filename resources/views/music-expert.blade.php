<!DOCTYPE html>
<html>
<head>
    <title>🎧 Pilih Mood Musik</title>
</head>
<body>
    <h1>Pilih Mood dan Tingkat Keyakinan (CF)</h1>

    @if(session('error'))
        <p style="color:red">{{ session('error') }}</p>
    @endif

    <form action="{{ route('music.recommend') }}" method="POST">
        @csrf

        @foreach($moods as $mood)
            <div>
                <label for="{{ $mood }}">{{ ucfirst($mood) }}</label>
                <select name="cf[{{ $mood }}]" id="{{ $mood }}">
                    <option value="" selected disabled>-- Pilih tingkat mood Anda --</option>
                    @foreach($cfOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        @endforeach

        <button type="submit">Rekomendasikan Musik</button>
    </form>
</body>
</html>
