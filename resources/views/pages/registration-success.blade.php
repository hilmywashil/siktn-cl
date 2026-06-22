@extends('layouts.app')

@section('title', 'Pendaftaran Berhasil')

@push('styles')
    <style>
        :root {
            --primary-blue: #022648;
            --secondary-blue: #2A348D;
            --accent-yellow: #C59217;
            --text-dark: #04293B;
            --text-grey: #6b7280;
            --bg-light: #F8F9FA;
        }

        body {
            background-color: var(--bg-light);
        }

        .success-page-container {
            max-width: 800px;
            margin: 60px auto;
            padding: 40px 20px;
        }

        .success-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            padding: 40px;
            text-align: center;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .success-icon {
            font-size: 4rem;
            color: #10B981;
            margin-bottom: 20px;
            animation: scaleUp 0.5s ease-out;
        }

        .success-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 10px;
        }

        .success-subtitle {
            font-size: 1.1rem;
            color: var(--text-grey);
            margin-bottom: 30px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        /* ==============================================
        KTA CARD DESIGN
        ============================================== */
        .kta-section-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-top: 40px;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .kta-card-wrapper {
            display: flex;
            justify-content: center;
            margin: 25px 0 35px 0;
        }

        .kta-card {
            width: 480px;
            height: 280px;
            background: linear-gradient(135deg, #022648 0%, #1e208f 50%, #040536 100%);
            border-radius: 16px;
            padding: 20px;
            position: relative;
            color: #fff;
            font-family: 'Montserrat', Arial, sans-serif;
            box-shadow: 0 15px 35px rgba(9, 11, 98, 0.25);
            overflow: hidden;
            border: 2px solid var(--accent-yellow);
            text-align: left;
        }

        .kta-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,231,1,0.08) 0%, rgba(255,255,255,0) 70%);
            pointer-events: none;
        }

        .kta-header {
            display: flex;
            align-items: center;
            border-bottom: 1px solid rgba(255, 231, 1, 0.3);
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .kta-logo {
            width: 42px;
            height: 42px;
            margin-right: 12px;
            object-fit: contain;
            border-radius: 50%;
            background: #fff;
            padding: 2px;
            border: 1px solid var(--accent-yellow);
        }

        .kta-title {
            flex-grow: 1;
        }

        .kta-title h2 {
            font-size: 13px;
            font-weight: 700;
            margin: 0;
            color: var(--accent-yellow);
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .kta-title p {
            font-size: 8px;
            margin: 2px 0 0 0;
            opacity: 0.8;
            letter-spacing: 0.3px;
        }

        .kta-body {
            display: flex;
            align-items: flex-start;
        }

        .kta-photo-wrapper {
            width: 90px;
            height: 115px;
            border: 1.5px solid var(--accent-yellow);
            border-radius: 6px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.1);
            margin-right: 20px;
            flex-shrink: 0;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        }

        .kta-photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .kta-details {
            flex-grow: 1;
        }

        .kta-field {
            margin-bottom: 7px;
        }

        .kta-label {
            font-size: 8px;
            color: var(--accent-yellow);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 1px;
            opacity: 0.9;
        }

        .kta-value {
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .kta-value.name {
            font-size: 14px;
            font-weight: 700;
            color: #fff;
            text-transform: uppercase;
            line-height: 1.2;
        }

        .kta-footer {
            position: absolute;
            bottom: 12px;
            left: 20px;
            right: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 8px;
        }

        .kta-footer-text {
            font-size: 8px;
            opacity: 0.7;
            letter-spacing: 0.3px;
        }

        .kta-barcode {
            font-size: 16px;
            letter-spacing: 2px;
            font-family: 'Courier New', Courier, monospace;
            color: var(--accent-yellow);
            opacity: 0.8;
            font-weight: bold;
        }

        .kta-actions {
            margin-bottom: 40px;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 24px;
            border-radius: 10px;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.95rem;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            text-decoration: none;
        }

        .btn-print {
            background-color: var(--primary-blue);
            color: #ffffff;
        }

        .btn-print:hover {
            background-color: var(--secondary-blue);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(9, 11, 98, 0.2);
        }

        .btn-dashboard {
            background-color: #E5E7EB;
            color: var(--text-dark);
        }

        .btn-dashboard:hover {
            background-color: #D1D5DB;
            transform: translateY(-2px);
        }

        /* ==============================================
        CREDENTIALS SECTION
        ============================================== */
        .credentials-box {
            background: var(--bg-light);
            border-radius: 15px;
            padding: 30px;
            margin-top: 30px;
            text-align: left;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .credentials-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .credentials-title i {
            color: var(--primary-blue);
        }

        .credentials-desc {
            font-size: 0.9rem;
            color: var(--text-grey);
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .credential-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 20px;
        }

        .credential-item {
            background: #ffffff;
            border: 1px solid rgba(0,0,0,0.06);
            border-radius: 8px;
            padding: 12px 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .credential-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-grey);
            text-transform: uppercase;
        }

        .credential-value {
            font-family: monospace;
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--text-dark);
            background: #F3F4F6;
            padding: 4px 10px;
            border-radius: 4px;
        }

        .alert-warning-box {
            background-color: #FFFBEB;
            border-left: 4px solid #F59E0B;
            padding: 15px;
            border-radius: 0 8px 8px 0;
            display: flex;
            gap: 12px;
            align-items: flex-start;
            margin-top: 15px;
        }

        .alert-warning-box i {
            color: #F59E0B;
            font-size: 1.1rem;
            margin-top: 2px;
        }

        .alert-warning-text {
            font-size: 0.85rem;
            color: #B45309;
            line-height: 1.5;
            margin: 0;
        }

        @keyframes scaleUp {
            0% { transform: scale(0.8); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        /* Print styling overrides */
        @media print {
            body * {
                visibility: hidden;
            }
            .kta-card-wrapper, .kta-card-wrapper * {
                visibility: visible;
            }
            .kta-card-wrapper {
                position: absolute;
                left: 0;
                top: 0;
                margin: 0;
                padding: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                width: 100%;
                height: 100vh;
            }
            .kta-card {
                box-shadow: none !important;
                border: 2px solid #000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
@endpush

@section('content')
    <div class="success-page-container">
        <div class="success-card">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            
            <h1 class="success-title">Pendaftaran Berhasil!</h1>
            <p class="success-subtitle">
                Terima kasih telah mendaftar di Corps Alumni Akademi Ilmu Pelayaran (Karang Taruna). Akun pendaftaran Anda telah berhasil dibuat dan saat ini sedang dalam proses verifikasi oleh tim administrator.
            </p>

            <h3 class="kta-section-title">Kartu Anggota Digital Sementara</h3>
            
            <div class="kta-card-wrapper">
                <div class="kta-card">
                    <div class="kta-header">
                        <img src="{{ asset('assets/img/logo.png') }}" class="kta-logo" alt="Karang Taruna" onerror="this.src='https://ui-avatars.com/api/?name=Karang Taruna&background=090b62&color=fff'">
                        <div class="kta-title">
                            <h2>Corps Alumni Akademi Ilmu Pelayaran</h2>
                            <p>KARTU TANDA ANGGOTA DIGITAL SEMENTARA</p>
                        </div>
                    </div>
                    
                    <div class="kta-body">
                        <div class="kta-photo-wrapper">
                            @if($anggota->foto_diri)
                                <img src="{{ Storage::url($anggota->foto_diri) }}" class="kta-photo" alt="{{ $anggota->nama_lengkap }}">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($anggota->nama_lengkap) }}&background=2A348D&color=fff&size=120" class="kta-photo" alt="Photo">
                            @endif
                        </div>
                        
                        <div class="kta-details">
                            <div class="kta-field">
                                <div class="kta-label">Nama Lengkap</div>
                                <div class="kta-value name">{{ $anggota->nama_lengkap }}</div>
                            </div>
                            
                            <div class="kta-field" style="display: inline-block; width: 55%;">
                                <div class="kta-label">NRP</div>
                                <div class="kta-value">{{ $anggota->nrp }}</div>
                            </div>
                            
                            <div class="kta-field" style="display: inline-block; width: 40%;">
                                <div class="kta-label">Angkatan</div>
                                <div class="kta-value">Angkatan {{ $anggota->angkatan }}</div>
                            </div>

                            <div class="kta-field">
                                <div class="kta-label">Status Keanggotaan</div>
                                <div class="kta-value" style="color: var(--accent-yellow); font-size: 11px;">
                                    <i class="fas fa-clock"></i> PENDING VERIFICATION
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="kta-footer">
                        <span class="kta-footer-text">Corps Alumni Akademi Ilmu Pelayaran - STIP Jakarta</span>
                        <span class="kta-barcode">Karang Taruna-{{ str_pad($anggota->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>
                </div>
            </div>

            <div class="kta-actions">
                <button type="button" class="btn-action btn-print" onclick="printKTA()">
                    <i class="fas fa-print"></i> Cetak Kartu Anggota
                </button>
                <a href="{{ route('profile-anggota') }}" class="btn-action btn-dashboard">
                    <i class="fas fa-tachometer-alt"></i> Masuk ke Dashboard
                </a>
            </div>

            <div class="credentials-box">
                <div class="credentials-title">
                    <i class="fas fa-key"></i>
                    Detail Akun Pendaftaran Anda
                </div>
                <p class="credentials-desc">
                    Silakan simpan informasi di bawah ini. Anda dapat menggunakannya untuk masuk ke Area Anggota dan memantau status persetujuan pendaftaran Anda.
                </p>

                <div class="credential-list">
                    <div class="credential-item">
                        <span class="credential-label">Email</span>
                        <span class="credential-value">{{ $anggota->email }}</span>
                    </div>
                    <div class="credential-item">
                        <span class="credential-label">Password Sementara</span>
                        @if(session('generated_password'))
                            <span class="credential-value">{{ session('generated_password') }}</span>
                        @else
                            <span class="credential-value" style="font-family: inherit; font-size: 0.85rem; color: #DC2626; font-weight: normal; background: none; padding: 0;">Sudah Kedaluwarsa (Hanya tampil sekali demi keamanan)</span>
                        @endif
                    </div>
                </div>

                <div class="alert-warning-box">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        <h4 style="margin: 0 0 5px 0; font-size: 0.9rem; font-weight: 700; color: #B45309;">PENTING: Harap Catat Password Anda!</h4>
                        <p class="alert-warning-text">
                            Password sementara ini di-generate secara otomatis oleh sistem demi keamanan dan <strong>hanya akan ditampilkan sekali saja</strong> pada halaman ini. Harap salin dan catat di tempat yang aman sebelum meninggalkan halaman ini.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function printKTA() {
            const cardHtml = document.querySelector('.kta-card-wrapper').innerHTML;
            
            // Get original document styles
            let stylesHtml = '';
            document.querySelectorAll('style, link[rel="stylesheet"]').forEach(el => {
                stylesHtml += el.outerHTML;
            });

            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Cetak KTA - Karang Taruna</title>
                        ${stylesHtml}
                        <style>
                            body {
                                margin: 0;
                                padding: 0;
                                display: flex;
                                justify-content: center;
                                align-items: center;
                                height: 100vh;
                                background: #ffffff;
                            }
                            .kta-card-wrapper {
                                margin: 0 !important;
                                display: flex !important;
                                justify-content: center !important;
                                align-items: center !important;
                            }
                            .kta-card {
                                box-shadow: none !important;
                                transform: scale(1.1);
                            }
                            @media print {
                                body {
                                    -webkit-print-color-adjust: exact;
                                    print-color-adjust: exact;
                                }
                            }
                        </style>
                    </head>
                    <body>
                        <div class="kta-card-wrapper">
                            ${cardHtml}
                        </div>
                        <script>
                            window.onload = function() {
                                setTimeout(function() {
                                    window.print();
                                    window.close();
                                }, 500);
                            };
                        <\/script>
                    </body>
                </html>
            `);
            printWindow.document.close();
        }
    </script>
@endpush
