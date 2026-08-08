@extends('admin.layouts.admin-layout')

@section('title', 'Direktori Kontak Pengurus & Anggota')
@section('page-title', 'Direktori Kontak')

@push('styles')
<style>
        @keyframes select2DropdownFadeIn {
            from { opacity: 0; transform: translateY(-8px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        @keyframes viewFadeIn {
            from { opacity: 0; transform: translateY(8px) scale(0.99); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        .animate-view-fade {
            animation: viewFadeIn 0.28s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

    .select2-container--default .select2-selection--single {
        height: 40px; padding: 0.35rem 0.75rem; font-size: 0.8125rem; font-weight: 600;
        color: var(--navy); background-color: #fff; border: 1px solid var(--gray-300);
        border-radius: var(--radius-md); display: flex; align-items: center;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); min-width: 180px;
    }
    .select2-container--default .select2-selection--single:hover {
        border-color: var(--navy); transform: translateY(-1px); box-shadow: 0 3px 8px rgba(2, 38, 72, 0.1);
    }

    .select2-dropdown {
        border: 1px solid var(--gray-300); border-radius: var(--radius-md); font-size: 0.8125rem; z-index: 9999;
        box-shadow: 0 12px 28px rgba(2, 38, 72, 0.15); margin-top: 4px; overflow: hidden; background-color: #fff;
    }
    .select2-container--open .select2-dropdown {
        animation: select2DropdownFadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .select2-container--default .select2-results__option--selectable {
        color: #111827 !important;
        transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1) !important;
        padding: 0.5rem 0.75rem !important;
    }
    .select2-results__option--highlighted[aria-selected],
    .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
        background-color: #022648 !important; color: #ffffff !important; font-weight: 600 !important;
        padding-left: 1.15rem !important;
    }

    .admin-ui-scope {
        --navy: #022648; --navy-dark: #01162f; --navy-light: #0a3a6b;
        --gold: #b7830f; --green: #059669; --blue: #2563eb; --red: #dc2626;
        --gray-50: #f9fafb; --gray-100: #f3f4f6; --gray-200: #e5e7eb; --gray-300: #d1d5db;
        --gray-500: #6b7280; --gray-700: #374151; --gray-900: #111827;
        --radius-sm: 4px; --radius-md: 6px; --radius-lg: 8px;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    /* ===== Stat Cards ===== */
    .stat-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
        gap: 1.25rem;
        margin-bottom: 1.75rem;
    }

    .stat-card {
        background: #ffffff; border-radius: var(--radius-lg); padding: 1.25rem;
        border: 1px solid var(--gray-200); box-shadow: 0 1px 3px rgba(2, 38, 72, 0.05);
        display: flex; align-items: center; gap: 1rem; position: relative; overflow: hidden;
    }
    .stat-card::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; background: var(--navy); }
    .stat-card.active::before { background: var(--green); }
    .stat-card.regional::before { background: var(--blue); }

    .stat-icon {
        width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center;
        justify-content: center; flex-shrink: 0; background: var(--gray-100); color: var(--navy);
    }
    .stat-info h4 { margin: 0; font-size: 0.75rem; color: var(--gray-500); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
    .stat-info .value { font-size: 1.6rem; font-weight: 800; color: var(--navy); margin-top: 0.2rem; font-family: 'Montserrat', sans-serif; }

    /* ===== Filter Box ===== */
    .filter-card {
        background: white; border-radius: var(--radius-lg); padding: 1.5rem;
        border: 1px solid var(--gray-200); box-shadow: 0 1px 3px rgba(0,0,0,0.05); margin-bottom: 2rem;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)) auto;
        gap: 1.25rem;
        align-items: flex-end;
    }

    .form-group { display: flex; flex-direction: column; gap: 0.4rem; }
    .form-group label { font-size: 0.85rem; font-weight: 700; color: var(--navy); }

    .form-control {
        padding: 0.55rem 0.875rem; border-radius: var(--radius-md); border: 1px solid var(--gray-300);
        font-size: 0.875rem; outline: none; background: white; transition: all 0.2s ease; width: 100%;
    }
    .form-control:focus { border-color: var(--navy); box-shadow: 0 0 0 3px rgba(2, 38, 72, 0.1); }

    .filter-actions {
        display: flex; gap: 0.5rem; flex-wrap: wrap; align-items: center;
    }
    .filter-actions-spacer { font-size: 0.85rem; visibility: hidden; white-space: nowrap; }

    .btn-solid-navy {
        background: var(--navy); color: white; padding: 0.55rem 1.15rem; border-radius: var(--radius-md);
        font-weight: 600; font-size: 0.875rem; border: none; cursor: pointer;
        display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
        text-decoration: none; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 4px rgba(2, 38, 72, 0.12); white-space: nowrap;
    }
    .btn-solid-navy:hover { background: var(--navy-light); color: white; transform: translateY(-1px); box-shadow: 0 4px 10px rgba(2, 38, 72, 0.2); }

    .btn-outline-secondary {
        background: white; color: var(--gray-700); padding: 0.55rem 1.15rem; border-radius: var(--radius-md);
        font-weight: 600; font-size: 0.875rem; border: 1px solid var(--gray-300); cursor: pointer;
        display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
        text-decoration: none; transition: all 0.2s ease; white-space: nowrap;
    }
    .btn-outline-secondary:hover { background: var(--gray-100); color: var(--navy); }

    .view-mode-toggle {
        display: inline-flex; background: #f1f5f9; border-radius: 6px; padding: 3px;
        border: 1px solid #cbd5e1; margin-left: 0.25rem;
    }
    .btn-toggle-view {
        padding: 6px 12px; border-radius: 4px; border: none; font-weight: 700; font-size: 0.8rem;
        cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
        transition: all 0.2s; background: transparent; color: #475569;
    }
    .btn-toggle-view.active { background: var(--navy); color: white; }

    /* ===== Grid View ===== */
    .contact-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
        align-items: stretch;
    }

    .contact-card {
        background: white; border-radius: var(--radius-lg); padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05); display: flex; flex-direction: column;
        align-items: center; text-align: center; transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
        position: relative; border: 1px solid var(--gray-200); width: 100%;
    }
    .contact-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px -4px rgba(2, 38, 72, 0.12);
        border-color: var(--gray-300);
    }

    .avatar-wrapper {
        width: 80px; height: 80px; border-radius: 50%; overflow: hidden; margin-bottom: 1rem;
        border: 3px solid var(--gray-200); background: var(--gray-100);
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .avatar-wrapper img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .avatar-placeholder { font-size: 2rem; font-weight: 800; color: var(--navy); font-family: 'Montserrat', sans-serif; }

    .contact-name { font-size: 1.1rem; font-weight: 700; color: var(--navy); margin-bottom: 0.25rem; word-break: break-word; }

    .contact-badge {
        display: inline-block; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem;
        font-weight: 700; background: #eff6ff; color: #1e40af; margin-bottom: 0.75rem;
    }

    .contact-info {
        width: 100%; display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.85rem;
        color: var(--gray-700); margin-bottom: 1.25rem; padding-top: 0.75rem;
        border-top: 1px solid var(--gray-100); flex: 1;
    }
    .info-item { display: flex; align-items: center; gap: 0.5rem; justify-content: center; max-width: 100%; }
    .info-item svg { width: 16px; height: 16px; flex-shrink: 0; color: var(--gray-500); }
    .info-item span { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 220px; }

    .contact-actions { width: 100%; display: flex; gap: 0.5rem; margin-top: auto; }

    .btn-wa {
        flex: 1; background: #25d366; color: white; padding: 0.55rem 1rem; border-radius: var(--radius-md);
        font-size: 0.85rem; font-weight: 700; text-decoration: none; display: inline-flex;
        align-items: center; justify-content: center; gap: 0.375rem; transition: all 0.2s;
    }
    .btn-wa:hover { background: #1da851; color: white; transform: translateY(-1px); }

    .btn-email {
        background: var(--gray-100); color: var(--gray-700); padding: 0.55rem 0.75rem; border-radius: var(--radius-md);
        font-size: 0.85rem; font-weight: 600; text-decoration: none; display: inline-flex;
        align-items: center; justify-content: center; transition: all 0.2s;
    }
    .btn-email:hover { background: var(--gray-200); color: var(--navy); }

    /* ===== List View ===== */
    #kontakListViewContainer {
        background: white; border-radius: var(--radius-lg); border: 1px solid var(--gray-200);
        overflow: hidden; margin-bottom: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .kontak-table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
    .kontak-table thead tr { background: var(--navy); color: white; }
    .kontak-table th { padding: 12px 14px; text-align: left; font-weight: 700; letter-spacing: 0.03em; }
    .kontak-table th.col-no, .kontak-table th.col-wa { text-align: center; }
    .kontak-table th.col-no { width: 50px; }
    .kontak-table td { padding: 10px 14px; border-bottom: 1px solid var(--gray-200); vertical-align: middle; }
    .kontak-table tbody tr:last-child td { border-bottom: none; }
    .kontak-table tbody tr:nth-child(even) { background: var(--gray-50); }
    .kontak-table tbody tr:hover { background: #eef2f7; }

    .kontak-row-no { text-align: center; font-weight: 700; color: var(--gray-500); }
    .kontak-row-name { display: flex; align-items: center; gap: 10px; }
    .kontak-row-avatar {
        width: 34px; height: 34px; border-radius: 50%; overflow: hidden; background: var(--navy);
        color: white; display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 0.85rem; flex-shrink: 0;
    }
    .kontak-row-avatar img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .kontak-row-jabatan {
        display: inline-block; padding: 3px 10px; border-radius: 9999px;
        background: #eff6ff; color: #1e40af; font-size: 0.75rem; font-weight: 700;
    }
    .kontak-row-hp { text-align: center; font-family: monospace; font-weight: 600; color: var(--gray-900); }
    .kontak-row-wa-cell { text-align: center; }
    .btn-wa-sm {
        background: #25d366; color: white; padding: 4px 10px; border-radius: 6px; font-size: 0.75rem;
        font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;
    }
    .btn-wa-sm:hover { background: #1da851; color: white; }

    .empty-state {
        background: white; border-radius: var(--radius-lg); padding: 3rem; text-align: center;
        color: var(--gray-500); border: 1px solid var(--gray-200);
    }
    .empty-state svg { margin-bottom: 1rem; color: #94a3b8; }
    .empty-state h3 { color: var(--navy); font-weight: 700; margin: 0 0 0.5rem 0; }
    .empty-state p { margin: 0; font-size: 0.875rem; }
</style>
@endpush

@section('content')
<div class="admin-ui-scope" style="padding-top: 0.5rem;">

    <!-- Stat Cards Top Benchmark -->
    <div class="stat-cards-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </div>
            <div class="stat-info">
                <h4>Total Kontak Terdaftar</h4>
                <div class="value">{{ number_format($kontaks->total()) }}</div>
            </div>
        </div>

        <div class="stat-card regional">
            <div class="stat-icon" style="background: #eff6ff; color: #2563eb;">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"></path><path d="M2 12h20"></path></svg>
            </div>
            <div class="stat-info">
                <h4>Wilayah / Domisili</h4>
                <div class="value">{{ number_format(count($domisiliList)) }}</div>
            </div>
        </div>

        <div class="stat-card active">
            <div class="stat-icon" style="background: #ecfdf5; color: #059669;">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
            </div>
            <div class="stat-info">
                <h4>Kontak WhatsApp</h4>
                <div class="value">{{ number_format($kontaks->filter(fn($k) => !empty($k->no_hp))->count()) }}</div>
            </div>
        </div>
    </div>

    {{-- Filter Box --}}
    <div class="filter-card">
        <form method="GET" action="{{ route('admin.kontak.index') }}">
            <div class="filter-grid">
                <div class="form-group">
                    <label for="search">Cari Nama / Email / HP</label>
                    <input type="text" name="search" id="search" class="form-control" placeholder="Ketik kata kunci..." value="{{ request('search') }}">
                </div>

                <div class="form-group">
                    <label for="domisili">Wilayah / Domisili</label>
                    <select name="domisili" id="domisili" class="form-control select2-basic">
                        <option value="">-- Semua Wilayah --</option>
                        @foreach($domisiliList as $d)
                            <option value="{{ $d }}" {{ request('domisili') == $d ? 'selected' : '' }}>{{ $d }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="jabatan">Jabatan</label>
                    <select name="jabatan" id="jabatan" class="form-control select2-basic">
                        <option value="">-- Semua Jabatan --</option>
                        @foreach($jabatanList as $j)
                            <option value="{{ $j }}" {{ request('jabatan') == $j ? 'selected' : '' }}>{{ $j }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="filter-actions-spacer">Aksi</label>
                    <div class="filter-actions">
                        <button type="submit" class="btn-solid-navy">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            Filter
                        </button>
                        <a href="{{ route('admin.kontak.export', request()->query()) }}" class="btn-outline-secondary" title="Export Data Kontak ke Excel" onclick="Toast.fire({ icon: 'success', title: 'File Excel Direktori Kontak sedang diunduh...' })">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            Export Excel
                        </a>
                        @if(request()->anyFilled(['search', 'domisili', 'jabatan']))
                            <a href="{{ route('admin.kontak.index') }}" class="btn-outline-secondary">Reset</a>
                        @endif

                        <div class="view-mode-toggle">
                            <button type="button" class="btn-toggle-view" id="btnViewGrid" onclick="switchKontakView('grid')" title="Tampilan Kartu Grid">
                                <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                                Grid
                            </button>
                            <button type="button" class="btn-toggle-view" id="btnViewList" onclick="switchKontakView('list')" title="Tampilan Tabel List">
                                <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                                List
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Contact Content Container --}}
    @if($kontaks->count() > 0)
        <!-- Grid View -->
        <div class="contact-grid" id="kontakGridViewContainer">
            @foreach($kontaks as $k)
                @php
                    $waNumber = preg_replace('/[^0-9]/', '', $k->no_hp ?? '');
                    if (str_starts_with($waNumber, '0')) {
                        $waNumber = '62' . substr($waNumber, 1);
                    }
                    $hasPhoto = $k->foto_diri && Storage::disk('public')->exists($k->foto_diri);
                @endphp
                <div class="contact-card">
                    <div class="avatar-wrapper">
                        @if($hasPhoto)
                            <img src="{{ asset('storage/' . $k->foto_diri) }}" alt="{{ $k->nama_lengkap }}">
                        @else
                            <div class="avatar-placeholder">{{ strtoupper(substr($k->nama_lengkap ?? 'A', 0, 1)) }}</div>
                        @endif
                    </div>

                    <div class="contact-name">{{ $k->nama_lengkap ?? 'Anggota' }}</div>
                    <div class="contact-badge">{{ $k->jabatan ?? 'Anggota Karang Taruna' }}</div>

                    <div class="contact-info">
                        <div class="info-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <span title="{{ $k->domisili ?? 'Nasional' }}">{{ $k->domisili ?? 'Nasional' }}</span>
                        </div>

                        @if($k->email)
                        <div class="info-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            <span title="{{ $k->email }}">{{ $k->email }}</span>
                        </div>
                        @endif

                        @if($k->no_hp)
                        <div class="info-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            <span>{{ $k->no_hp }}</span>
                        </div>
                        @endif
                    </div>

                    <div class="contact-actions">
                        @if($waNumber)
                            <a href="https://wa.me/{{ $waNumber }}" target="_blank" class="btn-wa">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-1.144 4.177 4.287-1.124z"/></svg>
                                WhatsApp
                            </a>
                        @endif
                        @if($k->email)
                            <a href="mailto:{{ $k->email }}" class="btn-email" title="Kirim Email">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- List Table View -->
        <div id="kontakListViewContainer">
            <table class="kontak-table">
                <thead>
                    <tr>
                        <th class="col-no">NO</th>
                        <th>NAMA LENGKAP</th>
                        <th>JABATAN</th>
                        <th>DOMISILI</th>
                        <th>EMAIL</th>
                        <th class="col-wa">NO. WHATSAPP</th>
                        <th class="col-wa">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kontaks as $index => $k)
                        @php
                            $waNumber = preg_replace('/[^0-9]/', '', $k->no_hp ?? '');
                            if (str_starts_with($waNumber, '0')) {
                                $waNumber = '62' . substr($waNumber, 1);
                            }
                            $hasPhoto = $k->foto_diri && Storage::disk('public')->exists($k->foto_diri);
                        @endphp
                        <tr>
                            <td class="kontak-row-no">{{ $kontaks->firstItem() + $index }}</td>
                            <td>
                                <div class="kontak-row-name">
                                    <div class="kontak-row-avatar">
                                        @if($hasPhoto)
                                            <img src="{{ asset('storage/' . $k->foto_diri) }}" alt="{{ $k->nama_lengkap }}">
                                        @else
                                            {{ strtoupper(substr($k->nama_lengkap ?? 'A', 0, 1)) }}
                                        @endif
                                    </div>
                                    <span style="font-weight: 700; color: var(--navy);">{{ $k->nama_lengkap ?? 'Anggota' }}</span>
                                </div>
                            </td>
                            <td><span class="kontak-row-jabatan">{{ $k->jabatan ?? 'Anggota' }}</span></td>
                            <td style="color: var(--gray-700);">{{ $k->domisili ?? 'Nasional' }}</td>
                            <td style="color: var(--gray-700);">{{ $k->email ?? '-' }}</td>
                            <td class="kontak-row-hp">{{ $k->no_hp ?? '-' }}</td>
                            <td class="kontak-row-wa-cell">
                                @if($waNumber)
                                    <a href="https://wa.me/{{ $waNumber }}" target="_blank" class="btn-wa-sm">
                                        <svg viewBox="0 0 24 24" width="13" height="13" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-1.144 4.177 4.287-1.124z"/></svg>
                                        WA
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1.5rem;">
            {{ $kontaks->links() }}
        </div>
    @else
        <div class="empty-state">
            <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <h3>Tidak ada data kontak ditemukan</h3>
            <p>Coba ubah kata kunci pencarian atau filter domisili/jabatan.</p>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function switchKontakView(mode) {
        const gridView = document.getElementById('kontakGridViewContainer');
        const listView = document.getElementById('kontakListViewContainer');
        const btnGrid = document.getElementById('btnViewGrid');
        const btnList = document.getElementById('btnViewList');

        if (mode === 'list') {
            if (gridView) gridView.style.display = 'none';
            if (listView) {
                listView.style.display = 'block';
                listView.classList.remove('animate-view-fade');
                void listView.offsetWidth;
                listView.classList.add('animate-view-fade');
            }
            btnList?.classList.add('active');
            btnGrid?.classList.remove('active');
            localStorage.setItem('siktn_view_mode_kontak', 'list');
        } else {
            if (listView) listView.style.display = 'none';
            if (gridView) {
                gridView.style.display = 'grid';
                gridView.classList.remove('animate-view-fade');
                void gridView.offsetWidth;
                gridView.classList.add('animate-view-fade');
            }
            btnGrid?.classList.add('active');
            btnList?.classList.remove('active');
            localStorage.setItem('siktn_view_mode_kontak', 'grid');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof $.fn.select2 !== 'undefined') {
            $('.select2-basic').select2({
                minimumResultsForSearch: -1,
                width: '100%'
            });
        }

        const savedMode = localStorage.getItem('siktn_view_mode_kontak') || 'grid';
        switchKontakView(savedMode);
    });
</script>
@endpush