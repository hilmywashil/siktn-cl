@extends('admin.layouts.admin-layout')

@section('title', 'Dashboard Overview - SIKTN Admin')
@section('page-title', 'Dashboard Executive')

@php
$activeMenu = 'dashboard';
@endphp

@push('styles')
<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<!-- OpenLayers Map CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ol@v7.4.0/ol.css">

<style>
    /* GLOBAL SELECT2 OVERRIDES (Solid & 6px Rounded) */
    .select2-container--default .select2-selection--single {
        height: 38px !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 6px !important;
        padding: 4px 10px !important;
        font-family: 'Montserrat', sans-serif !important;
        font-size: 0.85rem !important;
        background-color: #ffffff !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #022648 !important;
        font-weight: 700 !important;
        line-height: 28px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
    }
    .select2-dropdown {
        border-radius: 6px !important;
        border: 1px solid #cbd5e1 !important;
        box-shadow: 0 4px 15px rgba(2, 38, 72, 0.1) !important;
        font-family: 'Montserrat', sans-serif !important;
        font-size: 0.85rem !important;
        z-index: 99999 !important;
        overflow: hidden !important;
        background: #ffffff !important;
    }
    .select2-container--default .select2-results__option {
        padding: 9px 14px !important;
        font-size: 0.85rem !important;
        font-weight: 600 !important;
        color: #022648 !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected],
    .select2-container--default .select2-results__option[aria-selected="true"] {
        background-color: #022648 !important;
        color: #ffffff !important;
        font-weight: 700 !important;
    }

    /* FULLCALENDAR SCROLLBAR & SLIM EVENT STYLING */
    .fc .fc-scroller {
        overflow: hidden !important;
    }
    .fc-theme-standard td, .fc-theme-standard th {
        border: 1px solid #e2e8f0 !important;
    }
    .fc-event {
        border-radius: 3px !important;
        padding: 1px 4px !important;
        font-size: 0.6875rem !important;
        font-weight: 700 !important;
        border: none !important;
        color: #ffffff !important;
        margin-top: 1px !important;
        min-height: 14px !important;
        line-height: 1.2 !important;
    }
    .fc-event-title {
        display: inline-block !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        max-width: 100% !important;
    }
    .fc-event:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }
    /* When day cell has > 2 events: hide text and make slim 6px colored bar */
    .fc-daygrid-day.has-many-events .fc-event-title,
    .fc-daygrid-day.has-many-events .fc-event-time {
        display: none !important;
    }
    .fc-daygrid-day.has-many-events .fc-event {
        height: 6px !important;
        min-height: 6px !important;
        padding: 0 !important;
        margin-top: 2px !important;
        margin-bottom: 2px !important;
        border-radius: 2px !important;
    }

    .admin-ui-scope {
        --navy: #022648; --navy-dark: #01162f; --navy-light: #0a3a6b;
        --gold: #b7830f; --green: #059669; --blue: #2563eb; --red: #dc2626; --amber: #d97706;
        --gray-50: #f8fafc; --gray-100: #f1f5f9; --gray-200: #e2e8f0; --gray-300: #cbd5e1;
        --gray-500: #64748b; --gray-700: #334155; --gray-900: #0f172a;
        --radius-sm: 4px; --radius-md: 6px; --radius-lg: 6px;
        font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    /* Welcome Header Banner Solid Navy */
    .dashboard-welcome-banner {
        background: #022648;
        border-radius: var(--radius-md); padding: 1.1rem 1.4rem; color: #ffffff;
        display: flex; align-items: center; justify-content: space-between; gap: 1.25rem;
        margin-bottom: 1.25rem; border: 1px solid #01162f; margin-top: 0.25rem;
    }
    .welcome-info { display: flex; align-items: center; gap: 1rem; }
    .welcome-avatar {
        width: 44px; height: 44px; border-radius: 6px; background: #b7830f;
        display: flex; align-items: center; justify-content: center; font-size: 1.1rem;
        font-weight: 800; color: #ffffff; flex-shrink: 0; overflow: hidden;
    }
    .welcome-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .welcome-text h1 { margin: 0; font-size: 1.15rem; font-weight: 800; color: #ffffff; letter-spacing: -0.2px; }
    .welcome-text p { margin: 0.2rem 0 0 0; font-size: 0.8rem; color: #cbd5e1; font-weight: 500; }

    .welcome-role-badge {
        background: #b7830f; color: #ffffff; padding: 0.4rem 0.9rem; border-radius: 4px;
        font-weight: 800; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;
    }

    /* Real IP Visitor Counter Banner */
    .visitor-counter-bar {
        background: #ffffff; border: 1px solid var(--gray-200); border-radius: var(--radius-md);
        padding: 0.75rem 1.1rem; margin-bottom: 1.25rem; display: flex; align-items: center;
        justify-content: space-between; gap: 1rem;
    }
    .visitor-ip-badge {
        background: #f1f5f9; color: var(--navy); padding: 0.25rem 0.65rem; border-radius: 4px;
        font-size: 0.75rem; font-weight: 700; border: 1px solid var(--gray-300);
    }
    .visitor-stat-item { display: flex; align-items: center; gap: 0.5rem; }
    .visitor-stat-item .val {
        font-size: 1.1rem; font-weight: 800; color: var(--navy); font-family: 'Montserrat', sans-serif;
    }
    .visitor-stat-item .lbl {
        font-size: 0.725rem; color: var(--gray-500); font-weight: 700; text-transform: uppercase;
    }

    /* 5 Stat Cards Grid Balanced Alignment */
    .brief-stat-cards {
        display: grid; grid-template-columns: repeat(5, 1fr);
        gap: 1.25rem; margin-bottom: 1.25rem; width: 100%;
    }
    @media (max-width: 1200px) {
        .brief-stat-cards { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 768px) {
        .brief-stat-cards { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 480px) {
        .brief-stat-cards { grid-template-columns: 1fr; }
    }

    .brief-card {
        background: #ffffff; border-radius: var(--radius-md); padding: 1rem 0.9rem;
        border: 1px solid var(--gray-200); display: flex; align-items: center; gap: 0.85rem;
        position: relative; overflow: hidden; min-height: 82px;
    }
    .brief-card::before {
        content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; background: var(--navy);
    }
    .brief-card.card-amber::before { background: var(--amber); }
    .brief-card.card-green::before { background: var(--green); }
    .brief-card.card-blue::before { background: var(--blue); }
    .brief-card.card-purple::before { background: #8b5cf6; }

    .brief-icon {
        width: 38px; height: 38px; border-radius: 6px; display: flex;
        align-items: center; justify-content: center; flex-shrink: 0;
    }
    .brief-info h4 {
        margin: 0; font-size: 0.6875rem; color: var(--gray-500); font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.4px; line-height: 1.2;
    }
    .brief-info .value {
        font-size: 1.2rem; font-weight: 800; color: var(--navy); margin-top: 0.15rem;
        font-family: 'Montserrat', sans-serif; line-height: 1.1;
    }

    /* Top Grid 2 Equal Columns */
    .dashboard-main-grid {
        display: grid; grid-template-columns: 1fr 400px; gap: 1.25rem; margin-bottom: 1.25rem;
        align-items: stretch;
    }
    @media (max-width: 1200px) {
        .dashboard-main-grid { grid-template-columns: 1fr; }
    }

    /* Widget Containers (Equal Heights & 6px Rounded) */
    .widget-box {
        background: #ffffff; border-radius: var(--radius-md); border: 1px solid var(--gray-200);
        margin-bottom: 1.25rem; overflow: hidden;
    }
    .widget-box.equal-height {
        height: 100%; display: flex; flex-direction: column; margin-bottom: 0;
    }
    .widget-box.equal-height .widget-body {
        height: 390px !important;
        max-height: 390px !important;
        overflow-y: auto !important;
    }
    .widget-box.equal-height .widget-body::-webkit-scrollbar {
        width: 4px;
    }
    .widget-box.equal-height .widget-body::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    .widget-header {
        padding: 0.85rem 1.1rem; background: var(--gray-50); border-bottom: 1px solid var(--gray-200);
        display: flex; align-items: center; justify-content: space-between; gap: 1rem;
    }
    .widget-header h3 {
        margin: 0; font-size: 0.9rem; font-weight: 800; color: var(--navy);
        display: flex; align-items: center; gap: 0.6rem;
    }
    .widget-body { padding: 1.1rem; flex: 1; display: flex; flex-direction: column; justify-content: space-between; overflow: hidden; }

    /* REAL OPENLAYERS MAP STYLING */
    #openLayersRealMap {
        width: 100%; height: 400px; border-radius: var(--radius-md); border: 1px solid var(--gray-300);
        position: relative; overflow: hidden;
    }
    .map-controls {
        display: flex; align-items: center; justify-content: space-between; gap: 1rem;
        margin-bottom: 1rem; background: var(--gray-50); padding: 0.6rem 0.9rem; border-radius: var(--radius-md);
        border: 1px solid var(--gray-200); flex-wrap: wrap;
    }
    .map-legend { display: flex; align-items: center; gap: 1.25rem; font-size: 0.8rem; font-weight: 600; }
    .legend-item { display: flex; align-items: center; gap: 0.4rem; }
    .legend-dot { width: 10px; height: 10px; border-radius: 2px; display: inline-block; }

    /* Clean Refined Grid Wilayah */
    .prov-grid-list {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 0.75rem;
        width: 100%; max-height: 240px; overflow-y: auto; padding-right: 0.3rem; margin-top: 1.25rem;
    }
    .prov-item {
        padding: 0.6rem 0.85rem; border-radius: var(--radius-md); border: 1px solid var(--gray-200);
        display: flex; align-items: center; justify-content: space-between; font-size: 0.8125rem; font-weight: 600;
        background: #ffffff; cursor: pointer;
    }
    .prov-item:hover { border-color: var(--navy); }
    
    .status-badge-pill {
        font-size: 0.7rem; font-weight: 700; padding: 2px 6px; border-radius: 4px;
        text-transform: uppercase; letter-spacing: 0.3px;
    }
    .badge-selesai { background: #d1fae5; color: #059669; }
    .badge-caretaker { background: #fef3c7; color: #b7830f; }
    .badge-belum { background: #f1f5f9; color: #64748b; }

    /* Overview Feature Cards Grid (2 Columns) */
    .overview-sections-grid {
        display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.25rem; margin-bottom: 1.25rem;
    }
    @media (max-width: 900px) {
        .overview-sections-grid { grid-template-columns: 1fr; }
    }

    .feature-item-row {
        display: flex; align-items: center; justify-content: space-between; padding: 0.6rem 0;
        border-bottom: 1px solid var(--gray-100); font-size: 0.825rem;
    }
    .feature-item-row:last-child { border-bottom: none; }

    /* Calendar Preview Container */
    #dashboardCalendar { font-size: 0.8rem; width: 100%; height: 100%; min-height: 330px; }
</style>
@endpush

@section('content')
<div class="admin-ui-scope" style="padding-top: 0.25rem;">

    <!-- Welcome Header Banner -->
    <div class="dashboard-welcome-banner">
        <div class="welcome-info">
            <div class="welcome-avatar">
                @if(auth()->guard('admin')->user()->photo)
                    <img src="{{ auth()->guard('admin')->user()->photo_url }}" alt="{{ auth()->guard('admin')->user()->name }}">
                @else
                    {{ strtoupper(substr(auth()->guard('admin')->user()->name, 0, 2)) }}
                @endif
            </div>
            <div class="welcome-text">
                <h1>Selamat Datang Kembali, {{ auth()->guard('admin')->user()->name }}</h1>
                <p>Eksekutif Dashboard Sistem Informasi Karang Taruna Nasional (SIKTN)</p>
            </div>
        </div>
        <div>
            <span class="welcome-role-badge">
                {{ auth()->guard('admin')->user()->role_display_name }}
            </span>
        </div>
    </div>

    <!-- REAL IP VISITOR COUNTER BAR -->
    <div class="visitor-counter-bar">
        <div style="display: flex; align-items: center; gap: 0.6rem;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--navy)" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            <span style="font-weight: 800; font-size: 0.875rem; color: var(--navy);">Statistik Pengunjung Website (Real IP Tracker):</span>
            <span class="visitor-ip-badge">IP: {{ $visitorStats['current_ip'] }}</span>
        </div>
        <div style="display: flex; gap: 1.75rem;">
            <div class="visitor-stat-item">
                <span class="lbl">Hari Ini:</span>
                <span class="val">{{ number_format($visitorStats['today']) }}</span>
            </div>
            <div class="visitor-stat-item">
                <span class="lbl">Bulan Ini:</span>
                <span class="val">{{ number_format($visitorStats['month']) }}</span>
            </div>
            <div class="visitor-stat-item">
                <span class="lbl">Total Kunjungan:</span>
                <span class="val" style="color: var(--green);">{{ number_format($visitorStats['total']) }}</span>
            </div>
        </div>
    </div>

    <!-- 5 BRIEF SUMMARY STAT CARDS -->
    <div class="brief-stat-cards">
        <div class="brief-card">
            <div class="brief-icon" style="background: #e0f2fe; color: #0284c7;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
            </div>
            <div class="brief-info">
                <h4>Total Surat Keluar</h4>
                <div class="value">{{ number_format($totalSuratKeluar) }}</div>
            </div>
        </div>

        <div class="brief-card card-amber">
            <div class="brief-icon" style="background: #fffbe6; color: #d97706;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 16 14"/></svg>
            </div>
            <div class="brief-info">
                <h4>Surat Pending TTD</h4>
                <div class="value" style="color: var(--amber);">{{ number_format($totalSuratPending) }}</div>
            </div>
        </div>

        <div class="brief-card card-green">
            <div class="brief-icon" style="background: #d1fae5; color: #059669;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <div class="brief-info">
                <h4>Temu Karya Selesai</h4>
                <div class="value" style="color: var(--green);">{{ number_format($totalTemuKaryaSelesai) }}</div>
            </div>
        </div>

        <div class="brief-card card-blue">
            <div class="brief-icon" style="background: #eff6ff; color: #2563eb;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
            </div>
            <div class="brief-info">
                <h4>Temu Karya Pending</h4>
                <div class="value" style="color: var(--blue);">{{ number_format($totalTemuKaryaPending) }}</div>
            </div>
        </div>

        <div class="brief-card card-purple">
            <div class="brief-icon" style="background: #f3e8ff; color: #8b5cf6;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/><line x1="8" y1="2" x2="8" y2="18"/><line x1="16" y1="6" x2="16" y2="22"/></svg>
            </div>
            <div class="brief-info">
                <h4>Wilayah Aktif / Belum</h4>
                <div class="value" style="color: #8b5cf6;">{{ $totalWilayahSelesai }} <span style="font-size: 0.8rem; font-weight: 600; color: var(--gray-500);">dari {{ $totalWilayahNasional }} Prov</span></div>
            </div>
        </div>
    </div>

    <!-- TOP GRID 2 COLUMNS: GRAFIK & KALENDER AGENDA (EQUAL HEIGHT - NO GAP) -->
    <div class="dashboard-main-grid">

        <!-- KIRI: GRAFIK TREN SURAT -->
        <div>
            <div class="widget-box equal-height">
                <div class="widget-header">
                    <h3>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--navy)" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                        Grafik Tren Surat Masuk & Surat Keluar ({{ date('Y') }})
                    </h3>
                    <span style="font-size: 0.8rem; color: var(--gray-500); font-weight: 600;">Statistik Bulanan</span>
                </div>
                <div class="widget-body">
                    <div style="height: 330px; position: relative; width: 100%;">
                        <canvas id="suratTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- KANAN: KALENDER READ-ONLY (EQUAL HEIGHT - NO GAP) -->
        <div>
            <div class="widget-box equal-height">
                <div class="widget-header">
                    <h3>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--navy)" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        Kalender Agenda Bulanan
                    </h3>
                    <small style="color: var(--gray-500); font-weight: 600;">Klik Event untuk Detail</small>
                </div>
                <div class="widget-body">
                    <div id="dashboardCalendar"></div>
                </div>
            </div>
        </div>

    </div>

    <!-- OVERVIEW SECTIONS GRID (RAPI BERKELOMPOK) -->
    <div class="overview-sections-grid">
        <!-- 1. SECTION BERITA & PUBLIKASI -->
        <div class="widget-box" style="margin-bottom: 0;">
            <div class="widget-header">
                <h3>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--navy)" stroke-width="2"><path d="M19 20H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h10l5 5v11a2 2 0 0 1-2 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                    Ringkasan Berita & Publikasi
                </h3>
                <a href="{{ route('admin.berita.index') }}" style="font-size: 0.8rem; font-weight: 700; color: var(--navy); text-decoration: none;">Kelola Berita &rarr;</a>
            </div>
            <div class="widget-body">
                <div style="display: flex; gap: 1rem; margin-bottom: 1rem; background: var(--gray-50); padding: 0.75rem; border-radius: var(--radius-md); border: 1px solid var(--gray-200);">
                    <div style="flex: 1; text-align: center;">
                        <div style="font-size: 0.7rem; color: var(--gray-500); font-weight: 700;">TOTAL BERITA</div>
                        <div style="font-size: 1.1rem; font-weight: 800; color: var(--navy);">{{ $totalBerita }}</div>
                    </div>
                    <div style="flex: 1; text-align: center; border-left: 1px solid var(--gray-200); border-right: 1px solid var(--gray-200);">
                        <div style="font-size: 0.7rem; color: var(--gray-500); font-weight: 700;">PUBLISHED</div>
                        <div style="font-size: 1.1rem; font-weight: 800; color: var(--green);">{{ $totalBeritaAktif }}</div>
                    </div>
                    <div style="flex: 1; text-align: center;">
                        <div style="font-size: 0.7rem; color: var(--gray-500); font-weight: 700;">POPULER</div>
                        <div style="font-size: 1.1rem; font-weight: 800; color: var(--amber);">{{ $totalBeritaPopuler }}</div>
                    </div>
                </div>

                <div style="font-weight: 700; font-size: 0.8rem; color: var(--navy); margin-bottom: 0.5rem;">Berita Terbaru:</div>
                @if(isset($recentBerita) && $recentBerita->count() > 0)
                    @foreach($recentBerita as $b)
                        <div class="feature-item-row">
                            <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 70%; font-weight: 600;">{{ $b->judul }}</span>
                            <span class="status-badge-pill badge-selesai">{{ $b->status }}</span>
                        </div>
                    @endforeach
                @else
                    <div style="font-size: 0.8rem; color: var(--gray-500); text-align: center; padding: 0.5rem;">Belum ada berita terpublikasi.</div>
                @endif
            </div>
        </div>

        <!-- 2. SECTION E-KATALOG -->
        <div class="widget-box" style="margin-bottom: 0;">
            <div class="widget-header">
                <h3>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--navy)" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    Ringkasan E-Katalog Usaha
                </h3>
                <a href="{{ route('admin.katalog.index') }}" style="font-size: 0.8rem; font-weight: 700; color: var(--navy); text-decoration: none;">Kelola Katalog &rarr;</a>
            </div>
            <div class="widget-body">
                <div style="display: flex; gap: 1rem; margin-bottom: 1rem; background: var(--gray-50); padding: 0.75rem; border-radius: var(--radius-md); border: 1px solid var(--gray-200);">
                    <div style="flex: 1; text-align: center;">
                        <div style="font-size: 0.7rem; color: var(--gray-500); font-weight: 700;">TOTAL KATALOG</div>
                        <div style="font-size: 1.1rem; font-weight: 800; color: var(--navy);">{{ $totalKatalogAll }}</div>
                    </div>
                    <div style="flex: 1; text-align: center; border-left: 1px solid var(--gray-200); border-right: 1px solid var(--gray-200);">
                        <div style="font-size: 0.7rem; color: var(--gray-500); font-weight: 700;">KATALOG AKTIF</div>
                        <div style="font-size: 1.1rem; font-weight: 800; color: var(--green);">{{ $totalKatalog }}</div>
                    </div>
                    <div style="flex: 1; text-align: center;">
                        <div style="font-size: 0.7rem; color: var(--gray-500); font-weight: 700;">NONAKTIF</div>
                        <div style="font-size: 1.1rem; font-weight: 800; color: var(--amber);">{{ $totalKatalogInactive }}</div>
                    </div>
                </div>

                <div style="font-weight: 700; font-size: 0.8rem; color: var(--navy); margin-bottom: 0.5rem;">Katalog Produk Terbaru:</div>
                @if(isset($recentKatalogs) && $recentKatalogs->count() > 0)
                    @foreach($recentKatalogs as $k)
                        <div class="feature-item-row">
                            <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 70%; font-weight: 600;">{{ $k->company_name }}</span>
                            <span style="font-size: 0.725rem; font-weight: 700; color: var(--navy);">{{ $k->category ?? 'Produk' }}</span>
                        </div>
                    @endforeach
                @else
                    <div style="font-size: 0.8rem; color: var(--gray-500); text-align: center; padding: 0.5rem;">Belum ada katalog terdaftar.</div>
                @endif
            </div>
        </div>
    </div>

    <!-- BOTTOM FULL WIDTH: PETA REAL OPENLAYERS INDONESIA -->
    <div class="widget-box" style="margin-bottom: 1.5rem;">
        <div class="widget-header">
            <h3>
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--navy)" stroke-width="2"><circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/></svg>
                Peta OpenLayers Visualisasi Wilayah Temu Karya & Caretaker
            </h3>
            <div style="display: flex; gap: 0.5rem; align-items: center;">
                <label for="mapLevelFilter" style="font-size: 0.85rem; font-weight: 700; color: var(--navy); margin: 0;">Tingkatan Wilayah:</label>
                <select id="mapLevelFilter" class="form-control select2-basic" style="width: 170px;" onchange="filterMapLevel(this.value)">
                    <option value="provinsi">Provinsi</option>
                    <option value="kab_kota">Kabupaten / Kota</option>
                </select>
            </div>
        </div>
        <div class="widget-body">
            <div class="map-controls">
                <div class="map-legend">
                    <span class="legend-item"><span class="legend-dot" style="background: var(--green);"></span> Sudah Temu Karya</span>
                    <span class="legend-item"><span class="legend-dot" style="background: var(--amber);"></span> Caretaker</span>
                    <span class="legend-item"><span class="legend-dot" style="background: var(--gray-500);"></span> Belum Temu Karya</span>
                </div>
                <small id="mapLevelSubtitle" style="color: var(--gray-500); font-weight: 700; font-size: 0.85rem;">38 Provinsi Karang Taruna Indonesia</small>
            </div>

            <!-- REAL OPENLAYERS MAP CONTAINER FULL WIDTH -->
            <div id="openLayersRealMap"></div>

            <!-- DYNAMIC REGIONS GRID -->
            <div class="prov-grid-list" id="provGridContainer">
                <!-- Loaded dynamically via JS -->
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<!-- OpenLayers Map JS -->
<script src="https://cdn.jsdelivr.net/npm/ol@v7.4.0/dist/ol.js"></script>

<script>
    // DATA PROVINSI LENGKAP INDONESIA
    const dataProvinsi = [
        { name: 'Aceh', lat: 4.6951, lng: 96.7494, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Sumatera Utara', lat: 2.1154, lng: 99.5451, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Sumatera Barat', lat: -0.7399, lng: 100.8000, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Riau', lat: 0.2933, lng: 101.7068, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Kepulauan Riau', lat: 3.9456, lng: 108.1428, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Jambi', lat: -1.4852, lng: 103.6169, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Sumatera Selatan', lat: -3.3199, lng: 104.9147, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Bangka Belitung', lat: -2.7411, lng: 106.4406, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Bengkulu', lat: -3.5778, lng: 102.3464, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Lampung', lat: -4.5586, lng: 105.4068, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'DKI Jakarta', lat: -6.2088, lng: 106.8456, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Jawa Barat', lat: -6.9175, lng: 107.6191, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Banten', lat: -6.4058, lng: 106.0640, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Jawa Tengah', lat: -7.1510, lng: 110.1403, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'DI Yogyakarta', lat: -7.7956, lng: 110.3695, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Jawa Timur', lat: -7.5360, lng: 112.2384, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Bali', lat: -8.4095, lng: 115.1889, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Nusa Tenggara Barat', lat: -8.6529, lng: 117.3616, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Nusa Tenggara Timur', lat: -8.6574, lng: 121.0794, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Kalimantan Barat', lat: -0.2787, lng: 111.4753, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Kalimantan Tengah', lat: -1.6815, lng: 113.3824, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Kalimantan Selatan', lat: -3.0926, lng: 115.2838, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Kalimantan Timur', lat: 0.5387, lng: 116.4194, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Kalimantan Utara', lat: 3.0731, lng: 116.0414, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Sulawesi Utara', lat: 0.6247, lng: 123.9750, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Gorontalo', lat: 0.6999, lng: 122.4467, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Sulawesi Tengah', lat: -1.4300, lng: 121.4456, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Sulawesi Barat', lat: -2.8441, lng: 119.2321, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Sulawesi Selatan', lat: -3.6687, lng: 119.9741, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Sulawesi Tenggara', lat: -4.1449, lng: 122.1746, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Maluku', lat: -3.2385, lng: 130.1453, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Maluku Utara', lat: 1.5709, lng: 127.8087, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Papua', lat: -4.2699, lng: 138.0804, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Papua Barat', lat: -1.3361, lng: 133.1747, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Papua Selatan', lat: -7.0000, lng: 139.0000, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Papua Tengah', lat: -3.8000, lng: 136.5000, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Papua Pegunungan', lat: -4.1000, lng: 139.5000, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Papua Barat Daya', lat: -1.1500, lng: 131.2500, status: 'Belum', badgeClass: 'badge-belum', records: [] }
    ];

    const dataKabKota = [
        { name: 'Kota Banda Aceh', lat: 5.5483, lng: 95.3238, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Kota Medan', lat: 3.5952, lng: 98.6722, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Kota Padang', lat: -0.9471, lng: 100.4172, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Kota Pekanbaru', lat: 0.5071, lng: 101.4478, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Kota Palembang', lat: -2.9761, lng: 104.7754, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Kota Bandar Lampung', lat: -5.4500, lng: 105.2667, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Jakarta Pusat', lat: -6.1805, lng: 106.8284, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Jakarta Selatan', lat: -6.2615, lng: 106.8106, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Kota Bandung', lat: -6.9175, lng: 107.6191, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Kota Surabaya', lat: -7.2575, lng: 112.7521, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Kota Semarang', lat: -6.9667, lng: 110.4167, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Kota Yogyakarta', lat: -7.7956, lng: 110.3695, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Kota Denpasar', lat: -8.6705, lng: 115.2126, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Kota Pontianak', lat: -0.0263, lng: 109.3425, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Kota Banjarmasin', lat: -3.3167, lng: 114.5900, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Kota Samarinda', lat: -0.5022, lng: 117.1536, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Kota Makassar', lat: -5.1477, lng: 119.4327, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Kota Manado', lat: 1.4748, lng: 124.8428, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Kota Ambon', lat: -3.6954, lng: 128.1814, status: 'Belum', badgeClass: 'badge-belum', records: [] },
        { name: 'Kota Jayapura', lat: -2.5415, lng: 140.7186, status: 'Belum', badgeClass: 'badge-belum', records: [] }
    ];

    const temuKaryaRecords = @json($temuKaryaMapData);

    // KELOMPOKKAN TEMU KARYA (MULTI-TEMU KARYA PER WILAYAH)
    temuKaryaRecords.forEach(rec => {
        if (rec.wilayah) {
            const foundProv = dataProvinsi.find(p => p.name.toLowerCase() === rec.wilayah.toLowerCase());
            if (foundProv) {
                foundProv.records.push(rec);
                const count = foundProv.records.length;
                if (rec.status === 'selesai') {
                    foundProv.status = count > 1 ? `Selesai (${count})` : 'Selesai';
                    foundProv.badgeClass = 'badge-selesai';
                } else if (rec.jenis === 'caretaker' || rec.status === 'caretaker') {
                    foundProv.status = count > 1 ? `Caretaker (${count})` : 'Caretaker';
                    foundProv.badgeClass = 'badge-caretaker';
                }
            }

            const foundKab = dataKabKota.find(k => k.name.toLowerCase() === rec.wilayah.toLowerCase());
            if (foundKab) {
                foundKab.records.push(rec);
                const count = foundKab.records.length;
                if (rec.status === 'selesai') {
                    foundKab.status = count > 1 ? `Selesai (${count})` : 'Selesai';
                    foundKab.badgeClass = 'badge-selesai';
                } else if (rec.jenis === 'caretaker' || rec.status === 'caretaker') {
                    foundKab.status = count > 1 ? `Caretaker (${count})` : 'Caretaker';
                    foundKab.badgeClass = 'badge-caretaker';
                }
            }
        }
    });

    let olMapInstance = null;
    let vectorSource = null;

    document.addEventListener('DOMContentLoaded', function () {
        // Init Select2 on #mapLevelFilter
        if (typeof $.fn.select2 !== 'undefined') {
            $('#mapLevelFilter').select2({
                minimumResultsForSearch: -1
            });
        }

        // Init OpenLayers Map
        initOpenLayersMap();

        // Render Initial Grid Wilayah Level Provinsi
        renderMapGrid('provinsi');

        // --- 1. CHART.JS TREN SURAT MASUK & KELUAR ---
        const ctx = document.getElementById('suratTrendChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($chartMonths),
                datasets: [
                    {
                        label: 'Surat Masuk',
                        data: @json($suratMasukMonthly),
                        backgroundColor: '#022648',
                        borderRadius: 4,
                        barPercentage: 0.5,
                        categoryPercentage: 0.6,
                    },
                    {
                        label: 'Surat Keluar',
                        data: @json($suratKeluarMonthly),
                        backgroundColor: '#b7830f',
                        borderRadius: 4,
                        barPercentage: 0.5,
                        categoryPercentage: 0.6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 20,
                            font: { family: 'Montserrat', weight: '700', size: 11 }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, font: { family: 'Montserrat' } },
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        ticks: { font: { family: 'Montserrat', weight: '600' } },
                        grid: { display: false }
                    }
                }
            }
        });

        // --- 2. FULLCALENDAR PREVIEW WITH VECTOR SVG ICONS SWEETALERT2 MODAL ---
        const calendarEl = document.getElementById('dashboardCalendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next',
                center: 'title',
                right: 'today'
            },
            displayEventTime: false,
            height: 370,
            events: @json($calendarEvents),
            eventDidMount: function(info) {
                if (info.event.title) {
                    info.el.setAttribute('title', info.event.title);
                }
            },
            datesSet: function() {
                setTimeout(optimizeCalendarDensity, 50);
            },
            eventClick: function(info) {
                info.jsEvent.preventDefault();
                const props = info.event.extendedProps;
                
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: `<div style="color: #022648; font-weight: 800; font-size: 1.2rem; border-bottom: 2px solid #022648; padding-bottom: 8px;">${info.event.title}</div>`,
                        html: `
                            <div style="text-align: left; font-size: 0.875rem; line-height: 1.7; color: #374151; padding: 0.75rem 0;">
                                <div style="display: flex; align-items: flex-start; gap: 0.6rem; margin-bottom: 0.6rem;">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#022648" stroke-width="2" style="flex-shrink:0; margin-top:2px;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                    <div><strong>Lokasi Kegiatan:</strong><br>${props.lokasi}</div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.6rem;">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#022648" stroke-width="2" style="flex-shrink:0;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    <div><strong>Waktu Mulai:</strong> ${props.formatted_start} WIB</div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.6rem;">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#022648" stroke-width="2" style="flex-shrink:0;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 15 15"/></svg>
                                    <div><strong>Waktu Selesai:</strong> ${props.formatted_end} WIB</div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.6rem;">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#022648" stroke-width="2" style="flex-shrink:0;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    <div><strong>PIC / Penanggung Jawab:</strong> ${props.pic_name}</div>
                                </div>
                                <div style="margin-top: 0.75rem; padding: 0.85rem; background: #f8fafc; border-radius: 4px; border: 1px solid #e2e8f0; border-left: 4px solid #022648; display: flex; gap: 0.6rem;">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#022648" stroke-width="2" style="flex-shrink:0; margin-top:2px;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                    <div><strong style="color: #022648;">Catatan Agenda:</strong><br>${props.deskripsi}</div>
                                </div>
                            </div>
                        `,
                        confirmButtonText: 'Tutup Detail',
                        confirmButtonColor: '#022648',
                        showCancelButton: true,
                        cancelButtonText: 'Buka Manajemen Agenda',
                        cancelButtonColor: '#b7830f',
                    }).then((result) => {
                        if (result.dismiss === Swal.DismissReason.cancel) {
                            window.location.href = "{{ route('admin.agenda.index') }}";
                        }
                    });
                }
            }
        });

        function optimizeCalendarDensity() {
            document.querySelectorAll('.fc-daygrid-day').forEach(dayEl => {
                const events = dayEl.querySelectorAll('.fc-event');
                if (events.length > 2) {
                    dayEl.classList.add('has-many-events');
                } else {
                    dayEl.classList.remove('has-many-events');
                }
            });
        }

        calendar.render();
        setTimeout(optimizeCalendarDensity, 100);
    });

    function initOpenLayersMap() {
        vectorSource = new ol.source.Vector();

        const vectorLayer = new ol.layer.Vector({
            source: vectorSource
        });

        olMapInstance = new ol.Map({
            target: 'openLayersRealMap',
            layers: [
                new ol.layer.Tile({
                    source: new ol.source.OSM()
                }),
                vectorLayer
            ],
            view: new ol.View({
                center: ol.proj.fromLonLat([118.0149, -2.5489]),
                zoom: 5
            })
        });

        renderOpenLayersFeatures('provinsi');
    }

    function renderOpenLayersFeatures(level) {
        if (!vectorSource) return;
        vectorSource.clear();

        const items = level === 'provinsi' ? dataProvinsi : dataKabKota;

        items.forEach(item => {
            let colorHex = '#64748b'; // Gray for Belum
            if (item.status.includes('Selesai')) colorHex = '#059669'; // Green
            if (item.status.includes('Caretaker')) colorHex = '#d97706'; // Amber

            const feature = new ol.Feature({
                geometry: new ol.geom.Point(ol.proj.fromLonLat([item.lng, item.lat])),
                name: item.name,
                status: item.status,
                records: item.records
            });

            feature.setStyle(new ol.style.Style({
                image: new ol.style.Circle({
                    radius: level === 'provinsi' ? 8 : 6,
                    fill: new ol.style.Fill({ color: colorHex }),
                    stroke: new ol.style.Stroke({ color: '#ffffff', width: 2 })
                })
            }));

            vectorSource.addFeature(feature);
        });
    }

    function renderMapGrid(level) {
        const container = document.getElementById('provGridContainer');
        const subtitle = document.getElementById('mapLevelSubtitle');
        const items = level === 'provinsi' ? dataProvinsi : dataKabKota;

        subtitle.textContent = level === 'provinsi' 
            ? '38 Provinsi Karang Taruna Indonesia' 
            : `${items.length} Kabupaten / Kota Utama Karang Taruna`;

        let html = '';
        items.forEach((item, idx) => {
            html += `
                <div class="prov-item" onclick="showWilayahDetails('${level}', ${idx})" title="Klik untuk rincian ${item.name}">
                    <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: var(--navy);">${item.name}</span>
                    <span class="status-badge-pill ${item.badgeClass}">${item.status}</span>
                </div>
            `;
        });
        container.innerHTML = html;
    }

    function filterMapLevel(level) {
        renderMapGrid(level);
        renderOpenLayersFeatures(level);
        if (typeof Toast !== 'undefined') {
            Toast.fire({ icon: 'info', title: 'Level Wilayah Map: ' + (level === 'provinsi' ? 'Provinsi' : 'Kabupaten / Kota') });
        }
    }

    function showWilayahDetails(level, idx) {
        const items = level === 'provinsi' ? dataProvinsi : dataKabKota;
        const item = items[idx];
        
        if (!item) return;

        if (item.records && item.records.length > 0) {
            let listHtml = `<div style="text-align: left; font-size: 0.85rem; padding: 0.5rem 0;">`;
            item.records.forEach((rec, i) => {
                listHtml += `
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-left: 4px solid ${rec.status === 'selesai' ? '#059669' : '#d97706'}; padding: 0.75rem; border-radius: 4px; margin-bottom: 0.6rem;">
                        <div style="font-weight: 700; color: #022648;">#${i+1} ${rec.jenis ? rec.jenis.toUpperCase() : 'TEMU KARYA'}</div>
                        <div style="margin-top: 4px; color: #475569;"><strong>Status:</strong> ${rec.status ? rec.status.toUpperCase() : '-'}</div>
                        <div style="color: #475569;"><strong>Lokasi:</strong> ${rec.lokasi ?? '-'}</div>
                        <div style="color: #475569;"><strong>Tanggal:</strong> ${rec.tanggal_pelaksanaan ?? '-'}</div>
                    </div>
                `;
            });
            listHtml += `</div>`;

            Swal.fire({
                title: `<div style="color: #022648; font-weight: 800; font-size: 1.15rem;">Detail Temu Karya - ${item.name} (${item.records.length})</div>`,
                html: listHtml,
                confirmButtonText: 'Tutup Rincian',
                confirmButtonColor: '#022648'
            });
        } else {
            Swal.fire({
                title: `<div style="color: #022648; font-weight: 800; font-size: 1.15rem;">${item.name}</div>`,
                text: 'Belum ada catatan pelaksanaan Temu Karya / Caretaker di wilayah ini.',
                icon: 'info',
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#022648'
            });
        }
    }
</script>
@endpush