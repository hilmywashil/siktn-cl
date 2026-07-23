@extends('admin.layouts.admin-layout')

@section('title', ($tipe == 'masuk' ? 'Surat Masuk' : 'Surat Keluar') . ' - SIKTN Admin')
@section('page-title', 'Sekretariat - ' . ($tipe == 'masuk' ? 'Surat Masuk' : 'Surat Keluar'))

@push('styles')
<style>
    @keyframes select2DropdownFadeIn {
        from {
            opacity: 0;
            transform: translateY(-8px) scale(0.97);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .select2-container--default .select2-selection--single {
        height: 40px; padding: 0.35rem 0.75rem; font-size: 0.8125rem; font-weight: 600;
        color: var(--navy); background-color: #fff; border: 1px solid var(--gray-300);
        border-radius: var(--radius-md); display: flex; align-items: center;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); min-width: 160px;
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
        --navy: #022648;
        --navy-dark: #01162f;
        --navy-light: #0a3a6b;
        --gold: #b7830f;
        --green: #059669;
        --blue: #2563eb;
        --red: #dc2626;
        --amber: #d97706;
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-300: #d1d5db;
        --gray-500: #6b7280;
        --gray-700: #374151;
        --gray-900: #111827;
        --radius-sm: 4px;
        --radius-md: 6px;
        --radius-lg: 8px;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    /* Summary Stat Cards */
    .stat-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.25rem;
        margin-bottom: 1.75rem;
    }

    .stat-card {
        background: #ffffff;
        border-radius: var(--radius-lg);
        padding: 1.25rem;
        border: 1px solid var(--gray-200);
        box-shadow: 0 1px 3px rgba(2, 38, 72, 0.05);
        display: flex;
        align-items: center;
        gap: 1rem;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 4px;
        background: var(--navy);
    }
    .stat-card.approved::before { background: var(--green); }
    .stat-card.pending::before { background: var(--amber); }

    .stat-icon {
        width: 44px; height: 44px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        background: var(--gray-100);
        color: var(--navy);
    }

    .stat-info h4 {
        margin: 0;
        font-size: 0.75rem;
        color: var(--gray-500);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-info .value {
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--navy);
        margin-top: 0.2rem;
        font-family: 'Montserrat', sans-serif;
    }

    /* Buttons Benchmark */
    .btn-solid-navy {
        background: var(--navy);
        color: white;
        padding: 0.55rem 1.15rem;
        border-radius: var(--radius-md);
        font-weight: 600;
        font-size: 0.875rem;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 4px rgba(2, 38, 72, 0.12);
    }

    .btn-solid-navy:hover {
        background: var(--navy-light);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(2, 38, 72, 0.2);
    }

    .btn-outline-secondary {
        background: white;
        color: var(--gray-700);
        padding: 0.55rem 1.15rem;
        border-radius: var(--radius-md);
        font-weight: 600;
        font-size: 0.875rem;
        border: 1px solid var(--gray-300);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-outline-secondary:hover {
        background: var(--gray-100);
        color: var(--navy);
    }

    /* Filter Box */
    .filter-card {
        background: white;
        border-radius: var(--radius-lg);
        padding: 1.25rem 1.5rem;
        border: 1px solid var(--gray-200);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        margin-bottom: 1.5rem;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.25rem;
        align-items: flex-end;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }

    .form-group label {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--navy);
    }

    .form-control {
        padding: 0.55rem 0.875rem;
        border-radius: var(--radius-md);
        border: 1px solid var(--gray-300);
        font-size: 0.875rem;
        outline: none;
        background: white;
        transition: all 0.2s ease;
        width: 100%;
    }

    .form-control:focus {
        border-color: var(--navy);
        box-shadow: 0 0 0 3px rgba(2, 38, 72, 0.1);
    }

    /* Filter Tabs Header */
    .filter-tabs-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .filter-tabs {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .filter-tab {
        padding: 0.5rem 1rem;
        border-radius: var(--radius-md);
        border: 1px solid var(--gray-200);
        background: white;
        color: var(--gray-700);
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-tab:hover {
        background: var(--gray-100);
        border-color: var(--gray-300);
    }

    .filter-tab.active {
        background: var(--navy);
        color: white;
        border-color: var(--navy);
    }

    .tab-badge {
        background: rgba(0, 0, 0, 0.08);
        padding: 0.125rem 0.5rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .filter-tab.active .tab-badge {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }

    .tab-badge-danger {
        background: #fee2e2;
        color: #991b1b;
        padding: 0.125rem 0.5rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    /* Table Container */
    .table-container {
        background: white;
        border-radius: var(--radius-lg);
        border: 1px solid var(--gray-200);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
    }

    .table thead {
        background: var(--gray-50);
        border-bottom: 1px solid var(--gray-200);
    }

    .table th {
        padding: 0.875rem 1rem;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--gray-700);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .table td {
        padding: 1rem;
        border-bottom: 1px solid var(--gray-100);
        font-size: 0.875rem;
        color: var(--gray-900);
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background: var(--gray-50);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.35rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-badge.pending { background: #fef3c7; color: #d97706; }
    .status-badge.approved { background: #d1fae5; color: #065f46; }
    .status-badge.rejected { background: #fee2e2; color: #991b1b; }
    .status-badge.draft { background: #f3f4f6; color: #4b5563; }

    /* Action Trigger (⋮) & Floating Dropdown */
    .aksi-wrapper {
        position: relative;
        display: inline-block;
    }

    .btn-aksi-trigger {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--navy);
        color: #ffffff;
        border: none;
        border-radius: var(--radius-md);
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 1px 3px rgba(2, 38, 72, 0.12);
    }

    .btn-aksi-trigger:hover {
        background: var(--navy-light);
        transform: scale(1.08) translateY(-1px);
        box-shadow: 0 4px 12px rgba(2, 38, 72, 0.25);
    }

    .aksi-dropdown {
        display: block;
        position: fixed;
        min-width: 175px;
        background: #ffffff;
        border: 1px solid var(--gray-200);
        border-radius: var(--radius-md);
        box-shadow: 0 14px 32px rgba(2, 38, 72, 0.18);
        padding: 6px;
        z-index: 9999;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-8px) scale(0.96);
        transition: opacity 0.18s cubic-bezier(0.16, 1, 0.3, 1), transform 0.18s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.18s;
        pointer-events: none;
    }

    .aksi-dropdown.is-open {
        opacity: 1;
        visibility: visible;
        transform: translateY(0) scale(1);
        pointer-events: auto;
    }

    .aksi-item {
        display: flex;
        align-items: center;
        gap: 9px;
        width: 100%;
        padding: 0.55rem 0.65rem;
        font-size: 0.8125rem;
        font-weight: 600;
        border-radius: var(--radius-sm);
        color: var(--gray-900);
        text-decoration: none !important;
        border: none;
        background: transparent;
        text-align: left;
        cursor: pointer;
        transition: all 0.18s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .aksi-item:hover {
        background: var(--gray-100);
        transform: translateX(4px);
    }

    .aksi-item.aksi-view:hover { color: var(--navy); }
    .aksi-item.aksi-edit:hover { color: var(--blue); }
    .aksi-item.aksi-delete:hover { color: var(--red); background: #fef2f2; }

    .aksi-divider {
        height: 1px;
        background: var(--gray-200);
        margin: 4px 0;
    }

    /* Modals Custom */
    .modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15, 23, 42, 0.6);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        padding: 1rem;
    }
    
    .modal-overlay.active {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: var(--radius-lg);
        max-width: 600px;
        width: 100%;
        padding: 1.5rem;
        max-height: 90vh;
        overflow-y: auto;
    }

    .timeline {
        border-left: 2px solid var(--gray-200);
        padding-left: 1.25rem;
        margin-top: 1rem;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 1.25rem;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -1.65rem;
        top: 0.25rem;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: var(--navy);
    }
</style>
@endpush

@section('content')
<div class="admin-ui-scope" style="padding-top: 0.5rem;">

    <!-- Stat Cards Top Benchmark -->
    <div class="stat-cards-grid">
        <div class="stat-card approved">
            <div class="stat-icon" style="background: #ecfdf5; color: #059669;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            </div>
            <div class="stat-info">
                <h4>Surat Terbit Bulan Ini</h4>
                <div class="value">{{ $totalTerbitBulanIni }}</div>
            </div>
        </div>

        <div class="stat-card pending">
            <div class="stat-icon" style="background: #fffbeb; color: #d97706;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            </div>
            <div class="stat-info">
                <h4>Pending TTD Pimpinan</h4>
                <div class="value">{{ $totalPendingTTD }}</div>
            </div>
        </div>
    </div>

    <!-- Filter Box -->
    <div class="filter-card">
        <form action="{{ route('admin.sekretariat.surat.index') }}" method="GET">
            <input type="hidden" name="tipe" value="{{ $tipe }}">
            <input type="hidden" name="klasifikasi" value="{{ $klasifikasi }}">
            <div class="filter-grid">
                <div class="form-group">
                    <label for="search">Cari No. Surat / Perihal</label>
                    <input type="text" name="search" id="search" class="form-control" placeholder="Ketik kata kunci..." value="{{ request('search') }}">
                </div>

                <div class="form-group">
                    <label for="status">Status Surat</label>
                    <select name="status" id="status" class="form-control select2-basic">
                        <option value="">-- Semua Status --</option>
                        <option value="Pending TTD" {{ request('status') == 'Pending TTD' ? 'selected' : '' }}>Pending TTD</option>
                        <option value="Terbit" {{ request('status') == 'Terbit' ? 'selected' : '' }}>Terbit</option>
                        <option value="Revisi" {{ request('status') == 'Revisi' ? 'selected' : '' }}>Revisi</option>
                        <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>

                <div style="display: flex; gap: 0.5rem; align-items: flex-end;">
                    <button type="submit" class="btn-solid-navy" style="white-space: nowrap;">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        Filter
                    </button>
                    @if(request()->anyFilled(['status', 'search']))
                        <a href="{{ route('admin.sekretariat.surat.index', ['tipe' => $tipe, 'klasifikasi' => $klasifikasi]) }}" class="btn-outline-secondary" style="white-space: nowrap;">Reset</a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Filter Tabs & Main Action -->
    <div class="filter-tabs-header">
        <div class="filter-tabs">
            <a href="{{ route('admin.sekretariat.surat.index', ['tipe' => $tipe, 'klasifikasi' => 'internal']) }}" class="filter-tab {{ $klasifikasi == 'internal' ? 'active' : '' }}">
                Surat Internal <span class="tab-badge">{{ $countInternal }}</span>
            </a>
            <a href="{{ route('admin.sekretariat.surat.index', ['tipe' => $tipe, 'klasifikasi' => 'eksternal']) }}" class="filter-tab {{ $klasifikasi == 'eksternal' ? 'active' : '' }}">
                Surat Eksternal <span class="tab-badge">{{ $countEksternal }}</span>
            </a>
            <a href="{{ route('admin.sekretariat.surat.index', ['tipe' => $tipe, 'klasifikasi' => 'penting']) }}" class="filter-tab {{ $klasifikasi == 'penting' ? 'active' : '' }}">
                Surat Penting 
                <span class="tab-badge">{{ $countPenting }}</span>
                @if($countPentingPending > 0)
                    <span class="tab-badge-danger">{{ $countPentingPending }} Pending</span>
                @endif
            </a>
        </div>

        <div>
            <button type="button" class="btn-solid-navy" onclick="openCreateModal()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah {{ $tipe == 'masuk' ? 'Surat Masuk' : 'Surat Keluar' }} Baru
            </button>
        </div>
    </div>

    <!-- Table Container -->
    <div class="table-container">
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>NO. SURAT</th>
                        <th>KLASIFIKASI</th>
                        <th>PERIHAL & {{ $tipe == 'masuk' ? 'PENGIRIM' : 'TUJUAN' }}</th>
                        <th>TANGGAL</th>
                        <th>STATUS</th>
                        <th>LAMPIRAN (PDF/WORD)</th>
                        <th style="text-align: center; width: 80px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($surats as $item)
                    <tr>
                        <td>
                            <strong style="color: var(--navy);">{{ $item->nomor_surat }}</strong>
                        </td>
                        <td>
                            <span style="font-size: 0.75rem; text-transform: uppercase; padding: 3px 8px; background: var(--gray-200); border-radius: 4px; font-weight: 600;">{{ ucfirst($item->klasifikasi) }}</span>
                        </td>
                        <td>
                            <div style="font-weight: 600;">{{ $item->perihal }}</div>
                            <div style="font-size: 0.8rem; color: var(--gray-500);">{{ $tipe == 'masuk' ? 'Pengirim' : 'Tujuan' }}: {{ $item->pengirim_tujuan }}</div>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                        <td>
                            @if($item->status == 'Pending TTD')
                                <span class="status-badge pending">● Pending TTD</span>
                            @elseif($item->status == 'Terbit')
                                <span class="status-badge approved">● Terbit</span>
                            @elseif($item->status == 'Revisi')
                                <span class="status-badge rejected">● Revisi</span>
                            @else
                                <span class="status-badge draft">● Draft</span>
                            @endif
                        </td>
                        <td>
                            @if($item->file_lampiran)
                                <a href="{{ Storage::url($item->file_lampiran) }}" target="_blank" class="btn-outline-secondary" style="padding: 0.35rem 0.75rem; font-size: 0.8rem;" onclick="Toast.fire({ icon: 'success', title: 'Berkas surat sedang diunduh...' })">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                                    Unduh File PDF/Word
                                </a>
                            @elseif($item->link_drive)
                                <a href="{{ $item->link_drive }}" target="_blank" style="color: var(--blue); text-decoration: none; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 4px;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                                    Drive Link
                                </a>
                            @else
                                <span style="color: var(--gray-500); font-size: 0.8rem;">-</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <!-- Action Dropdown Trigger (⋮) -->
                            <div class="aksi-wrapper">
                                <button type="button" class="btn-aksi-trigger" data-target="dropdown-surat-{{ $item->id }}" aria-label="Menu Aksi">
                                    <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                        <circle cx="12" cy="5" r="1.75"></circle>
                                        <circle cx="12" cy="12" r="1.75"></circle>
                                        <circle cx="12" cy="19" r="1.75"></circle>
                                    </svg>
                                </button>

                                <div class="aksi-dropdown" id="dropdown-surat-{{ $item->id }}">
                                    <button type="button" class="aksi-item aksi-view" onclick="viewAuditTrail({{ $item->id }})">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                        Audit Trail
                                    </button>
                                    
                                    <button type="button" class="aksi-item aksi-edit" onclick="openStatusModal({{ $item->id }}, '{{ $item->status }}')">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        Edit Status
                                    </button>

                                    <div class="aksi-divider"></div>

                                    <button type="button" class="aksi-item aksi-delete" onclick="confirmDeleteSurat({{ $item->id }}, '{{ addslashes($item->nomor_surat) }}')">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        Hapus Surat
                                    </button>
                                    <form id="delete-form-{{ $item->id }}" action="{{ route('admin.sekretariat.surat.destroy', $item->id) }}" method="POST" style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 3rem; color: var(--gray-500);">Belum ada data {{ $tipe == 'masuk' ? 'Surat Masuk' : 'Surat Keluar' }} pada kriteria ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modals -->
    <!-- Modal 1: Audit Trail -->
    <div class="modal-overlay" id="modalAuditTrail">
        <div class="modal-content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--navy); margin: 0;">Log Audit Trail Surat</h3>
                <button type="button" onclick="closeModal('modalAuditTrail')" style="background: none; border: none; font-size: 1.2rem; cursor: pointer;">&times;</button>
            </div>
            <div id="auditTrailContent">
                <div style="text-align: center; padding: 2rem; color: var(--gray-500);">Memuat data audit trail...</div>
            </div>
        </div>
    </div>

    <!-- Modal 2: Create Surat -->
    <div class="modal-overlay" id="modalCreateSurat">
        <div class="modal-content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--navy); margin: 0;">Catat {{ $tipe == 'masuk' ? 'Surat Masuk' : 'Surat Keluar' }} Baru</h3>
                <button type="button" onclick="closeModal('modalCreateSurat')" style="background: none; border: none; font-size: 1.2rem; cursor: pointer;">&times;</button>
            </div>
            <form action="{{ route('admin.sekretariat.surat.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="tipe" value="{{ $tipe }}">
                
                <div class="form-group">
                    <label>Nomor Surat</label>
                    <input type="text" name="nomor_surat" class="form-control" placeholder="Contoh: 001/SK/PNKT/VII/2026" required>
                </div>

                <div class="form-group">
                    <label>Klasifikasi Surat</label>
                    <select name="klasifikasi" class="form-control select2-basic" style="width: 100%;" required>
                        <option value="internal">Surat Internal</option>
                        <option value="eksternal">Surat Eksternal</option>
                        <option value="penting">Surat Penting</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Perihal</label>
                    <input type="text" name="perihal" class="form-control" placeholder="Perihal surat..." required>
                </div>

                <div class="form-group">
                    <label>{{ $tipe == 'masuk' ? 'Pengirim (Instansi/Nama)' : 'Tujuan (Instansi/Nama)' }}</label>
                    <input type="text" name="pengirim_tujuan" class="form-control" placeholder="{{ $tipe == 'masuk' ? 'Nama/Instansi Pengirim' : 'Nama/Instansi Tujuan' }}" required>
                </div>

                <div class="form-group">
                    <label>Tanggal Surat</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="form-group">
                    <label>Upload File Lampiran (PDF / Word)</label>
                    <input type="file" name="file_lampiran" class="form-control" accept=".pdf,.doc,.docx">
                </div>

                <div class="form-group">
                    <label>Link Google Drive (Opsional)</label>
                    <input type="url" name="link_drive" class="form-control" placeholder="https://drive.google.com/...">
                </div>

                <div class="form-group">
                    <label>Status Awal</label>
                    <select name="status" class="form-control select2-basic" style="width: 100%;">
                        <option value="Draft">Draft</option>
                        <option value="Pending TTD">Pending TTD</option>
                        <option value="Terbit">Terbit</option>
                    </select>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.5rem;">
                    <button type="button" onclick="closeModal('modalCreateSurat')" class="btn-outline-secondary">Batal</button>
                    <button type="submit" class="btn-solid-navy">Simpan Surat</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal 3: Update Status Surat -->
    <div class="modal-overlay" id="modalStatusSurat">
        <div class="modal-content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--navy); margin: 0;">Ubah Status Surat</h3>
                <button type="button" onclick="closeModal('modalStatusSurat')" style="background: none; border: none; font-size: 1.2rem; cursor: pointer;">&times;</button>
            </div>
            <form id="formUpdateStatus" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="form-group">
                    <label>Status Baru</label>
                    <select name="status" id="statusSelect" class="form-control select2-basic" style="width: 100%;" required>
                        <option value="Draft">Draft</option>
                        <option value="Pending TTD">Pending TTD</option>
                        <option value="Terbit">Terbit</option>
                        <option value="Revisi">Revisi</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Catatan / Alasan Perubahan (Masuk ke Audit Trail)</label>
                    <textarea name="catatan" class="form-control" rows="3" placeholder="Contoh: Menunggu tanda tangan ketua umum..."></textarea>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.5rem;">
                    <button type="button" onclick="closeModal('modalStatusSurat')" class="btn-outline-secondary">Batal</button>
                    <button type="submit" class="btn-solid-navy">Perbarui Status</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof $.fn.select2 !== 'undefined') {
            $('.select2-basic').select2({
                minimumResultsForSearch: -1,
                width: '100%'
            });
        }

        let activeDropdown = null;

        // Position & Toggle Dropdown Trigger (⋮)
        document.querySelectorAll('.btn-aksi-trigger').forEach(button => {
            button.addEventListener('click', function (e) {
                e.stopPropagation();
                const targetId = this.getAttribute('data-target');
                const dropdown = document.getElementById(targetId);

                if (activeDropdown && activeDropdown !== dropdown) {
                    activeDropdown.classList.remove('is-open');
                }

                if (dropdown.classList.contains('is-open')) {
                    dropdown.classList.remove('is-open');
                    activeDropdown = null;
                } else {
                    const rect = this.getBoundingClientRect();
                    dropdown.style.top = (rect.bottom + 4) + 'px';
                    dropdown.style.left = (rect.right - 175) + 'px';
                    dropdown.classList.add('is-open');
                    activeDropdown = dropdown;
                }
            });
        });

        // Close dropdown on click outside
        document.addEventListener('click', function () {
            if (activeDropdown) {
                activeDropdown.classList.remove('is-open');
                activeDropdown = null;
            }
        });

        // Close dropdown on scroll
        window.addEventListener('scroll', function () {
            if (activeDropdown) {
                activeDropdown.classList.remove('is-open');
                activeDropdown = null;
            }
        }, true);
    });

    function openCreateModal() {
        document.getElementById('modalCreateSurat').classList.add('active');
    }

    function openStatusModal(id, currentStatus) {
        const form = document.getElementById('formUpdateStatus');
        form.action = `/admin/sekretariat/surat/${id}/status`;
        $('#statusSelect').val(currentStatus).trigger('change');
        document.getElementById('modalStatusSurat').classList.add('active');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.remove('active');
    }

    function confirmDeleteSurat(id, nomor) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Hapus Surat?',
                text: `Apakah Anda yakin ingin menghapus surat no. ${nomor}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#f3f4f6',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        } else if (confirm(`Apakah Anda yakin ingin menghapus surat no. ${nomor}?`)) {
            document.getElementById('delete-form-' + id).submit();
        }
    }

    function viewAuditTrail(id) {
        document.getElementById('modalAuditTrail').classList.add('active');
        document.getElementById('auditTrailContent').innerHTML = '<div style="text-align: center; padding: 2rem; color: var(--gray-500);">Memuat data audit trail...</div>';
        
        fetch(`/admin/sekretariat/surat/${id}/audit-trail`)
            .then(res => res.json())
            .then(data => {
                let html = '<div class="timeline">';
                if (data.length === 0) {
                    html += '<p style="color: var(--gray-500);">Belum ada riwayat perubahan status.</p>';
                } else {
                    data.forEach(item => {
                        html += `
                            <div class="timeline-item">
                                <div style="font-weight: 700; color: var(--navy);">${item.status_baru}</div>
                                <div style="font-size: 0.8rem; color: var(--gray-500);">${item.created_at} oleh <strong>${item.user_name}</strong></div>
                                ${item.catatan ? `<div style="font-size: 0.85rem; background: var(--gray-100); padding: 0.5rem; border-radius: 4px; margin-top: 4px;">${item.catatan}</div>` : ''}
                            </div>
                        `;
                    });
                }
                html += '</div>';
                document.getElementById('auditTrailContent').innerHTML = html;
            })
            .catch(() => {
                document.getElementById('auditTrailContent').innerHTML = '<p style="color: var(--red);">Gagal memuat log audit trail.</p>';
            });
    }
</script>
@endpush
