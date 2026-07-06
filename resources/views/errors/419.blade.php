<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 - Sesi Berakhir</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        body { margin: 0; padding: 0; font-family: 'Inter', sans-serif; background-color: #0a2540; color: white; display: flex; align-items: center; justify-content: center; height: 100vh; text-align: center; }
        .container { max-width: 600px; padding: 2rem; }
        .error-code { font-size: 8rem; font-weight: 800; margin: 0; color: #facc15; line-height: 1; text-shadow: 0 4px 6px rgba(0,0,0,0.3); }
        .error-title { font-size: 2rem; font-weight: 600; margin: 1rem 0; }
        .error-desc { font-size: 1.125rem; color: #cbd5e1; margin-bottom: 2.5rem; line-height: 1.6; }
        .btn { display: inline-block; background-color: #facc15; color: #0a2540; padding: 0.875rem 2rem; border-radius: 9999px; font-weight: 600; text-decoration: none; transition: all 0.2s; box-shadow: 0 4px 6px rgba(250, 204, 21, 0.25); }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 6px 8px rgba(250, 204, 21, 0.3); background-color: #fde047; }
        .logo { margin-bottom: 2rem; width: 100px; height: 100px; object-fit: contain; }
    </style>
</head>
<body>
    <div class="container">
        <img src="{{ asset('assets-front/images/logo_karang_taruna.png') }}" alt="Karang Taruna" class="logo">
        <h1 class="error-code">419</h1>
        <h2 class="error-title">Sesi Anda Telah Berakhir</h2>
        <p class="error-desc">Maaf, halaman ini sudah terlalu lama didiamkan (kedaluwarsa). Silakan muat ulang halaman atau login kembali untuk melanjutkan.</p>
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : url('/') }}" class="btn">Muat Ulang Halaman</a>
    </div>
</body>
</html>
