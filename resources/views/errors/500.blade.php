<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Terjadi Kesalahan | SIKTN</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', sans-serif;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            color: #0a2540;
        }
        .error-container {
            text-align: center;
            background: white;
            padding: 3rem;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            max-width: 500px;
            width: 90%;
        }
        .error-icon {
            color: #ef4444;
            margin-bottom: 1.5rem;
        }
        h1 {
            font-size: 4rem;
            margin: 0;
            font-weight: 800;
            line-height: 1;
            color: #ef4444;
        }
        h2 {
            font-size: 1.5rem;
            margin: 1rem 0;
            font-weight: 700;
        }
        p {
            color: #6b7280;
            margin-bottom: 2rem;
            line-height: 1.5;
        }
        .btn-back {
            display: inline-block;
            background: #0a2540;
            color: white;
            text-decoration: none;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn-back:hover {
            background: #ffd700;
            color: #0a2540;
            transform: translateY(-2px);
        }
        .debug-msg {
            margin-top: 2rem;
            padding: 1rem;
            background: #fef2f2;
            border: 1px solid #fee2e2;
            border-radius: 8px;
            font-size: 0.75rem;
            color: #991b1b;
            text-align: left;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">
            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                <line x1="12" y1="9" x2="12" y2="13"></line>
                <line x1="12" y1="17" x2="12.01" y2="17"></line>
            </svg>
        </div>
        <h1>500</h1>
        <h2>Kesalahan Sistem</h2>
        <p>Maaf, terjadi kesalahan internal pada server kami. Tim teknis kami telah diberitahu dan sedang menangani masalah ini.</p>
        <a href="{{ url()->previous() }}" class="btn-back">Muat Ulang Halaman</a>
        
        @if(config('app.debug') && isset($exception))
        <div class="debug-msg">
            <strong>Pesan Debug:</strong><br>
            {{ $exception->getMessage() }}
        </div>
        @endif
    </div>
</body>
</html>
