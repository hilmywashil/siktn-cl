@extends('admin.layouts.admin-layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@php
$activeMenu = 'dashboard';
@endphp

@section('content')
{{-- Welcome Card --}}
<div class="welcome-card">
    <div class="welcome-content">
        <div class="welcome-avatar">
            @if(auth()->guard('admin')->user()->photo)
            <img src="{{ auth()->guard('admin')->user()->photo_url }}" alt="{{ auth()->guard('admin')->user()->name }}">
            @else
            {{ strtoupper(substr(auth()->guard('admin')->user()->name, 0, 2)) }}
            @endif
        </div>
        <div class="welcome-text">
            <h1>Selamat Datang, {{ auth()->guard('admin')->user()->name }}</h1>
            <p>Dashboard Admin Karang Taruna</p>
            <span class="category-badge badge-{{ auth()->guard('admin')->user()->category }}">
                {{ auth()->guard('admin')->user()->role_display_name }}
            </span>
        </div>
    </div>
</div>

{{-- Statistics Cards --}}
<div class="stats-grid">
    @if($admin->category === 'bpc')
        <div class="stats-wrapper">
            <div class="stat-card">
                <div class="stat-label">Total Pendaftar</div>
                <div class="stat-value">{{ $totalAnggota }}</div>
                <div class="stat-meta">Wilayah {{ $admin->domisili }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Menunggu Verifikasi</div>
                <div class="stat-value">{{ $pendingAnggota }}</div>
                <div class="stat-meta">Perlu ditinjau</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Disetujui</div>
                <div class="stat-value">{{ $approvedAnggota }}</div>
                <div class="stat-meta">Anggota aktif</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Ditolak</div>
                <div class="stat-value">{{ $rejectedAnggota }}</div>
                <div class="stat-meta">Tidak memenuhi syarat</div>
            </div>
        </div>
    @else
        <div class="stats-wrapper">
            <div class="stat-card">
                <div class="stat-label">Total Admin</div>
                <div class="stat-value">{{ $totalAdmins }}</div>
                <div class="stat-meta">Jumlah</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total E-Katalog</div>
                <div class="stat-value">{{ $totalKatalog }}</div>
                <div class="stat-meta">
                    @if($totalKatalogInactive > 0)
                    {{ $totalKatalogInactive }} tidak aktif
                    @else
                    Semua aktif
                    @endif
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Anggota</div>
                <div class="stat-value">{{ $totalAnggotaApproved }}</div>
                <div class="stat-meta">
                    Pending: {{ $totalAnggotaPending }} | Ditolak: {{ $totalAnggotaRejected }}
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Struktur Organisasi</div>
                <div class="stat-value">{{ $totalOrganisasi }}</div>
                <div class="stat-meta">Anggota organisasi aktif</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Berita</div>
                <div class="stat-value">{{ $totalBerita }}</div>
                <div class="stat-meta">
                    Aktif: {{ $totalBeritaAktif }} | Populer: {{ $totalBeritaPopuler }}
                </div>
            </div>
        </div>
    @endif
</div>

{{-- Content untuk BPC --}}
@if($admin->category === 'bpc')
    <div class="tabs-container">
        <div class="tab-content active">
            <div class="section-header">
                <h3 class="section-title">Pendaftar Terbaru dari {{ $admin->domisili }}</h3>
            </div>
            <div class="admin-list">
                @forelse($recentAnggota as $anggota)
                <div class="admin-item">
                    <div class="admin-avatar">
                        {{ strtoupper(substr($anggota->nama_perusahaan, 0, 2)) }}
                    </div>
                    <div class="admin-info">
                        <div class="admin-name">{{ $anggota->nama_perusahaan }}</div>
                        <div class="admin-email">{{ $anggota->email_website_perusahaan }} - {{ $anggota->trade_mark }}</div>
                    </div>
                    <span class="admin-badge badge-{{ $anggota->status }}">
                        @if($anggota->status === 'pending') MENUNGGU
                        @elseif($anggota->status === 'approved') DISETUJUI
                        @else DITOLAK @endif
                    </span>
                </div>
                @empty
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <p>Belum ada pendaftar dari wilayah {{ $admin->domisili }}</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
@else
    {{-- Content untuk BPD/Super Admin --}}
    <div class="tabs-container">
        <div class="tabs-header">
            <button class="tab-button active" data-tab="anggota">
                <i class="fas fa-users"></i> Anggota
                <span class="tab-badge">{{ $totalAnggotaApproved }}</span>
            </button>
            <button class="tab-button" data-tab="admin">
                <i class="fas fa-user-shield"></i> Admin
                <span class="tab-badge">{{ $totalAdmins }}</span>
            </button>
            <button class="tab-button" data-tab="katalog">
                <i class="fas fa-briefcase"></i> E-Katalog
                <span class="tab-badge">{{ $totalKatalog }}</span>
            </button>
            <button class="tab-button" data-tab="organisasi">
                <i class="fas fa-sitemap"></i> Organisasi
                <span class="tab-badge">{{ $totalOrganisasi }}</span>
            </button>
            <button class="tab-button" data-tab="berita">
                <i class="fas fa-newspaper"></i> Berita
                <span class="tab-badge">{{ $totalBerita }}</span>
            </button>
        </div>

        {{-- Tab: Anggota --}}
        <div id="tab-anggota" class="tab-content active">
            <div class="section-header">
                <h3 class="section-title">Pendaftar Terbaru (Semua Domisili)</h3>
            </div>
            <div class="admin-list">
                @forelse($recentAnggota as $anggota)
                <div class="admin-item">
                    <div class="admin-avatar">
                        {{ strtoupper(substr($anggota->nama_perusahaan, 0, 2)) }}
                    </div>
                    <div class="admin-info">
                        <div class="admin-name">
                            {{ $anggota->nama_perusahaan }}
                            @if($anggota->domisili) - {{ $anggota->domisili }} @endif
                        </div>
                        <div class="admin-email">{{ $anggota->email_website_perusahaan }} - {{ $anggota->trade_mark }}</div>
                    </div>
                    <span class="admin-badge badge-{{ $anggota->status }}">
                        @if($anggota->status === 'pending') MENUNGGU
                        @elseif($anggota->status === 'approved') DISETUJUI
                        @else DITOLAK @endif
                    </span>
                </div>
                @empty
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <p>Belum ada pendaftar</p>
                </div>
                @endforelse
            </div>
        </div>

         {{-- Tab: Admin --}}
        <div id="tab-admin" class="tab-content">
            <div class="section-header">
                <h3 class="section-title">Daftar Admin Terdaftar</h3>
                <a href="{{ route('admin.info-admin') }}" class="view-all-btn">Lihat Semua</a>
            </div>
            <div class="admin-list">
                @forelse($recentAdmins as $adminItem)
                <div class="admin-item">
                    <div class="admin-avatar">
                        @if($adminItem->photo)
                        <img src="{{ $adminItem->photo_url }}" alt="{{ $adminItem->name }}">
                        @else
                        {{ strtoupper(substr($adminItem->name, 0, 2)) }}
                        @endif
                    </div>
                    <div class="admin-info">
                        <div class="admin-name">{{ $adminItem->name }}</div>
                        <div class="admin-email">{{ $adminItem->email }}</div>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <p>Belum ada admin terdaftar</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Tab: Katalog --}}
        <div id="tab-katalog" class="tab-content">
            <div class="section-header">
                <h3 class="section-title">E-Katalog Terbaru</h3>
                <a href="{{ route('admin.katalog.index') }}" class="view-all-btn">Lihat Semua</a>
            </div>
            <div class="admin-list">
                @forelse($recentKatalogs as $katalog)
                <div class="katalog-item">
                    <div class="katalog-thumbnail">
                        <img src="{{ $katalog->logo_url }}" alt="{{ $katalog->company_name }}">
                    </div>
                    <div class="katalog-content">
                        <div class="katalog-header">
                            <h4 class="katalog-title">{{ $katalog->company_name }}</h4>
                            <p class="katalog-field">{{ $katalog->business_field }}</p>
                        </div>
                        <div class="katalog-footer">
                            <span class="katalog-meta">Ditambahkan {{ $katalog->created_at->diffForHumans() }}</span>
                            <span class="admin-badge badge-aktif">AKTIF</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <i class="fas fa-briefcase"></i>
                    <p>Belum ada katalog bisnis</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Tab: Organisasi --}}
        <div id="tab-organisasi" class="tab-content">
            <div class="section-header">
                <h3 class="section-title">Struktur Organisasi Terbaru</h3>
                <a href="{{ route('admin.organisasi.index') }}" class="view-all-btn">Lihat Semua</a>
            </div>
            <div class="admin-list">
                @forelse($recentOrganisasi as $org)
                <div class="organisasi-item">
                    <div class="organisasi-photo">
                        <img src="{{ $org->foto_url }}" alt="{{ $org->nama }}">
                    </div>
                    <div class="organisasi-content">
                        <div>
                            <h4 class="organisasi-name">{{ $org->nama }}</h4>
                            <p class="organisasi-position">{{ $org->jabatan }}</p>
                        </div>
                        <div class="organisasi-footer">
                            <span class="katalog-meta">Urutan: {{ $org->urutan }}</span>
                            <span class="admin-badge badge-{{ $org->kategori }}">
                                {{ strtoupper(str_replace('_', ' ', $org->kategori)) }}
                            </span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <i class="fas fa-sitemap"></i>
                    <p>Belum ada data organisasi</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Tab: Berita --}}
        <div id="tab-berita" class="tab-content">
            <div class="section-header">
                <h3 class="section-title">Berita Terbaru</h3>
                <a href="{{ route('admin.berita.index') }}" class="view-all-btn">Lihat Semua</a>
            </div>
            <div class="admin-list">
                @forelse($recentBerita as $berita)
                <div class="katalog-item">
                    <div class="katalog-thumbnail">
                        <img src="{{ $berita->gambar_url }}" alt="{{ $berita->judul }}">
                    </div>
                    <div class="katalog-content">
                        <div class="katalog-header">
                            <h4 class="katalog-title">{{ $berita->judul }}</h4>
                            <p class="katalog-field">
                                <i class="fas fa-eye"></i> {{ $berita->views }} views | 
                                <i class="fas fa-calendar"></i> {{ $berita->tanggal_format }}
                            </p>
                        </div>
                        <div class="katalog-footer">
                            <span class="katalog-meta">{{ $berita->created_at->diffForHumans() }}</span>
                            <div>
                                @if($berita->is_populer)
                                <span class="admin-badge" style="background: #f59e0b; color: white; margin-right: 0.5rem;">POPULER</span>
                                @endif
                                <span class="admin-badge badge-{{ $berita->is_active ? 'aktif' : 'pending' }}">
                                    {{ $berita->is_active ? 'AKTIF' : 'TIDAK AKTIF' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <i class="fas fa-newspaper"></i>
                    <p>Belum ada berita</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
@endif

<!-- Floating Action Button for Birthdays -->
<button type="button" class="birthday-fab" onclick="toggleBirthdaySidebar()">
    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M20 21v-8a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v8"></path>
        <path d="M4 16s.5-1 2-1 2.5 2 4 2 2.5-2 4-2 2.5 2 4 2 2 1 2 1"></path>
        <path d="M2 21h20"></path>
        <path d="M7 8v3"></path>
        <path d="M12 8v3"></path>
        <path d="M17 8v3"></path>
        <path d="M7 4h.01"></path>
        <path d="M12 4h.01"></path>
        <path d="M17 4h.01"></path>
    </svg>
    @if(isset($upcomingBirthdays) && $upcomingBirthdays->isNotEmpty())
        <span class="birthday-badge">{{ $upcomingBirthdays->count() }}</span>
    @endif
</button>

<!-- Slide-in Sidebar (Offcanvas) for Birthdays -->
<div class="birthday-overlay" id="birthdaySidebarOverlay" onclick="toggleBirthdaySidebar()"></div>
<div class="birthday-sidebar" id="birthdaySidebar">
    <div class="birthday-sidebar-header">
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <div style="background: rgba(197, 146, 23, 0.1); color: #C59217; padding: 0.5rem; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-8a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v8"></path>
                    <path d="M4 16s.5-1 2-1 2.5 2 4 2 2.5-2 4-2 2.5 2 4 2 2 1 2 1"></path>
                    <path d="M2 21h20"></path>
                    <path d="M7 8v3"></path>
                    <path d="M12 8v3"></path>
                    <path d="M17 8v3"></path>
                    <path d="M7 4h.01"></path>
                    <path d="M12 4h.01"></path>
                    <path d="M17 4h.01"></path>
                </svg>
            </div>
            <h3 style="font-size: 1.125rem; font-weight: 700; color: #0a2540; margin: 0;">Ulang Tahun Anggota Terdekat</h3>
        </div>
        <button type="button" class="birthday-close-btn" onclick="toggleBirthdaySidebar()">
            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" fill="none" stroke-width="2">
                <path d="M18 6L6 18M6 6l12 12"></path>
            </svg>
        </button>
    </div>
    
    <div class="birthday-sidebar-body">
        <div class="birthday-list" style="display: flex; flex-direction: column; gap: 1rem;">
            @if(isset($upcomingBirthdays) && $upcomingBirthdays->isNotEmpty())
                @foreach($upcomingBirthdays as $bd)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 1rem; border-bottom: 1px solid #f3f4f6;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 42px; height: 42px; border-radius: 50%; background: #C59217; color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.9375rem; flex-shrink: 0;">
                                {{ strtoupper(substr($bd['nama'], 0, 2)) }}
                            </div>
                            <div>
                                <div style="font-weight: 700; color: #0a2540; font-size: 0.9375rem; margin-bottom: 0.125rem;">{{ $bd['nama'] }}</div>
                                <div style="color: #6b7280; font-size: 0.8125rem;">Anggota</div>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-weight: 700; color: #374151; font-size: 0.875rem;">{{ date('d M', strtotime($bd['tanggal'])) }}</div>
                            <div style="color: #C59217; font-size: 0.75rem; font-weight: 700; background: rgba(197, 146, 23, 0.1); padding: 0.15rem 0.5rem; border-radius: 4px; display: inline-block; margin-top: 0.25rem;">{{ $bd['hari'] }}</div>
                        </div>
                    </div>
                @endforeach
            @else
                <div style="text-align: center; color: #6b7280; padding: 2rem 0; font-size: 0.875rem;">
                    <svg viewBox="0 0 24 24" width="48" height="48" stroke="#d1d5db" fill="none" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 1rem;">
                        <path d="M20 21v-8a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v8"></path>
                        <path d="M4 16s.5-1 2-1 2.5 2 4 2 2.5-2 4-2 2.5 2 4 2 2 1 2 1"></path>
                        <path d="M2 21h20"></path>
                        <path d="M7 8v3"></path>
                        <path d="M12 8v3"></path>
                        <path d="M17 8v3"></path>
                        <path d="M7 4h.01"></path>
                        <path d="M12 4h.01"></path>
                        <path d="M17 4h.01"></path>
                    </svg>
                    <p>Belum ada anggota yang berulang tahun dalam waktu dekat.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@push('styles')
<style>
    * {
        font-family: 'Montserrat', sans-serif;
    }

    .welcome-card {
        background: linear-gradient(135deg, #0a2540 0%, #1a3a5c 100%);
        padding: 2.5rem;
        border-radius: 12px;
        color: white;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .welcome-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 215, 0, 0.1);
        border-radius: 50%;
        z-index: 0;
    }

    .welcome-content {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 2rem;
    }

    .welcome-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #ffd700;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0a2540;
        font-weight: 700;
        font-size: 2rem;
        flex-shrink: 0;
        border: 4px solid rgba(255, 215, 0, 0.3);
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .welcome-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .welcome-text {
        flex: 1;
    }

    .welcome-card h1 {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        font-weight: 700;
    }

    .welcome-card p {
        font-size: 1.125rem;
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 1rem;
    }

    .category-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .badge-bpc, .badge-pkkt {
        background: #ffd700;
        color: #0a2540;
    }

    .badge-bpd, .badge-ppkt {
        background: #3b82f6;
        color: white;
    }
    
    .badge-pnkt {
        background: #022648;
        color: #34d399;
        border: 1px solid #34d399;
        font-weight: 700;
    }

    .badge-ppkt {
        background: #022648;
        color: #60a5fa;
        border: 1px solid #60a5fa;
        font-weight: 700;
    }

    .badge-pkkt {
        background: #022648;
        color: #fbbf24;
        border: 1px solid #fbbf24;
        font-weight: 700;
    }
    
    .badge-pimpinan {
        background: #022648;
        color: #f87171;
        border: 1px solid #f87171;
        font-weight: 700;
    }

    .badge-super_admin {
        background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);
        color: white;
    }

    .stats-grid {
        margin-bottom: 2rem;
    }

    .stats-wrapper {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }

    .stat-card {
        background: white;
        padding: 2rem;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        transform: translateY(-4px);
    }

    .stat-label {
        color: #9ca3af;
        font-size: 0.9375rem;
        margin-bottom: 1rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-value {
        font-size: 3.5rem;
        font-weight: 800;
        color: #0a2540;
        margin-bottom: 0.75rem;
        line-height: 1;
    }

    .stat-meta {
        font-size: 0.9375rem;
        color: #10b981;
        font-weight: 600;
    }

    .tabs-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }

    .tabs-header {
        display: flex;
        border-bottom: 2px solid #f3f4f6;
        background: #f9fafb;
        overflow-x: auto;
        scrollbar-width: thin;
    }

    .tab-button {
        flex: 1;
        min-width: 150px;
        padding: 1rem 1.5rem;
        background: transparent;
        border: none;
        color: #6b7280;
        font-weight: 600;
        font-size: 0.9375rem;
        cursor: pointer;
        transition: all 0.3s;
        position: relative;
        white-space: nowrap;
    }

    .tab-button:hover {
        background: #f3f4f6;
        color: #0a2540;
    }

    .tab-button.active {
        color: #0a2540;
        background: white;
    }

    .tab-button.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 3px;
        background: #C59217;
    }

    .tab-badge {
        display: inline-block;
        background: #e5e7eb;
        color: #6b7280;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        margin-left: 0.5rem;
    }

    .tab-button.active .tab-badge {
        background: #C59217;
        color: white;
    }

    .tab-content {
        display: none;
        padding: 2rem;
        animation: fadeIn 0.3s ease-in-out;
    }

    .tab-content.active {
        display: block;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f3f4f6;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0a2540;
    }

    .view-all-btn {
        color: #0a2540;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 600;
        transition: color 0.2s;
    }

    .view-all-btn:hover {
        color: #ffd700;
    }

    .admin-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .admin-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.25rem;
        background: #f9fafb;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        transition: all 0.2s;
    }

    .admin-item:hover {
        background: #f3f4f6;
        border-color: #d1d5db;
        transform: translateX(4px);
    }

    .admin-avatar {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: #ffd700;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0a2540;
        font-weight: 700;
        font-size: 1.125rem;
        flex-shrink: 0;
        overflow: hidden;
        border: 2px solid #e5e7eb;
    }

    .admin-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .katalog-item {
        display: flex;
        gap: 1.5rem;
        padding: 1.5rem;
        background: #f9fafb;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        transition: all 0.3s;
    }

    .katalog-item:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
        border-color: #ffd700;
        background: white;
    }

    .katalog-thumbnail {
        width: 120px;
        height: 120px;
        border-radius: 8px;
        overflow: hidden;
        flex-shrink: 0;
        background: white;
        border: 1px solid #e5e7eb;
    }

    .katalog-thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .katalog-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-width: 0;
    }

    .katalog-header {
        margin-bottom: 0.5rem;
    }

    .katalog-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #0a2540;
        margin-bottom: 0.5rem;
        line-height: 1.4;
    }

    .katalog-field {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 0.75rem;
    }

    .katalog-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }

    .katalog-meta {
        font-size: 0.8125rem;
        color: #9ca3af;
    }

    .organisasi-item {
        display: flex;
        gap: 1.5rem;
        padding: 1.5rem;
        background: #f9fafb;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        transition: all 0.3s;
    }

    .organisasi-item:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
        border-color: #3b82f6;
        background: white;
    }

    .organisasi-photo {
        width: 100px;
        height: 100px;
        border-radius: 8px;
        overflow: hidden;
        flex-shrink: 0;
        background: white;
        border: 2px solid #e5e7eb;
    }

    .organisasi-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .organisasi-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-width: 0;
    }

    .organisasi-name {
        font-size: 1.125rem;
        font-weight: 700;
        color: #0a2540;
        margin-bottom: 0.25rem;
    }

    .organisasi-position {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 0.75rem;
    }

    .organisasi-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .admin-info {
        flex: 1;
        min-width: 0;
    }

    .admin-name {
        font-weight: 600;
        color: #0a2540;
        margin-bottom: 0.25rem;
        font-size: 0.9375rem;
    }

    .admin-email {
        font-size: 0.8125rem;
        color: #6b7280;
    }

    .admin-badge {
        padding: 0.375rem 0.875rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .badge-aktif {
        background: #10b981;
        color: white;
    }

    .badge-pending {
        background: #fef3c7;
        color: #d97706;
    }

    .badge-approved {
        background: #d1fae5;
        color: #059669;
    }

    .badge-rejected {
        background: #fee2e2;
        color: #dc2626;
    }

    .badge-ketua_umum,
    .badge-wakil_ketua_umum {
        background: #3b82f6;
        color: white;
    }

    .badge-ketua_bidang {
        background: #8b5cf6;
        color: white;
    }

    .badge-sekretaris_umum,
    .badge-wakil_sekretaris_umum {
        background: #f59e0b;
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 2rem;
        color: #9ca3af;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    /* Birthday Sidebar Styles */
    .birthday-fab {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #C59217;
        color: white;
        border: none;
        box-shadow: 0 4px 12px rgba(197, 146, 23, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 99;
        transition: all 0.3s;
    }
    
    .birthday-fab:hover {
        transform: scale(1.05) translateY(-5px);
        box-shadow: 0 6px 16px rgba(197, 146, 23, 0.5);
    }
    
    .birthday-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #ef4444;
        color: white;
        font-size: 0.75rem;
        font-weight: 700;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid white;
    }
    
    .birthday-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.4);
        z-index: 100;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s;
    }
    
    .birthday-overlay.active {
        opacity: 1;
        visibility: visible;
    }
    
    .birthday-sidebar {
        position: fixed;
        top: 0; right: -400px;
        width: 400px; max-width: 100%;
        height: 100vh;
        background: white;
        z-index: 101;
        box-shadow: -4px 0 24px rgba(0,0,0,0.15);
        transition: right 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
    }
    
    .birthday-sidebar.active {
        right: 0;
    }
    
    .birthday-sidebar-header {
        display: flex; justify-content: space-between; align-items: center;
        padding: 1.5rem;
        border-bottom: 2px solid #f3f4f6;
    }
    
    .birthday-close-btn {
        background: transparent; border: none; color: #6b7280;
        cursor: pointer; padding: 0.5rem; border-radius: 8px; transition: all 0.2s;
        display: flex; align-items: center; justify-content: center;
    }
    
    .birthday-close-btn:hover { background: #f3f4f6; color: #ef4444; }
    
    .birthday-sidebar-body {
        padding: 1.5rem; overflow-y: auto; flex: 1;
    }

    @media (max-width: 1024px) {
        .welcome-content {
            flex-direction: column;
            text-align: center;
        }
        .welcome-avatar {
            width: 70px;
            height: 70px;
            font-size: 1.5rem;
        }
        .welcome-card {
            padding: 1.5rem;
        }
        .welcome-card h1 {
            font-size: 1.5rem;
        }
        .welcome-card p {
            font-size: 0.9375rem;
        }
        .stats-wrapper {
            grid-template-columns: repeat(2, 1fr);
        }
        .admin-item {
            flex-wrap: wrap;
        }
        .admin-badge {
            margin-left: auto;
        }
        .tab-button {
            min-width: 120px;
            padding: 0.875rem 1rem;
            font-size: 0.875rem;
        }
        .tab-content {
            padding: 1.5rem;
        }
    }

    @media (max-width: 640px) {
        .stats-wrapper {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');

        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                const tabName = this.getAttribute('data-tab');
                
                // Remove active class from all buttons and contents
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('active'));
                
                // Add active class to clicked button
                this.classList.add('active');
                
                // Show corresponding content
                const targetContent = document.getElementById(`tab-${tabName}`);
                if (targetContent) {
                    targetContent.classList.add('active');
                }
            });
        });
    });

    function toggleBirthdaySidebar() {
        document.getElementById('birthdaySidebar').classList.toggle('active');
        document.getElementById('birthdaySidebarOverlay').classList.toggle('active');
    }
</script>


@endpush
@endsection