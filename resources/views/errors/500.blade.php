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
        <div style="display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap; margin-top: 1.5rem;">
            <a href="{{ url()->previous() }}" class="btn-back">Muat Ulang Halaman</a>
            @if(isset($exception))
            <button type="button" onclick="toggleErrorDetails()" class="btn-back" style="background: #ef4444; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                Lihat Detail Error
            </button>
            @endif
        </div>
        
        @if(isset($exception))
        <div id="errorDetailsBox" class="debug-msg" style="display: none; margin-top: 1.5rem;">
            <div style="font-weight: 800; color: #991b1b; margin-bottom: 8px; display: flex; justify-content: space-between; align-items: center;">
                <span>Detail Pesan Error Sistem:</span>
                <button type="button" onclick="copyErrorToClipboard()" style="font-size: 0.725rem; background: #991b1b; color: white; border: none; padding: 4px 10px; border-radius: 4px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                    Salin Error
                </button>
            </div>
            <pre id="errCodeText" style="white-space: pre-wrap; word-break: break-all; margin: 0; font-family: monospace; font-size: 0.75rem; color: #7f1d1d; max-height: 250px; overflow-y: auto; background: #fff5f5; padding: 0.75rem; border-radius: 6px; border: 1px dashed #fca5a5;">Pesan: {{ $exception->getMessage() ?: 'Terjadi kesalahan sistem internal.' }}
File: {{ $exception->getFile() }}:{{ $exception->getLine() }}

Stack Trace:
{{ $exception->getTraceAsString() }}</pre>
        </div>
        <script>
            function toggleErrorDetails() {
                var el = document.getElementById('errorDetailsBox');
                el.style.display = el.style.display === 'none' ? 'block' : 'none';
            }
            function copyErrorToClipboard() {
                var text = document.getElementById('errCodeText').innerText;
                navigator.clipboard.writeText(text).then(function() {
                    alert('Pesan error berhasil disalin ke clipboard!');
                }).catch(function() {
                    alert('Gagal menyalin. Silakan seleksi teks secara manual.');
                });
            }
        </script>
        @endif
    </div>
</body>
</html>
