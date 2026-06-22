@extends('layouts.app')

@section('title', 'Dashboard Anggota')

@push('styles')
    <style>
        :root {
            --primary-blue: #0B1354;
            --secondary-blue: #18227C;
            --accent-yellow: #C59217;
            --accent-red: #D60B1C;
            --text-dark: #0B1354;
            --text-grey: #6b7280;
            --bg-light: #F8F9FC;
        }

        body {
            font-family: 'Google Sans', 'Outfit', sans-serif !important;
            background-color: var(--bg-light) !important;
        }

        /* Force Sticky Header to show at the top of the dashboard */
        .header-sticky {
            top: 0 !important;
            position: sticky !important;
            box-shadow: 0 4px 20px rgba(11, 19, 84, 0.05) !important;
            background-color: #ffffff !important;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .header-inner {
            max-width: 1400px !important;
        }

        .dashboard-container {
            max-width: 1400px;
            margin: 40px auto 80px auto;
            padding: 0 20px;
            font-family: 'Google Sans', sans-serif;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 30px;
        }

        /* ==============================================
        SIDEBAR STYLING
        ============================================== */
        .dashboard-sidebar {
            background: #ffffff;
            border-radius: 14px;
            padding: 28px 20px;
            box-shadow: 0 10px 30px rgba(11, 19, 84, 0.03);
            border: 1px solid rgba(11, 19, 84, 0.06);
            height: fit-content;
        }

        .user-badge {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        }

        .user-avatar-wrapper {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 15px auto;
            border: 3px solid var(--accent-yellow);
            box-shadow: 0 4px 15px rgba(11, 19, 84, 0.12);
        }

        .user-avatar {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-name {
            font-family: 'Google Sans', sans-serif;
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin: 0 0 4px 0;
            letter-spacing: -0.2px;
        }

        .user-nrp {
            font-family: monospace;
            font-size: 0.85rem;
            color: var(--text-grey);
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 10px;
        }

        .status-pending {
            background-color: rgba(197, 146, 23, 0.08) !important;
            color: var(--accent-yellow) !important;
            border: 1px solid rgba(197, 146, 23, 0.2) !important;
        }

        .status-approved {
            background-color: rgba(197, 146, 23, 0.08) !important;
            color: var(--accent-yellow) !important;
            border: 1px solid rgba(197, 146, 23, 0.2) !important;
        }

        .status-rejected {
            background-color: rgba(214, 11, 28, 0.08) !important;
            color: var(--accent-red) !important;
            border: 1px solid rgba(214, 11, 28, 0.18) !important;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .sidebar-menu-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 16px;
            width: 100%;
            border: none;
            background: transparent;
            font-family: 'Google Sans', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-grey);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.25s ease;
            text-align: left;
            text-decoration: none;
        }

        .sidebar-menu-btn:hover {
            background-color: rgba(11, 19, 84, 0.04);
            color: var(--primary-blue);
        }

        .sidebar-menu-btn.active {
            background-color: var(--primary-blue);
            color: #ffffff;
            border-left: 4px solid var(--accent-yellow);
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        .sidebar-menu-btn i {
            font-size: 1rem;
            width: 18px;
            text-align: center;
            opacity: 0.9;
        }

        /* ==============================================
        CONTENT SECTIONS
        ============================================== */
        .dashboard-content {
            background: #ffffff;
            border-radius: 14px;
            padding: 35px 40px;
            box-shadow: 0 10px 30px rgba(11, 19, 84, 0.03);
            border: 1px solid rgba(11, 19, 84, 0.06);
            min-height: 550px;
        }

        .content-section {
            display: none;
            animation: fadeIn 0.25s ease-out;
        }

        .content-section.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .section-title {
            font-family: 'Google Sans', sans-serif;
            font-size: 1.45rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 24px;
            border-bottom: 2px solid var(--accent-yellow);
            padding-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Grid info */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-top: 10px;
        }

        .info-card {
            display: flex;
            align-items: center;
            gap: 18px;
            padding: 20px 24px;
            background: #ffffff;
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(11, 19, 84, 0.04), 0 1px 3px rgba(11, 19, 84, 0.02);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
        }

        .info-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: var(--accent-yellow);
            opacity: 0;
            transition: all 0.3s;
        }

        .info-card:hover {
            box-shadow: 0 8px 25px rgba(11, 19, 84, 0.08);
            transform: translateY(-3px);
        }

        .info-card:hover::before {
            opacity: 1;
        }

        .info-card-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: rgba(11, 19, 84, 0.04);
            color: var(--primary-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .info-card:hover .info-card-icon {
            background: var(--primary-blue);
            color: #ffffff;
            transform: scale(1.05) rotate(5deg);
        }

        .info-card-content {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .info-card-label {
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--text-grey);
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .info-card-value {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .info-card-value.empty {
            color: #9CA3AF;
            font-style: italic;
        }

        /* ==============================================
        KTA PREVIEW STYLING (NEW VERTICAL DESIGN)
        ============================================== */
        .kta-card-wrapper {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin: 30px 0;
            flex-wrap: wrap;
        }

        .kta-card {
            width: 320px;
            height: 510px;
            background: #ffffff;
            border-radius: 12px;
            position: relative;
            font-family: 'Times New Roman', Times, serif;
            box-shadow: 0 15px 35px rgba(11, 19, 84, 0.15);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .kta-card * {
            box-sizing: border-box;
        }

        /* Front Side Elements */
        .kta-header-front {
            background: var(--primary-blue);
            color: #ffffff;
            text-align: center;
            padding: 30px 15px 40px;
            position: relative;
        }

        .kta-header-front h2 {
            font-size: 22px;
            font-weight: 700;
            margin: 0 0 5px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            line-height: 1.2;
        }

        .kta-header-front p {
            font-family: 'Google Sans', sans-serif;
            font-size: 11px;
            margin: 0;
            letter-spacing: 1px;
            font-weight: 500;
        }

        .kta-gold-bar {
            background: var(--accent-yellow);
            height: 8px;
            width: 100%;
        }

        .kta-front-curve {
            position: absolute;
            bottom: -20px;
            left: -10%;
            width: 120%;
            height: 40px;
            background: var(--primary-blue);
            border-radius: 50%;
            border-bottom: 8px solid var(--accent-yellow);
        }

        .kta-logo-front {
            width: 130px;
            height: 130px;
            margin: -30px auto 10px;
            position: relative;
            z-index: 2;
            background: white;
            border-radius: 50%;
            padding: 4px;
        }

        .kta-logo-front img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 50%;
        }

        .kta-member-name {
            text-align: center;
            color: var(--primary-blue);
            font-size: 17px;
            font-weight: 700;
            text-transform: uppercase;
            margin: 5px 15px 0;
            line-height: 1.2;
        }

        .kta-member-nrp {
            text-align: center;
            color: var(--primary-blue);
            font-family: 'Google Sans', sans-serif;
            font-size: 14px;
            font-weight: 700;
            margin-top: 5px;
        }

        .kta-anchor-divider {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin: 10px 0;
            color: var(--accent-yellow);
            font-size: 18px;
        }

        .kta-anchor-divider::before,
        .kta-anchor-divider::after {
            content: '';
            width: 80px;
            height: 2px;
            background: var(--accent-yellow);
        }

        .kta-details-container {
            display: flex;
            padding: 0 20px;
            margin-top: 10px;
            gap: 15px;
        }

        .kta-photo-wrapper {
            width: 85px;
            height: 110px;
            border: 1px solid #e5e7eb;
            background: #f9fafb;
            flex-shrink: 0;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .kta-photo-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .kta-details-list {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .kta-detail-row {
            display: flex;
            font-family: 'Google Sans', sans-serif;
            font-size: 9px;
            font-weight: 700;
            color: var(--primary-blue);
        }

        .kta-detail-label {
            width: 85px;
            flex-shrink: 0;
        }

        .kta-detail-value {
            flex-grow: 1;
        }

        .kta-bottom-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            padding: 10px 20px 15px;
            margin-top: auto;
        }

        .kta-qr-code {
            width: 65px;
            height: 65px;
            background: white;
            padding: 2px;
        }

        .kta-signature {
            text-align: center;
            color: var(--primary-blue);
        }

        .kta-signature-img {
            height: 40px;
            margin-bottom: 5px;
            object-fit: contain;
        }

        .kta-signature-text {
            font-family: 'Google Sans', sans-serif;
            font-size: 9px;
            font-weight: 700;
            margin: 0;
        }

        .kta-signature-subtext {
            font-family: 'Google Sans', sans-serif;
            font-size: 8px;
            color: #666;
            margin: 2px 0 0;
        }

        .kta-bottom-bar {
            height: 15px;
            background: var(--accent-yellow);
            width: 100%;
            margin-top: auto;
        }

        /* Back Side Elements */
        .kta-card-back {
            background: #ffffff;
            position: relative;
        }

        .kta-card-back .kta-header-front {
            padding: 25px 15px;
            border-radius: 0;
        }

        .kta-card-back .kta-header-front h2 {
            font-family: 'Google Sans', sans-serif;
            font-size: 18px;
            letter-spacing: 1px;
            margin: 0;
        }

        .kta-back-content {
            padding: 30px 25px;
            position: relative;
            z-index: 2;
        }

        .kta-back-ketentuan-title {
            font-family: 'Google Sans', sans-serif;
            font-weight: 700;
            font-size: 12px;
            color: var(--primary-blue);
            margin-bottom: 15px;
        }

        .kta-back-ketentuan-list {
            margin: 0;
            padding-left: 15px;
            font-family: 'Google Sans', sans-serif;
            font-size: 11px;
            color: var(--primary-blue);
            line-height: 1.6;
            font-weight: 600;
        }

        .kta-back-ketentuan-list li {
            margin-bottom: 8px;
        }

        .kta-watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 180px;
            height: 180px;
            opacity: 0.1;
            z-index: 1;
            pointer-events: none;
        }

        .kta-back-footer {
            background: var(--primary-blue);
            color: white;
            text-align: center;
            padding: 25px 20px 20px;
            margin-top: auto;
            position: relative;
        }

        .kta-back-footer-curve {
            position: absolute;
            top: -30px;
            left: -10%;
            width: 120%;
            height: 60px;
            background: var(--primary-blue);
            border-radius: 50%;
            z-index: 1;
        }

        .kta-back-footer-content {
            position: relative;
            z-index: 2;
        }

        .kta-back-footer-title {
            color: var(--accent-yellow);
            font-size: 16px;
            font-weight: 700;
            margin: 0 0 3px;
        }

        .kta-back-footer-subtitle {
            font-family: 'Google Sans', sans-serif;
            font-size: 10px;
            font-weight: 600;
            margin: 0 0 15px;
        }

        .kta-back-address {
            font-family: 'Google Sans', sans-serif;
            font-size: 10px;
            margin-bottom: 15px;
            line-height: 1.4;
        }

        .kta-back-contact {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            font-family: 'Google Sans', sans-serif;
            font-size: 10px;
            margin-bottom: 15px;
        }

        .kta-back-contact i {
            margin-right: 5px;
        }

        .kta-back-validity {
            color: var(--accent-yellow);
            font-family: 'Google Sans', sans-serif;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.5px;
            line-height: 1.4;
        }

        /* ==============================================
        COMPANY INPUT FORM & LOCKED STATE
        ============================================== */
        .locked-state-box {
            background-color: #FFFDF5;
            border: 1px dashed #F59E0B;
            border-radius: 10px;
            padding: 35px 30px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.02);
        }

        .locked-icon {
            font-size: 2.2rem;
            color: #D97706;
            margin-bottom: 12px;
        }

        .locked-title {
            font-family: 'Google Sans', sans-serif;
            font-size: 1.15rem;
            font-weight: 700;
            color: #92400E;
            margin-bottom: 8px;
        }

        .locked-desc {
            font-size: 0.88rem;
            color: #B45309;
            line-height: 1.6;
            max-width: 520px;
            margin: 0 auto;
        }

        /* Form elements */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 25px;
        }

        .form-grid.full-width {
            grid-template-columns: 1fr;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        .form-control {
            padding: 11px 16px;
            border-radius: 8px;
            border: 1px solid rgba(9, 11, 98, 0.12);
            font-family: 'Google Sans', sans-serif;
            font-size: 0.92rem;
            transition: all 0.25s ease;
            background-color: #ffffff;
            color: var(--text-dark);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(9, 11, 98, 0.08);
        }

        .btn-submit {
            background-color: var(--primary-blue);
            color: #ffffff;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-family: 'Google Sans', sans-serif;
            font-weight: 700;
            font-size: 0.92rem;
            cursor: pointer;
            transition: all 0.25s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-submit:hover {
            background-color: var(--secondary-blue);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(9, 11, 98, 0.15);
        }

        .form-section-divider {
            font-family: 'Google Sans', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--secondary-blue);
            margin: 25px 0 10px 0;
            padding-bottom: 8px;
            border-bottom: 1px solid rgba(9, 11, 98, 0.06);
            grid-column: span 2;
        }

        /* File list */
        .file-upload-item {
            background-color: #F8F9FD;
            border: 1px solid rgba(9, 11, 98, 0.04);
            border-radius: 8px;
            padding: 12px 18px;
            margin-bottom: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .file-upload-info h5 {
            margin: 0;
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .file-upload-info p {
            margin: 2px 0 0 0;
            font-size: 0.72rem;
            color: var(--text-grey);
        }

        .file-upload-link {
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--secondary-blue);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .file-upload-link:hover {
            color: var(--primary-blue);
            text-decoration: underline;
        }

        /* Alert notifications */
        .alert-box {
            border-radius: 8px;
            padding: 14px 18px;
            margin-bottom: 25px;
            display: flex;
            gap: 12px;
            align-items: flex-start;
            font-size: 0.88rem;
        }

        .alert-success {
            background-color: #D1FAE5;
            color: #065F46;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .alert-danger {
            background-color: rgba(214, 11, 28, 0.06) !important;
            color: var(--accent-red) !important;
            border: 1px solid rgba(214, 11, 28, 0.15) !important;
        }

        @media (max-width: 991px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .info-grid, .form-grid {
                grid-template-columns: 1fr;
            }
            .form-section-divider {
                grid-column: span 1;
            }
            .kta-card {
                width: 100%;
                height: auto;
                aspect-ratio: 480 / 280;
            }
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-container">
        <!-- Alert Notification -->
        @if(session('success'))
            <div class="alert-box alert-success">
                <i class="fas fa-check-circle" style="margin-top: 2px;"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert-box alert-danger">
                <i class="fas fa-exclamation-circle" style="margin-top: 2px;"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        @if($errors->any())
            <div class="alert-box alert-danger">
                <i class="fas fa-exclamation-circle" style="margin-top: 2px;"></i>
                <div>
                    <ul style="margin: 0; padding-left: 15px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="dashboard-grid">
            <!-- SIDEBAR -->
            <aside class="dashboard-sidebar">
                <div class="user-badge">
                    <div class="user-avatar-wrapper">
                        @if($anggota->foto_diri)
                            <img src="{{ Storage::url($anggota->foto_diri) }}" class="user-avatar" alt="{{ $anggota->nama_lengkap }}">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($anggota->nama_lengkap) }}&background=090b62&color=fff&size=120" class="user-avatar" alt="Avatar">
                        @endif
                    </div>
                    <h4 class="user-name">{{ $anggota->nama_lengkap }}</h4>
                    <div class="user-nrp">NRP: {{ $anggota->nrp }}</div>
                    
                    @if($anggota->status == 'pending')
                        <span class="status-badge status-pending"><i class="fas fa-clock"></i> PENDING ACC</span>
                    @elseif($anggota->status == 'approved')
                        <span class="status-badge status-approved"><i class="fas fa-check-circle"></i> VERIFIED MEMBER</span>
                    @else
                        <span class="status-badge status-rejected"><i class="fas fa-times-circle"></i> REJECTED</span>
                    @endif
                </div>

                <nav class="sidebar-menu">
                    <button class="sidebar-menu-btn active" onclick="switchTab('informasi-umum', this)">
                        <i class="fas fa-user-circle"></i> Informasi Umum
                    </button>
                    <button class="sidebar-menu-btn" onclick="switchTab('kta-digital', this)">
                        <i class="fas fa-id-card"></i> KTA Digital
                    </button>
                    <button class="sidebar-menu-btn" onclick="switchTab('profil-perusahaan', this)">
                        <i class="fas fa-building"></i> Profil Perusahaan
                    </button>
                    <a href="{{ route('anggota.katalog.index') }}" class="sidebar-menu-btn">
                        <i class="fas fa-store"></i> Katalog Saya
                    </a>
                    <button class="sidebar-menu-btn" onclick="switchTab('keamanan', this)">
                        <i class="fas fa-lock"></i> Ubah Password
                    </button>
                    
                    <form action="{{ route('anggota.logout') }}" method="POST" style="margin-top: 15px; width: 100%;">
                        @csrf
                        <button type="submit" class="sidebar-menu-btn" style="color: #EF4444; border: 1px solid rgba(239, 68, 68, 0.2);">
                            <i class="fas fa-sign-out-alt"></i> Keluar / Logout
                        </button>
                    </form>
                </nav>
            </aside>

            <!-- MAIN CONTENT -->
            <main class="dashboard-content">
                
                <!-- ==============================================
                TAB: INFORMASI UMUM
                ============================================== -->
                <div id="informasi-umum" class="content-section active">
                    <h2 class="section-title"><i class="fas fa-user"></i> Data Pribadi Anggota</h2>
                    
                    <div class="info-grid">
                        <div class="info-card">
                            <div class="info-card-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="info-card-content">
                                <span class="info-card-label">Nama Lengkap</span>
                                <span class="info-card-value">{{ $anggota->nama_lengkap }}</span>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="info-card-icon">
                                <i class="fas fa-id-card-alt"></i>
                            </div>
                            <div class="info-card-content">
                                <span class="info-card-label">Nomor Registrasi Pokok (NRP)</span>
                                <span class="info-card-value">{{ $anggota->nrp }}</span>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="info-card-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div class="info-card-content">
                                <span class="info-card-label">Angkatan</span>
                                <span class="info-card-value">Angkatan {{ $anggota->angkatan }}</span>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="info-card-icon">
                                <i class="fas fa-venus-mars"></i>
                            </div>
                            <div class="info-card-content">
                                <span class="info-card-label">Jenis Kelamin</span>
                                <span class="info-card-value" style="text-transform: capitalize;">{{ $anggota->gender }}</span>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="info-card-icon">
                                <i class="fas fa-birthday-cake"></i>
                            </div>
                            <div class="info-card-content">
                                <span class="info-card-label">Tempat & Tanggal Lahir</span>
                                <span class="info-card-value">{{ $anggota->tempat_lahir_personal }}, {{ $anggota->tanggal_lahir ? $anggota->tanggal_lahir->format('d F Y') : '-' }}</span>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="info-card-icon">
                                <i class="fas fa-praying-hands"></i>
                            </div>
                            <div class="info-card-content">
                                <span class="info-card-label">Agama</span>
                                <span class="info-card-value">{{ $anggota->agama }}</span>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="info-card-icon">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div class="info-card-content">
                                <span class="info-card-label">No. Telepon / WhatsApp</span>
                                <span class="info-card-value">{{ $anggota->no_telp }}</span>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="info-card-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="info-card-content">
                                <span class="info-card-label">Email</span>
                                <span class="info-card-value">{{ $anggota->email }}</span>
                            </div>
                        </div>

                        <div class="info-card" style="grid-column: span 2;">
                            <div class="info-card-icon">
                                <i class="fas fa-map-marked-alt"></i>
                            </div>
                            <div class="info-card-content">
                                <span class="info-card-label">Alamat Domisili</span>
                                <span class="info-card-value">{{ $anggota->alamat_domisili }} (Kode Pos: {{ $anggota->kode_pos }})</span>
                            </div>
                        </div>
                        
                        <div class="info-card">
                            <div class="info-card-icon">
                                <i class="fas fa-fingerprint"></i>
                            </div>
                            <div class="info-card-content">
                                <span class="info-card-label">Nomor KTP</span>
                                <span class="info-card-value">{{ $anggota->no_ktp }}</span>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="info-card-icon">
                                <i class="fas fa-award"></i>
                            </div>
                            <div class="info-card-content">
                                <span class="info-card-label">Referensi Anggota CAAIP</span>
                                <span class="info-card-value">{{ $anggota->ref_hipmi }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ==============================================
                TAB: KTA DIGITAL
                ============================================== -->
                <div id="kta-digital" class="content-section">
                    <h2 class="section-title"><i class="fas fa-id-card"></i> Kartu Tanda Anggota Digital</h2>
                    
                    <p style="color: var(--text-grey); font-size: 0.95rem; margin-bottom: 20px;">
                        Berikut adalah Kartu Tanda Anggota Digital resmi Anda. Silakan unduh atau cetak KTA untuk digunakan sebagai bukti tanda keanggotaan.
                    </p>

                    <div class="kta-card-wrapper">
                        <!-- Front Side KTA -->
                        <div class="kta-card" id="ktaCardFront">
                            <div class="kta-header-front">
                                <h2>CORPS ALUMNI</h2>
                                <p>AKADEMI ILMU PELAYARAN</p>
                            </div>
                            <div class="kta-gold-bar"></div>
                            
                            <div class="kta-front-curve"></div>
                            
                            <div class="kta-logo-front">
                                <img src="{{ asset('assets/img/logo.png') }}" alt="CAAIP" onerror="this.src='https://ui-avatars.com/api/?name=CAAIP&background=090b62&color=fff'">
                            </div>

                            <div class="kta-member-name">{{ $anggota->nama_lengkap }}</div>
                            <div class="kta-member-nrp">{{ $anggota->nrp }}</div>
                            
                            <div class="kta-anchor-divider">
                                <i class="fas fa-anchor"></i>
                            </div>

                            <div class="kta-details-container">
                                <div class="kta-photo-wrapper">
                                    @if($anggota->foto_diri)
                                        <img src="{{ Storage::url($anggota->foto_diri) }}" alt="{{ $anggota->nama_lengkap }}">
                                    @else
                                        <i class="fas fa-user text-muted" style="font-size: 2rem;"></i>
                                    @endif
                                </div>
                                
                                <div class="kta-details-list">
                                    <div class="kta-detail-row">
                                        <div class="kta-detail-label">TANGGAL LAHIR</div>
                                        <div class="kta-detail-value">: {{ $anggota->tanggal_lahir ? $anggota->tanggal_lahir->format('d F Y') : '-' }}</div>
                                    </div>
                                    <div class="kta-detail-row">
                                        <div class="kta-detail-label">ANGKATAN</div>
                                        <div class="kta-detail-value">: {{ $anggota->angkatan }}</div>
                                    </div>
                                    <div class="kta-detail-row">
                                        <div class="kta-detail-label">NRP</div>
                                        <div class="kta-detail-value">: {{ $anggota->nrp }}</div>
                                    </div>
                                    <div class="kta-detail-row">
                                        <div class="kta-detail-label">STATUS</div>
                                        <div class="kta-detail-value">: ALUMNI AKTIF</div>
                                    </div>
                                </div>
                            </div>

                            <div class="kta-bottom-row">
                                <div class="kta-qr-code">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode(route('detail-buku', $anggota->id)) }}" alt="QR Code" style="width: 100%; height: 100%;" />
                                </div>
                                <div class="kta-signature">
                                    <img src="{{ asset('assets-front/images/signature_dummy.png') }}" alt="Signature" class="kta-signature-img" onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/f/f6/Signature_of_John_Hancock.svg'; this.style.opacity='0.5';">
                                    <p class="kta-signature-text">CORPS ALUMNI AIP</p>
                                    <p class="kta-signature-subtext">SEKRETARIS JENDERAL</p>
                                </div>
                            </div>
                            <div class="kta-bottom-bar"></div>
                        </div>

                        <!-- Back Side KTA -->
                        <div class="kta-card kta-card-back" id="ktaCardBack">
                            <div class="kta-header-front">
                                <h2>KARTU ANGGOTA ALUMNI</h2>
                            </div>
                            <div class="kta-gold-bar"></div>

                            <div class="kta-back-content">
                                <img src="{{ asset('assets/img/logo.png') }}" class="kta-watermark" alt="Watermark" onerror="this.src=''">
                                
                                <div class="kta-back-ketentuan-title">Ketentuan:</div>
                                <ol class="kta-back-ketentuan-list">
                                    <li>Kartu ini merupakan tanda pengenal resmi anggota Corps Alumni Akademi Ilmu Pelayaran.</li>
                                    <li>Kartu ini tidak dapat dipindahtangankan.</li>
                                    <li>Harap membawa kartu ini saat menghadiri kegiatan alumni.</li>
                                    <li>Apabila kartu ini hilang, segera laporkan ke sekretariat alumni.</li>
                                </ol>
                            </div>

                            <div class="kta-back-footer">
                                <div class="kta-back-footer-curve"></div>
                                <div class="kta-back-footer-content">
                                    <h3 class="kta-back-footer-title">CORPS ALUMNI</h3>
                                    <h4 class="kta-back-footer-subtitle">AKADEMI ILMU PELAYARAN</h4>
                                    
                                    <div class="kta-back-address">
                                        Jl. Raya Boulevard Barat No. 1, Kelapa<br>Gading, Jakarta Utara 14240
                                    </div>
                                    
                                    <div class="kta-back-contact">
                                        <span><i class="fas fa-globe"></i> www.caaip.or.id</span>
                                        <span><i class="fas fa-envelope"></i> sekretariat@caaip.or.id</span>
                                    </div>
                                    
                                    <div class="kta-back-validity">
                                        KARTU INI BERLAKU SELAMA ANGGOTA<br>TERDAFTAR SEBAGAI ALUMNI AKTIF
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="text-align: center; margin-top: 25px;">
                        <button type="button" class="btn-submit" onclick="printKTA()" style="background-color: var(--primary-blue);">
                            <i class="fas fa-print"></i> Cetak Kartu Anggota (Print / PDF)
                        </button>
                    </div>
                </div>

                <!-- ==============================================
                TAB: PROFIL PERUSAHAAN (LOCKED IF PENDING)
                ============================================== -->
                <div id="profil-perusahaan" class="content-section">
                    <h2 class="section-title"><i class="fas fa-building"></i> Profil & Dokumen Perusahaan</h2>

                    @if($anggota->status !== 'approved')
                        <!-- LOCKED STATE -->
                        <div class="locked-state-box">
                            <div class="locked-icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <h3 class="locked-title">Fitur Belum Terbuka</h3>
                            <p class="locked-desc">
                                Sesuai ketentuan, penginputan profil perusahaan dan dokumen legalitas usaha hanya dapat dilakukan **setelah pendaftaran keanggotaan Anda disetujui (di-ACC)** oleh pihak administrator.
                            </p>
                            <div style="margin-top: 20px; font-size: 0.85rem; color: var(--text-grey);">
                                Status Pendaftaran Saat Ini: <strong style="color: #D97706; text-transform: uppercase;">{{ $anggota->status }}</strong>
                            </div>
                        </div>
                    @else
                        <!-- UNLOCKED FORM STATE -->
                        <form action="{{ route('profile-anggota.update-profile') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="form-grid">
                                <div class="form-section-divider" style="margin-top: 0;">1. Profil Usaha / Perusahaan</div>
                                
                                <div class="form-group">
                                    <label for="nama_perusahaan">Nama Perusahaan / Bisnis <span style="color:red;">*</span></label>
                                    <input type="text" name="nama_perusahaan" id="nama_perusahaan" class="form-control" value="{{ old('nama_perusahaan', $anggota->nama_perusahaan) }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="trade_mark">Trade Mark / Merek Usaha <span style="color:red;">*</span></label>
                                    <input type="text" name="trade_mark" id="trade_mark" class="form-control" value="{{ old('trade_mark', $anggota->trade_mark) }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="tanggal_lahir">Tanggal Berdiri Perusahaan</label>
                                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $anggota->tanggal_lahir ? $anggota->tanggal_lahir->format('Y-m-d') : '') }}">
                                </div>

                                <div class="form-group">
                                    <label for="telepon_wa_perusahaan">Nomor Telepon Perusahaan <span style="color:red;">*</span></label>
                                    <input type="text" name="telepon_wa_perusahaan" id="telepon_wa_perusahaan" class="form-control" value="{{ old('telepon_wa_perusahaan', $anggota->telepon_wa_perusahaan) }}" required>
                                </div>

                                <div class="form-group" style="grid-column: span 2;">
                                    <label for="alamat_kantor">Alamat Lengkap Kantor <span style="color:red;">*</span></label>
                                    <textarea name="alamat_kantor" id="alamat_kantor" class="form-control" rows="3" required>{{ old('alamat_kantor', $anggota->alamat_kantor) }}</textarea>
                                </div>

                                <div class="form-section-divider">2. Profil Pimpinan Perusahaan</div>

                                <div class="form-group">
                                    <label for="nama_pimpinan">Nama Pimpinan <span style="color:red;">*</span></label>
                                    <input type="text" name="nama_pimpinan" id="nama_pimpinan" class="form-control" value="{{ old('nama_pimpinan', $anggota->nama_pimpinan) }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="email_pimpinan">Email Pimpinan</label>
                                    <input type="email" name="email_pimpinan" id="email_pimpinan" class="form-control" value="{{ old('email_pimpinan', $anggota->email_pimpinan) }}">
                                </div>

                                <div class="form-group">
                                    <label for="telepon_wa_pimpinan">WhatsApp Pimpinan <span style="color:red;">*</span></label>
                                    <input type="text" name="telepon_wa_pimpinan" id="telepon_wa_pimpinan" class="form-control" value="{{ old('telepon_wa_pimpinan', $anggota->telepon_wa_pimpinan) }}" required>
                                </div>

                                <div class="form-group" style="grid-column: span 2;">
                                    <label for="alamat_pimpinan">Alamat Pimpinan <span style="color:red;">*</span></label>
                                    <textarea name="alamat_pimpinan" id="alamat_pimpinan" class="form-control" rows="2" required>{{ old('alamat_pimpinan', $anggota->alamat_pimpinan) }}</textarea>
                                </div>

                                <div class="form-section-divider">3. Dokumen Legalitas Usaha</div>

                                <div class="form-group">
                                    <label for="akte_notaris">Nomor Akte Notaris</label>
                                    <input type="text" name="akte_notaris" id="akte_notaris" class="form-control" value="{{ old('akte_notaris', $anggota->akte_notaris) }}">
                                </div>

                                <div class="form-group">
                                    <label for="nomor_induk_berusaha_tdup">Nomor NIB / TDUP</label>
                                    <input type="text" name="nomor_induk_berusaha_tdup" id="nomor_induk_berusaha_tdup" class="form-control" value="{{ old('nomor_induk_berusaha_tdup', $anggota->nomor_induk_berusaha_tdup) }}">
                                </div>

                                <div class="form-group">
                                    <label for="npwp_perusahaan">Nomor NPWP Perusahaan</label>
                                    <input type="text" name="npwp_perusahaan" id="npwp_perusahaan" class="form-control" value="{{ old('npwp_perusahaan', $anggota->npwp_perusahaan) }}">
                                </div>
                            </div>

                            <div style="margin-top: 25px;">
                                <h4 style="font-family:'Montserrat',sans-serif; font-size: 0.95rem; font-weight:700; color:var(--text-dark); margin-bottom: 12px;">Upload File Legalitas (Opsional / Perbarui)</h4>
                                
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="surat_permohonan">Surat Permohonan (.PDF, maks 5MB)</label>
                                        <input type="file" name="surat_permohonan" id="surat_permohonan" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="akte_pendirian_perusahaan">Akte Pendirian (.PDF, maks 5MB)</label>
                                        <input type="file" name="akte_pendirian_perusahaan" id="akte_pendirian_perusahaan" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="nib_atau_tdup">NIB / TDUP (.PDF, maks 5MB)</label>
                                        <input type="file" name="nib_atau_tdup" id="nib_atau_tdup" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="ktp_pimpinan">KTP Pimpinan (.JPG/.PNG, maks 5MB)</label>
                                        <input type="file" name="ktp_pimpinan" id="ktp_pimpinan" class="form-control">
                                    </div>
                                    <div class="form-group" style="grid-column: span 2;">
                                        <label for="npwp_perusahaan_file">NPWP Perusahaan (.PDF, maks 5MB)</label>
                                        <input type="file" name="npwp_perusahaan_file" id="npwp_perusahaan_file" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <!-- Display Existing Documents -->
                            <div style="margin-top: 30px; margin-bottom: 30px;">
                                <h4 style="font-family:'Montserrat',sans-serif; font-size: 0.95rem; font-weight:700; color:var(--text-dark); margin-bottom: 15px;">Dokumen Terunggah Saat Ini:</h4>
                                
                                <div class="file-list">
                                    @php
                                        $docs = [
                                            ['label' => 'Surat Permohonan', 'field' => 'surat_permohonan'],
                                            ['label' => 'Akte Pendirian Perusahaan', 'field' => 'akte_pendirian_perusahaan'],
                                            ['label' => 'NIB / TDUP', 'field' => 'nib_atau_tdup'],
                                            ['label' => 'KTP Pimpinan', 'field' => 'ktp_pimpinan'],
                                            ['label' => 'NPWP Perusahaan', 'field' => 'npwp_perusahaan_file'],
                                        ];
                                    @endphp

                                    @foreach($docs as $doc)
                                        <div class="file-upload-item">
                                            <div class="file-upload-info">
                                                <h5>{{ $doc['label'] }}</h5>
                                                @if($anggota->{$doc['field']})
                                                    <p style="color: #059669; font-weight:600;"><i class="fas fa-check-circle"></i> File sudah terunggah</p>
                                                @else
                                                    <p style="color: #9CA3AF; font-style:italic;"><i class="fas fa-times-circle"></i> Belum ada file</p>
                                                @endif
                                            </div>
                                            @if($anggota->{$doc['field']})
                                                <a href="{{ Storage::url($anggota->{$doc['field']}) }}" class="file-upload-link" target="_blank">
                                                    <i class="fas fa-external-link-alt"></i> Lihat File
                                                </a>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <button type="submit" class="btn-submit">
                                <i class="fas fa-save"></i> Simpan Perubahan Profil Perusahaan
                            </button>
                        </form>
                    @endif
                </div>

                <!-- ==============================================
                TAB: KEAMANAN (CHANGE PASSWORD)
                ============================================== -->
                <div id="keamanan" class="content-section">
                    <h2 class="section-title"><i class="fas fa-lock"></i> Pengaturan Keamanan Akun</h2>
                    
                    <p style="color: var(--text-grey); font-size: 0.95rem; margin-bottom: 25px;">
                        Gunakan formulir di bawah ini jika Anda ingin memperbarui password login akun anggota Anda.
                    </p>

                    <form action="{{ route('profile-anggota.change-password') }}" method="POST" style="max-width: 500px;">
                        @csrf
                        
                        <div class="form-group" style="margin-bottom: 18px;">
                            <label for="current_password">Password Saat Ini / Sementara <span style="color:red;">*</span></label>
                            <input type="password" name="current_password" id="current_password" class="form-control" required placeholder="Masukkan password saat ini">
                        </div>

                        <div class="form-group" style="margin-bottom: 18px;">
                            <label for="new_password">Password Baru <span style="color:red;">*</span></label>
                            <input type="password" name="new_password" id="new_password" class="form-control" required placeholder="Minimal 8 karakter">
                        </div>

                        <div class="form-group" style="margin-bottom: 25px;">
                            <label for="new_password_confirmation">Konfirmasi Password Baru <span style="color:red;">*</span></label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required placeholder="Ulangi password baru">
                        </div>

                        <button type="submit" class="btn-submit">
                            <i class="fas fa-key"></i> Perbarui Password
                        </button>
                    </form>
                </div>

            </main>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function switchTab(tabId, element) {
            // Hide all sections
            document.querySelectorAll('.content-section').forEach(section => {
                section.classList.remove('active');
            });

            // Deactivate all sidebar buttons
            document.querySelectorAll('.sidebar-menu-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // Show selected tab
            const targetSection = document.getElementById(tabId);
            if (targetSection) {
                targetSection.classList.add('active');
            }

            // Set active class to the clicked button
            if (element) {
                element.classList.add('active');
            }
        }

        function printKTA() {
            const cardHtml = document.getElementById('ktaCardToPrint').outerHTML;
            
            // Get original page styles
            let stylesHtml = '';
            document.querySelectorAll('style, link[rel="stylesheet"]').forEach(el => {
                stylesHtml += el.outerHTML;
            });

            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Cetak KTA - CAAIP</title>
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
                            .kta-card {
                                box-shadow: none !important;
                                transform: scale(1.2);
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
                        ${cardHtml}
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
