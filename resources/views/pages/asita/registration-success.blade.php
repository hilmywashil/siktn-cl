<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Berhasil - ASITA Jawa Barat</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #2A348D 0%, #04293B 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            max-width: 700px;
            width: 100%;
        }

        .card {
            background: #fff;
            padding: 60px 50px;
            text-align: center;
        }

        .icon {
            width: 120px;
            height: 120px;
            background: #2A348D;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 40px;
        }

        .icon svg {
            width: 60px;
            height: 60px;
            stroke: #fff;
            stroke-width: 5;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        h1 {
            font-size: 3.5rem;
            font-weight: 900;
            color: #04293B;
            margin-bottom: 20px;
            letter-spacing: -1px;
            text-transform: uppercase;
        }

        .subtitle {
            font-size: 1.1rem;
            color: #04293B;
            font-weight: 700;
            margin-bottom: 40px;
            line-height: 1.6;
        }

        .status {
            display: inline-block;
            padding: 15px 40px;
            background: #2A348D;
            color: #fff;
            font-weight: 900;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 50px;
        }

        .credentials {
            background: #04293B;
            padding: 40px;
            margin: 40px 0;
            text-align: left;
        }

        .credentials h3 {
            color: #fff;
            font-size: 1.5rem;
            font-weight: 900;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .cred-item {
            background: #2A348D;
            padding: 20px;
            margin-bottom: 20px;
        }

        .cred-item:last-child {
            margin-bottom: 0;
        }

        .cred-label {
            color: #fff;
            font-size: 0.85rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 12px;
        }

        .cred-value {
            display: flex;
            align-items: center;
            gap: 15px;
            background: #fff;
            padding: 15px 20px;
        }

        .cred-text {
            font-family: 'Courier New', monospace;
            font-size: 1.1rem;
            font-weight: 900;
            color: #04293B;
            flex: 1;
            word-break: break-all;
            user-select: all;
        }

        .btn-copy {
            background: #2A348D;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 800;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .btn-copy:hover {
            background: #04293B;
        }

        .btn-copy.copied {
            background: #28a745;
        }

        .warning {
            background: #2A348D;
            padding: 30px;
            margin-top: 40px;
            text-align: left;
        }

        .warning h4 {
            color: #fff;
            font-size: 1.3rem;
            font-weight: 900;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .warning ul {
            list-style: none;
            padding: 0;
        }

        .warning li {
            color: #fff;
            font-weight: 700;
            font-size: 0.95rem;
            margin-bottom: 12px;
            padding-left: 20px;
            position: relative;
            line-height: 1.6;
        }

        .warning li:before {
            content: "■";
            position: absolute;
            left: 0;
            font-weight: 900;
        }

        .buttons {
            display: flex;
            gap: 20px;
            margin-top: 50px;
            flex-wrap: wrap;
        }

        .btn {
            flex: 1;
            min-width: 200px;
            padding: 20px 40px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 800;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-decoration: none;
            text-align: center;
            transition: all 0.2s;
            cursor: pointer;
        }

        .btn-primary {
            background: #2A348D;
            color: #fff;
            border: 3px solid #2A348D;
        }

        .btn-primary:hover {
            background: #fff;
            color: #2A348D;
        }

        .btn-secondary {
            background: #fff;
            color: #2A348D;
            border: 3px solid #2A348D;
        }

        .btn-secondary:hover {
            background: #2A348D;
            color: #fff;
        }

        @media (max-width: 768px) {
            .card {
                padding: 40px 30px;
            }

            h1 {
                font-size: 2.5rem;
            }

            .subtitle {
                font-size: 1rem;
            }

            .credentials {
                padding: 30px 20px;
            }

            .buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }

            .icon {
                width: 100px;
                height: 100px;
            }

            .icon svg {
                width: 50px;
                height: 50px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="icon">
                <svg viewBox="0 0 24 24" fill="none">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
            </div>

            <h1>BERHASIL</h1>
            <p class="subtitle">
                Akun Anda telah dibuat dan sedang menunggu verifikasi dari admin ASITA Jawa Barat.
            </p>

            <div class="status">MENUNGGU PERSETUJUAN</div>

            <div class="credentials">
                <h3>KREDENSIAL LOGIN</h3>

                <div class="cred-item">
                    <div class="cred-label">EMAIL</div>
                    <div class="cred-value">
                        <span class="cred-text" id="email">{{ auth('anggota')->user()->email_website_perusahaan }}</span>
                        <button class="btn-copy" onclick="copy('email', this)">COPY</button>
                    </div>
                </div>

                <div class="cred-item">
                    <div class="cred-label">PASSWORD</div>
                    <div class="cred-value">
                        <span class="cred-text" id="password">{{ session('generated_password') }}</span>
                        <button class="btn-copy" onclick="copy('password', this)">COPY</button>
                    </div>
                </div>
            </div>

            <div class="warning">
                <h4>PENTING</h4>
                <ul>
                    <li>SIMPAN PASSWORD INI DENGAN AMAN - Hanya ditampilkan sekali</li>
                    <li>Anda dapat login namun akses penuh tersedia setelah disetujui</li>
                    <li>Ganti password setelah login pertama kali</li>
                    <li>Verifikasi memakan waktu 1-3 hari kerja</li>
                </ul>
            </div>

            <div class="buttons">
                <a href="{{ route('profile-anggota') }}" class="btn btn-primary">LIHAT PROFIL</a>
                <a href="{{ route('home') }}" class="btn btn-secondary">BERANDA</a>
            </div>
        </div>
    </div>

    <script>
        function copy(id, btn) {
            const text = document.getElementById(id).innerText.trim();
            
            navigator.clipboard.writeText(text).then(() => {
                const original = btn.innerText;
                btn.classList.add('copied');
                btn.innerText = 'TERSALIN!';
                
                setTimeout(() => {
                    btn.classList.remove('copied');
                    btn.innerText = original;
                }, 2000);
            }).catch(() => {
                alert('Gagal menyalin. Copy manual.');
            });
        }
    </script>
</body>
</html>