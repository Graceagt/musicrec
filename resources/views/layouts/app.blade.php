<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sistem Pakar Musik</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            backdrop-filter: blur(10px);
            color: #fff;
            box-shadow: 0 8px 16px rgba(0,0,0,0.3);
        }
        .form-range::-webkit-slider-thumb {
            background: #ffcc00;
        }
        .btn-custom {
            background: #ffcc00;
            border: none;
            color: #000;
            font-weight: bold;
        }
        .btn-custom:hover {
            background: #ffaa00;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        @yield('content')
    </div>
</body>
</html>
