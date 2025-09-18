<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Spin Wheel</title>
  <link rel="stylesheet" href="{{ asset('css/spinwheel.css') }}">
</head>
<body>
  <div class="container">
    <div class="wheel" id="wheel">
      <div class="segment"><span>Pop</span></div>
      <div class="segment"><span>Rock</span></div>
      <div class="segment"><span>Jazz</span></div>
      <div class="segment"><span>Hip-Hop</span></div>
      <div class="segment"><span>R&amp;B</span></div>
      <div class="segment"><span>Classical</span></div>
      <div class="segment"><span>Indie</span></div>
      <div class="segment"><span>Metal</span></div>
    </div>
    <button onclick="spin()">SPIN</button>
  </div>

  <script>
    let wheel = document.getElementById("wheel");
    let deg = 0;

    function spin() {
      let extra = Math.floor(Math.random() * 360); // hasil random
      deg += 1800 + extra; // minimal 5 putaran
      wheel.style.transform = `rotate(${deg}deg)`;
    }
  </script>
</body>
</html>
