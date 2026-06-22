@extends('layouts.app')

@section('title', 'E-Katalog Perusahaan Saya')

@push('styles')
    <style>
        :root {
            --primary-blue: #0B1354;
            --secondary-blue: #18227C;
            --accent-gold: #C59217;
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

        /* SIDEBAR STYLING */
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
            border: 3px solid var(--accent-gold);
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
            color: var(--accent-gold) !important;
            border: 1px solid rgba(197, 146, 23, 0.2) !important;
        }

        .status-approved {
            background-color: rgba(197, 146, 23, 0.08) !important;
            color: var(--accent-gold) !important;
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
            border-left: 4px solid var(--accent-gold);
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        .sidebar-menu-btn i {
            font-size: 1rem;
            width: 18px;
            text-align: center;
            opacity: 0.9;
        }

        .dashboard-content {
            background: #ffffff;
            border-radius: 14px;
            padding: 35px 40px;
            box-shadow: 0 10px 30px rgba(11, 19, 84, 0.03);
            border: 1px solid rgba(11, 19, 84, 0.06);
            min-height: 550px;
        }

        .section-header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid var(--accent-gold);
            padding-bottom: 18px;
            margin-bottom: 30px;
        }

        .section-title {
            font-family: 'Google Sans', sans-serif;
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-title i {
            color: var(--primary-blue);
            font-size: 1.3rem;
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

        .alert-box.alert-success {
            background-color: #D1FAE5;
            color: #065F46;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .alert-box.alert-danger {
            background-color: #FEE2E2;
            color: #991B1B;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .alert-box.alert-warning {
            background-color: #FEF3C7;
            color: #92400e;
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

                /* SUMMARY CARDS */
        .summary-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .summary-card {
            background: #ffffff;
            padding: 20px;
            border-radius: 12px;
            border: 1px solid rgba(9, 11, 98, 0.08);
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all 0.3s ease;
        }

        .summary-card:hover {
            border-color: var(--primary-blue);
            box-shadow: 0 6px 20px rgba(9, 11, 98, 0.04);
            transform: translateY(-2px);
        }

        .summary-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
            background: rgba(9, 11, 98, 0.05) !important;
            color: var(--primary-blue) !important;
            border: 1px solid rgba(9, 11, 98, 0.02);
        }

        .summary-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .summary-value {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--text-dark);
            line-height: 1;
        }

        .summary-label {
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--text-grey);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* FILTER & SEARCH */
        .filter-section {
            background: #ffffff;
            padding: 16px 20px;
            border-radius: 12px;
            border: 1px solid rgba(9, 11, 98, 0.08);
            margin-bottom: 30px;
            display: flex;
            gap: 20px;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .filter-tabs {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .filter-tab {
            padding: 8px 16px;
            border: 1px solid rgba(9, 11, 98, 0.1);
            background: white;
            color: var(--text-grey);
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s ease;
        }

        .filter-tab:hover {
            border-color: var(--primary-blue);
            color: var(--primary-blue);
            background: rgba(9, 11, 98, 0.02);
        }

        .filter-tab.active {
            background: var(--primary-blue);
            color: white;
            border-color: var(--primary-blue);
        }

        .search-box {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 16px;
            border: 1px solid rgba(9, 11, 98, 0.12);
            border-radius: 8px;
            background: white;
            width: 300px;
            transition: all 0.3s ease;
        }

        .search-box:focus-within {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(9, 11, 98, 0.05);
        }

        .search-box i {
            color: #9CA3AF;
        }

        .search-box input {
            border: none;
            outline: none;
            font-size: 0.88rem;
            color: var(--text-dark);
            font-family: inherit;
            font-weight: 500;
            flex: 1;
            background: transparent;
        }

        /* KATALOG CARD */
        .katalog-card {
            background: #ffffff;
            border-radius: 12px;
            margin-bottom: 25px;
            border: 1px solid rgba(9, 11, 98, 0.08);
            box-shadow: 0 4px 15px rgba(9, 11, 98, 0.01);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .katalog-card:hover {
            border-color: rgba(9, 11, 98, 0.2);
            box-shadow: 0 8px 24px rgba(9, 11, 98, 0.04);
        }

        .katalog-header {
            padding: 24px 30px;
            border-bottom: 1px solid rgba(9, 11, 98, 0.06);
            background: rgba(9, 11, 98, 0.01);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .katalog-header-title {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .katalog-logo {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid rgba(9, 11, 98, 0.08);
        }

        .katalog-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .katalog-info h2 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin: 0;
            letter-spacing: -0.2px;
        }

        .katalog-info-subtitle {
            font-size: 0.88rem;
            color: var(--text-grey);
            font-weight: 500;
            margin: 0;
        }

        .creator-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #e0e7ff;
            color: #3730a3;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            width: fit-content;
            margin-top: 4px;
        }

        .katalog-status-badges {
            display: flex;
            gap: 8px;
        }

        .status-badge-pill {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-badge-pill.pending { background-color: rgba(197, 146, 23, 0.08) !important; color: var(--accent-gold) !important; border: 1px solid rgba(197, 146, 23, 0.18) !important; }
        .status-badge-pill.approved { background-color: rgba(11, 19, 84, 0.06) !important; color: var(--primary-blue) !important; border: 1px solid rgba(11, 19, 84, 0.12) !important; }
        .status-badge-pill.rejected { background-color: rgba(214, 11, 28, 0.08) !important; color: var(--accent-red) !important; border: 1px solid rgba(214, 11, 28, 0.18) !important; }
        .status-badge-pill.active { background-color: rgba(11, 19, 84, 0.04) !important; color: var(--primary-blue) !important; border: 1px solid rgba(11, 19, 84, 0.08) !important; }
        .status-badge-pill.inactive { background-color: #F3F4F6 !important; color: #4B5563 !important; border: 1px solid rgba(75, 85, 99, 0.15) !important; }

        .katalog-body {
            padding: 24px 30px;
            background: #ffffff;
        }

        .btn-toggle-detail {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 18px;
            background: #ffffff;
            color: var(--primary-blue);
            border: 1px solid rgba(9, 11, 98, 0.15);
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s ease;
        }

        .btn-toggle-detail:hover {
            background: var(--primary-blue);
            color: white;
            border-color: var(--primary-blue);
        }

        .btn-toggle-detail i {
            transition: transform 0.3s ease;
        }

        .btn-toggle-detail.expanded i {
            transform: rotate(180deg);
        }

        .rejection-box {
            background: #FEE2E2;
            border: 1px solid rgba(239, 68, 68, 0.15);
            border-radius: 8px;
            padding: 16px 20px;
            margin-bottom: 20px;
        }

        .rejection-box-title {
            font-weight: 700;
            color: #DC2626;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .rejection-box-content {
            font-size: 0.88rem;
            color: #991B1B;
            line-height: 1.5;
            font-weight: 500;
        }

        .detail-section {
            margin-bottom: 25px;
        }

        .detail-section:last-child {
            margin-bottom: 0;
        }

        .detail-section-title {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--primary-blue);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 1.5px solid rgba(9, 11, 98, 0.06);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-description {
            padding: 18px;
            background: rgba(9, 11, 98, 0.01);
            border-radius: 8px;
            border-left: 3px solid var(--primary-blue);
        }

        .info-description p {
            font-size: 0.88rem;
            color: #4b5563;
            line-height: 1.6;
            margin: 0;
            font-weight: 500;
        }

        .images-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 12px;
        }

        .image-item {
            aspect-ratio: 16/9;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid rgba(9, 11, 98, 0.08);
            transition: all 0.3s ease;
        }

        .image-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .image-item:hover img {
            transform: scale(1.05);
        }

        .map-container {
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid rgba(9, 11, 98, 0.08);
        }

        .map-container iframe {
            width: 100%;
            height: 300px;
            border: none;
            display: block;
        }

        .katalog-actions {
            display: flex;
            gap: 10px;
            padding: 18px 30px;
            border-top: 1px solid rgba(9, 11, 98, 0.06);
            background: rgba(9, 11, 98, 0.01);
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.25s ease;
            text-decoration: none;
            border: none;
        }

        .btn-action.secondary {
            background: #ffffff;
            color: var(--primary-blue);
            border: 1px solid rgba(9, 11, 98, 0.15);
        }

        .btn-action.secondary:hover {
            background: var(--primary-blue);
            color: #ffffff;
            border-color: var(--primary-blue);
            box-shadow: 0 4px 12px rgba(9, 11, 98, 0.1);
        }

        .btn-action.danger {
            background: rgba(214, 11, 28, 0.06) !important;
            color: var(--accent-red) !important;
            border: 1px solid rgba(214, 11, 28, 0.15) !important;
        }

        .btn-action.danger:hover {
            background: var(--accent-red) !important;
            color: #ffffff !important;
            border-color: var(--accent-red) !important;
            box-shadow: 0 4px 12px rgba(214, 11, 28, 0.15) !important;
        }

        .btn-submit-main {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            background: var(--primary-blue) !important;
            color: #ffffff !important;
            border: 1px solid var(--primary-blue) !important;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 10px rgba(9, 11, 98, 0.08);
        }

        .btn-submit-main:hover {
            background: var(--secondary-blue) !important;
            border-color: var(--secondary-blue) !important;
            box-shadow: 0 4px 15px rgba(9, 11, 98, 0.15);
            transform: translateY(-1px);
        }

        /* EMPTY STATE */
        .empty-state {
            text-align: center;
            padding: 60px 40px;
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid rgba(9, 11, 98, 0.08);
        }

        .empty-state i {
            font-size: 3rem;
            color: #D1D5DB;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0 0 8px 0;
        }

        .empty-state p {
            font-size: 0.9rem;
            color: var(--text-grey);
            margin: 0 0 20px 0;
            font-weight: 500;
        }

        /* Info Grid inside collapsed catalog */
        .katalog-detail-content .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .katalog-detail-content .info-card {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 14px 18px;
            background: #ffffff;
            border: 1px solid rgba(9, 11, 98, 0.06);
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(9, 11, 98, 0.005);
        }

        .katalog-detail-content .info-card-icon {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: rgba(9, 11, 98, 0.03);
            color: var(--primary-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .katalog-detail-content .info-card-content {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .katalog-detail-content .info-card-label {
            font-size: 0.68rem;
            font-weight: 700;
            color: var(--text-grey);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .katalog-detail-content .info-card-value {
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        @media (max-width: 991px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
            .summary-cards {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .summary-cards {
                grid-template-columns: 1fr;
            }
            .filter-section {
                flex-direction: column;
                align-items: stretch;
            }
            .search-box {
                width: 100%;
            }
            .katalog-detail-content .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-container">
        <!-- ALERTS -->
        @if(session('success'))
            <div class="alert-box alert-success">
                <i class="fas fa-check-circle" style="margin-top: 2px; font-size: 1.1rem;"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert-box alert-danger">
                <i class="fas fa-exclamation-circle" style="margin-top: 2px; font-size: 1.1rem;"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        @if(session('info'))
            <div class="alert-box alert-warning" style="background-color: #E0F2FE; color: #0369A1; border: 1px solid rgba(2, 132, 199, 0.2);">
                <i class="fas fa-info-circle" style="margin-top: 2px; font-size: 1.1rem;"></i>
                <div>{{ session('info') }}</div>
            </div>
        @endif

        @if($anggota->status !== 'approved')
            <div class="alert-box alert-warning">
                <i class="fas fa-exclamation-triangle" style="margin-top: 2px; font-size: 1.1rem;"></i>
                <div>Akun Anda belum diverifikasi. Hanya anggota terverifikasi yang dapat menambah atau mengedit katalog perusahaan.</div>
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
                    <a href="{{ route('profile-anggota') }}#informasi-umum" class="sidebar-menu-btn">
                        <i class="fas fa-user-circle"></i> Informasi Umum
                    </a>
                    <a href="{{ route('profile-anggota') }}#kta-digital" class="sidebar-menu-btn">
                        <i class="fas fa-id-card"></i> KTA Digital
                    </a>
                    <a href="{{ route('profile-anggota') }}#profil-perusahaan" class="sidebar-menu-btn">
                        <i class="fas fa-building"></i> Profil Perusahaan
                    </a>
                    <a href="{{ route('anggota.katalog.index') }}" class="sidebar-menu-btn active">
                        <i class="fas fa-store"></i> Katalog Saya
                    </a>
                    <a href="{{ route('profile-anggota') }}#keamanan" class="sidebar-menu-btn">
                        <i class="fas fa-lock"></i> Ubah Password
                    </a>
                    
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
                <!-- SECTION HEADER -->
                <div class="section-header-row">
                    <h2 class="section-title"><i class="fas fa-store"></i> Katalog Perusahaan Saya</h2>
                    @if($anggota->status === 'approved')
                        <a href="{{ route('anggota.katalog.create') }}" class="btn-submit-main">
                            <i class="fas fa-plus"></i> Tambah Katalog Baru
                        </a>
                    @endif
                </div>

                <!-- SUMMARY CARDS (jika ada katalog) -->
                @if($katalogs->count() > 0)
                    <div class="summary-cards">
                        <div class="summary-card">
                            <div class="summary-icon">
                                <i class="fas fa-archive"></i>
                            </div>
                            <div class="summary-info">
                                <span class="summary-value">{{ $katalogs->count() }}</span>
                                <span class="summary-label">Total</span>
                            </div>
                        </div>

                        <div class="summary-card">
                            <div class="summary-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="summary-info">
                                <span class="summary-value">{{ $katalogs->where('status', 'approved')->count() }}</span>
                                <span class="summary-label">Disetujui</span>
                            </div>
                        </div>

                        <div class="summary-card">
                            <div class="summary-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="summary-info">
                                <span class="summary-value">{{ $katalogs->where('status', 'pending')->count() }}</span>
                                <span class="summary-label">Pending</span>
                            </div>
                        </div>

                        <div class="summary-card">
                            <div class="summary-icon">
                                <i class="fas fa-eye"></i>
                            </div>
                            <div class="summary-info">
                                <span class="summary-value">{{ $katalogs->where('status', 'approved')->where('is_active', true)->count() }}</span>
                                <span class="summary-label">Aktif</span>
                            </div>
                        </div>
                    </div>

                    <!-- FILTER & SEARCH -->
                    <div class="filter-section">
                        <div class="filter-tabs">
                            <button class="filter-tab active" onclick="filterKatalog('all', this)">
                                Semua ({{ $katalogs->count() }})
                            </button>
                            <button class="filter-tab" onclick="filterKatalog('approved', this)">
                                Disetujui ({{ $katalogs->where('status', 'approved')->count() }})
                            </button>
                            <button class="filter-tab" onclick="filterKatalog('pending', this)">
                                Pending ({{ $katalogs->where('status', 'pending')->count() }})
                            </button>
                            <button class="filter-tab" onclick="filterKatalog('rejected', this)">
                                Ditolak ({{ $katalogs->where('status', 'rejected')->count() }})
                            </button>
                            <button class="filter-tab" onclick="filterKatalog('active', this)">
                                Aktif ({{ $katalogs->where('status', 'approved')->where('is_active', true)->count() }})
                            </button>
                        </div>
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" id="searchKatalog" placeholder="Cari nama perusahaan..." onkeyup="searchKatalog()">
                        </div>
                    </div>
                @endif

                <!-- KATALOG CONTENT -->
                @if($katalogs->count() > 0)
                    <div id="katalogList">
                        @foreach($katalogs as $katalog)
                            <div class="katalog-card" 
                                 data-status="{{ $katalog->status }}" 
                                 data-active="{{ $katalog->is_active ? 'true' : 'false' }}"
                                 data-name="{{ strtolower($katalog->company_name) }}">
                                
                                <!-- CARD HEADER -->
                                <div class="katalog-header">
                                    <div class="katalog-header-title">
                                        <img src="{{ $katalog->logo_url }}" alt="{{ $katalog->company_name }}" class="katalog-logo">
                                        <div class="katalog-info">
                                            <h2>{{ $katalog->company_name }}</h2>
                                            <p class="katalog-info-subtitle">{{ $katalog->business_field }}</p>
                                            @if($katalog->isCreatedByAdmin())
                                                <span class="creator-badge">
                                                    <i class="fas fa-shield-alt"></i> Dibuat oleh Admin
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="katalog-status-badges">
                                        @if($katalog->status === 'pending')
                                            <span class="status-badge-pill pending">Menunggu Review</span>
                                        @elseif($katalog->status === 'approved')
                                            <span class="status-badge-pill approved">Disetujui</span>
                                        @else
                                            <span class="status-badge-pill rejected">Ditolak</span>
                                        @endif
                                        
                                        @if($katalog->status === 'approved')
                                            @if($katalog->is_active)
                                                <span class="status-badge-pill active">Aktif</span>
                                            @else
                                                <span class="status-badge-pill inactive">Nonaktif</span>
                                            @endif
                                        @endif
                                    </div>
                                </div>

                                <!-- CARD BODY (Collapsible) -->
                                <div class="katalog-body">
                                    <!-- Rejection Reason -->
                                    @if($katalog->status === 'rejected' && $katalog->rejection_reason)
                                        <div class="rejection-box">
                                            <div class="rejection-box-title">
                                                <i class="fas fa-exclamation-triangle"></i> Alasan Penolakan
                                            </div>
                                            <div class="rejection-box-content">
                                                {{ $katalog->rejection_reason }}
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Collapsible Content -->
                                    <div class="katalog-detail-content" style="display: none; margin-bottom: 20px;">
                                        <!-- INFORMASI KONTAK -->
                                        <div class="detail-section">
                                            <h3 class="detail-section-title"><i class="fas fa-address-book"></i> Informasi Kontak</h3>
                                            <div class="info-grid">
                                                <div class="info-card">
                                                    <div class="info-card-icon"><i class="fas fa-envelope"></i></div>
                                                    <div class="info-card-content">
                                                        <span class="info-card-label">Email</span>
                                                        <span class="info-card-value">{{ $katalog->email }}</span>
                                                    </div>
                                                </div>
                                                <div class="info-card">
                                                    <div class="info-card-icon"><i class="fas fa-phone"></i></div>
                                                    <div class="info-card-content">
                                                        <span class="info-card-label">Telepon</span>
                                                        <span class="info-card-value">{{ $katalog->phone }}</span>
                                                    </div>
                                                </div>
                                                <div class="info-card" style="grid-column: span 2;">
                                                    <div class="info-card-icon"><i class="fas fa-map-marker-alt"></i></div>
                                                    <div class="info-card-content">
                                                        <span class="info-card-label">Alamat Kantor</span>
                                                        <span class="info-card-value">{{ $katalog->address }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- DESKRIPSI PERUSAHAAN -->
                                        <div class="detail-section">
                                            <h3 class="detail-section-title"><i class="fas fa-info-circle"></i> Deskripsi Perusahaan</h3>
                                            <div class="info-description">
                                                <p>{{ $katalog->description }}</p>
                                            </div>
                                        </div>

                                        <!-- GALERI FOTO -->
                                        @if($katalog->images && count($katalog->images) > 0)
                                            <div class="detail-section">
                                                <h3 class="detail-section-title"><i class="fas fa-images"></i> Galeri Foto</h3>
                                                <div class="images-grid">
                                                    @foreach($katalog->images_url as $image)
                                                        @if($image)
                                                            <div class="image-item">
                                                                <img src="{{ $image }}" alt="Galeri {{ $katalog->company_name }}" loading="lazy">
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        <!-- LOKASI MAP -->
                                        @if($katalog->map_embed_url)
                                            <div class="detail-section">
                                                <h3 class="detail-section-title"><i class="fas fa-map-marked-alt"></i> Lokasi Kantor</h3>
                                                <div class="map-container">
                                                    <iframe src="{{ $katalog->map_embed_url }}" allowfullscreen="" loading="lazy"></iframe>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Toggle Button -->
                                    <div style="text-align: center;">
                                        <button class="btn-toggle-detail" onclick="toggleDetail(this)">
                                            <i class="fas fa-chevron-down"></i>
                                            <span>Lihat Detail</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- CARD ACTIONS -->
                                @if($anggota->status === 'approved')
                                    <div class="katalog-actions">
                                        <a href="{{ route('anggota.katalog.edit', $katalog->id) }}" class="btn-action secondary">
                                            <i class="fas fa-edit"></i> Edit Data
                                        </a>

                                        @if($katalog->status === 'approved')
                                            <form action="{{ route('anggota.katalog.toggle-status', $katalog->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn-action secondary">
                                                    @if($katalog->is_active)
                                                        <i class="fas fa-eye-slash"></i> Nonaktifkan
                                                    @else
                                                        <i class="fas fa-eye"></i> Aktifkan di Web
                                                    @endif
                                                </button>
                                            </form>
                                        @endif

                                        <form action="{{ route('anggota.katalog.destroy', $katalog->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus katalog {{ $katalog->company_name }}? Data tidak dapat dikembalikan!')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action danger">
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- No Results Message -->
                    <div id="noResults" class="empty-state" style="display: none; margin-top: 24px;">
                        <i class="fas fa-search"></i>
                        <h3>Tidak Ada Hasil</h3>
                        <p>Tidak ada katalog yang sesuai dengan pencarian Anda.</p>
                    </div>
                @else
                    <!-- EMPTY STATE -->
                    <div class="empty-state">
                        <i class="fas fa-store-slash"></i>
                        <h3>Belum Ada Data Katalog</h3>
                        <p>Anda belum menambahkan katalog bisnis/perusahaan Anda.</p>
                        @if($anggota->status === 'approved')
                            <a href="{{ route('anggota.katalog.create') }}" class="btn-submit-main">
                                <i class="fas fa-plus"></i> Buat Katalog Pertama Saya
                            </a>
                        @else
                            <p style="margin-top: 10px; font-size: 0.85rem; color: #DC2626;">
                                <i class="fas fa-lock"></i> Silakan tunggu akun Anda di-ACC oleh Admin untuk mulai menginput katalog.
                            </p>
                        @endif
                    </div>
                @endif
            </main>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Toggle Detail Katalog
        function toggleDetail(button) {
            const katalogCard = button.closest('.katalog-card');
            const detailContent = katalogCard.querySelector('.katalog-detail-content');
            const icon = button.querySelector('i');
            const label = button.querySelector('span');
            
            if (detailContent.style.display === 'none') {
                detailContent.style.display = 'block';
                button.classList.add('expanded');
                icon.className = 'fas fa-chevron-up';
                label.innerText = 'Sembunyikan Detail';
            } else {
                detailContent.style.display = 'none';
                button.classList.remove('expanded');
                icon.className = 'fas fa-chevron-down';
                label.innerText = 'Lihat Detail';
            }
        }

        // Filter Katalog by Status
        function filterKatalog(filter, element) {
            const cards = document.querySelectorAll('.katalog-card');
            const tabs = document.querySelectorAll('.filter-tab');
            const noResults = document.getElementById('noResults');
            let visibleCount = 0;
            
            // Update active tab style
            tabs.forEach(tab => tab.classList.remove('active'));
            element.classList.add('active');
            
            // Filter cards
            cards.forEach(card => {
                const status = card.getAttribute('data-status');
                const isActive = card.getAttribute('data-active') === 'true';
                let shouldShow = false;
                
                if (filter === 'all') {
                    shouldShow = true;
                } else if (filter === 'active') {
                    shouldShow = status === 'approved' && isActive;
                } else {
                    shouldShow = status === filter;
                }
                
                if (shouldShow) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Show/hide no results message
            if (visibleCount === 0) {
                noResults.style.display = 'block';
            } else {
                noResults.style.display = 'none';
            }
        }

        // Search Katalog by Company Name
        function searchKatalog() {
            const searchInput = document.getElementById('searchKatalog');
            const searchTerm = searchInput.value.toLowerCase();
            const cards = document.querySelectorAll('.katalog-card');
            const noResults = document.getElementById('noResults');
            let visibleCount = 0;
            
            cards.forEach(card => {
                const companyName = card.getAttribute('data-name');
                const currentDisplay = card.style.display;
                
                // Only search within currently visible cards (respect filter)
                if (currentDisplay !== 'none' || searchTerm !== '') {
                    if (companyName.includes(searchTerm)) {
                        card.style.display = 'block';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                }
            });
            
            // Show/hide no results message
            if (visibleCount === 0) {
                noResults.style.display = 'block';
            } else {
                noResults.style.display = 'none';
            }
        }

        // Reset search when filter changes
        document.addEventListener('DOMContentLoaded', function() {
            const filterTabs = document.querySelectorAll('.filter-tab');
            filterTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const searchInput = document.getElementById('searchKatalog');
                    if (searchInput) {
                        searchInput.value = '';
                    }
                });
            });
        });
    </script>
@endpush