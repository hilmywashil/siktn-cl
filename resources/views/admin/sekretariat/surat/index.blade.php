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

    .surat-title-link {
        font-weight: 700;
        color: var(--navy);
        text-decoration: none;
        transition: color 0.15s ease, text-decoration 0.15s ease;
        display: inline-block;
        line-height: 1.35;
    }
    .surat-title-link:hover {
        color: var(--blue);
        text-decoration: underline;
    }
    .badge-link-type {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 0.725rem;
        font-weight: 600;
        padding: 2px 7px;
        border-radius: 4px;
        background: #f1f5f9;
        color: #475569;
        margin-top: 4px;
    }
    .badge-link-type.drive {
        background: #eff6ff;
        color: #1d4ed8;
    }
    .badge-link-type.pdf {
        background: #fef2f2;
        color: #b91c1c;
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

    /* Modals Custom Standard & Professional */
    .modal-overlay {
        --navy: #022648; --navy-dark: #01162f; --navy-light: #0a3a6b;
        --gray-50: #f9fafb; --gray-100: #f3f4f6; --gray-200: #e5e7eb; --gray-300: #d1d5db;
        --gray-500: #6b7280; --gray-700: #374151; --gray-900: #111827;
        --radius-sm: 4px; --radius-md: 6px; --radius-lg: 8px;
        position: fixed !important;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(2, 38, 72, 0.48);
        backdrop-filter: blur(4px);
        display: none !important;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        padding: 1.5rem;
        opacity: 0 !important;
        visibility: hidden !important;
        pointer-events: none !important;
    }

    .modal-overlay.active {
        display: flex !important;
        visibility: visible !important;
        opacity: 1 !important;
        pointer-events: auto !important;
    }

    .modal-content-lg {
        background: #ffffff;
        border-radius: 12px;
        max-width: 680px;
        width: 100%;
        box-shadow: 0 24px 48px rgba(2, 38, 72, 0.25);
        border: 1px solid rgba(2, 38, 72, 0.1);
        overflow: hidden;
        transform: scale(0.94) translateY(12px);
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        max-height: 90vh;
        display: flex;
        flex-direction: column;
    }

    .modal-content-lg form {
        display: flex;
        flex-direction: column;
        flex: 1 1 auto;
        min-height: 0;
        overflow: hidden;
        margin: 0;
    }

    .modal-overlay.active .modal-content-lg {
        transform: scale(1) translateY(0);
    }

    .modal-header-prof {
        padding: 1.2rem 1.5rem;
        background: linear-gradient(135deg, #022648 0%, #01162f 100%);
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-shrink: 0;
    }

    .modal-body-prof {
        padding: 1.5rem;
        overflow-y: auto;
        flex: 1 1 auto;
        min-height: 0;
        max-height: calc(88vh - 130px);
    }

    .modal-footer-prof {
        padding: 1rem 1.5rem;
        background: #f8f9fc;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
        flex-shrink: 0;
        margin-top: auto;
    }

    .swal-high-zindex {
        z-index: 999999 !important;
    }

    .modal-overlay .form-control {
        border: 1px solid #cbd5e1 !important; border-radius: 6px !important;
        padding: 0.55rem 0.875rem !important; font-size: 0.875rem !important;
        background-color: #ffffff !important; color: #0f172a !important; outline: none !important;
        transition: border-color 0.2s, box-shadow 0.2s !important; width: 100% !important;
    }

    .modal-overlay .form-control:focus {
        border-color: #022648 !important; box-shadow: 0 0 0 3px rgba(2, 38, 72, 0.12) !important;
    }

    .modal-overlay .btn-solid-navy {
        background-color: #022648 !important; color: #ffffff !important; border: none !important;
        padding: 0.55rem 1.25rem !important; border-radius: 6px !important; font-weight: 700 !important;
        cursor: pointer !important; display: inline-flex !important; align-items: center !important; gap: 0.5rem !important;
    }

    .modal-overlay .btn-solid-navy:hover { background-color: #01162f !important; }

    .modal-overlay .btn-outline-secondary {
        background-color: #ffffff !important; color: #374151 !important; border: 1px solid #d1d5db !important;
        padding: 0.55rem 1.25rem !important; border-radius: 6px !important; font-weight: 600 !important;
        cursor: pointer !important; display: inline-flex !important; align-items: center !important; gap: 0.5rem !important;
    }

    .modal-overlay .btn-outline-secondary:hover { background-color: #f3f4f6 !important; color: #022648 !important; }

    .form-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
    }

    .form-group-full {
        grid-column: 1 / -1;
    }

    .file-upload-zone {
        border: 2px dashed #cbd5e1;
        border-radius: var(--radius-md);
        padding: 1rem;
        background: #f8fafc;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    }

    .file-upload-zone:hover {
        border-color: var(--navy);
        background: #f1f5f9;
    }

    .file-upload-zone input[type="file"] {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        opacity: 0;
        cursor: pointer;
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

        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            <button type="button" class="btn-solid-navy" onclick="openCreateModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah {{ $tipe == 'masuk' ? 'Surat Masuk' : 'Surat Keluar' }}
            </button>
            <button type="button" onclick="openImportSuratModal()" style="background: #059669; color: white; border: none; padding: 0.55rem 1rem; border-radius: var(--radius-md); font-weight: 600; font-size: 0.875rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s;" onmouseover="this.style.background='#047857'" onmouseout="this.style.background='#059669'">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                Import Excel
            </button>
            <button type="button" onclick="openBulkPdfModal()" style="background: #b7830f; color: white; border: none; padding: 0.55rem 1rem; border-radius: var(--radius-md); font-weight: 600; font-size: 0.875rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s;" onmouseover="this.style.background='#966a0c'" onmouseout="this.style.background='#b7830f'">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
                Bulk Upload Multi-PDF
            </button>
        </div>
    </div>

    <!-- Table Container -->
    <div class="table-container">
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">
                            <input type="checkbox" id="check-all-surat" style="cursor: pointer; width: 16px; height: 16px;">
                        </th>
                        <th>NO. SURAT</th>
                        <th>KLASIFIKASI</th>
                        <th>PERIHAL & {{ $tipe == 'masuk' ? 'PENGIRIM' : 'TUJUAN' }}</th>
                        <th>TANGGAL</th>
                        <th>STATUS</th>
                        <th style="text-align: center; width: 80px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($surats as $item)
                    <tr>
                        <td style="text-align: center;">
                            <input type="checkbox" class="check-surat-item" value="{{ $item->id }}" style="cursor: pointer; width: 16px; height: 16px;">
                        </td>
                        <td>
                            <strong style="color: var(--navy);">{{ $item->nomor_surat }}</strong>
                        </td>
                        <td>
                            <span style="font-size: 0.75rem; text-transform: uppercase; padding: 3px 8px; background: var(--gray-200); border-radius: 4px; font-weight: 600;">{{ ucfirst($item->klasifikasi) }}</span>
                        </td>
                        <td>
                            <div>
                                @if($item->link_drive)
                                    <a href="{{ $item->link_drive }}" target="_blank" class="surat-title-link" title="Buka Arsip Google Drive: {{ $item->perihal }}">
                                        {{ $item->perihal }} ↗
                                    </a>
                                @elseif($item->file_lampiran)
                                    <a href="javascript:void(0)" onclick="previewSuratLampiran('{{ Storage::url($item->file_lampiran) }}', '{{ addslashes($item->perihal) }}')" class="surat-title-link" title="Pratinjau File PDF: {{ $item->perihal }}">
                                        {{ $item->perihal }}
                                    </a>
                                @else
                                    <span style="font-weight: 700; color: var(--navy);">{{ $item->perihal }}</span>
                                @endif
                            </div>

                            <div style="font-size: 0.8rem; color: var(--gray-500); margin-top: 2px;">
                                {{ $tipe == 'masuk' ? 'Pengirim' : 'Tujuan' }}: <strong>{{ $item->pengirim_tujuan }}</strong>
                            </div>

                            <div style="margin-top: 4px; display: flex; gap: 4px; flex-wrap: wrap;">
                                @if($item->file_lampiran)
                                    <span class="badge-link-type pdf" onclick="previewSuratLampiran('{{ Storage::url($item->file_lampiran) }}', '{{ addslashes($item->perihal) }}')" style="cursor: pointer;">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                        PDF Arsip
                                    </span>
                                @endif
                                @if($item->link_drive)
                                    <a href="{{ $item->link_drive }}" target="_blank" class="badge-link-type drive" style="text-decoration: none;">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                                        Google Drive
                                    </a>
                                @endif
                            </div>
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
                                    @if($item->file_lampiran)
                                    <button type="button" class="aksi-item aksi-view" onclick="previewSuratLampiran('{{ Storage::url($item->file_lampiran) }}', '{{ addslashes($item->perihal) }}')">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        Pratinjau File
                                    </button>
                                    <a href="{{ Storage::url($item->file_lampiran) }}" target="_blank" class="aksi-item aksi-view">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                        Unduh File
                                    </a>
                                    @endif

                                    @if($item->link_drive)
                                    <button type="button" class="aksi-item aksi-view" onclick="previewSuratDrive('{{ $item->link_drive }}', '{{ addslashes($item->perihal) }}')">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        Pratinjau Drive
                                    </button>
                                    <a href="{{ $item->link_drive }}" target="_blank" class="aksi-item aksi-view">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                                        Buka Drive
                                    </a>
                                    @endif

                                    @if($item->file_lampiran || $item->link_drive)
                                    <div class="aksi-divider"></div>
                                    @endif

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
                        <td colspan="6" style="text-align: center; padding: 3rem; color: var(--gray-500);">Belum ada data {{ $tipe == 'masuk' ? 'Surat Masuk' : 'Surat Keluar' }} pada kriteria ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Floating / Sticky Bulk Action Bar for Surat -->
    <div id="bulk-action-bar-surat" style="display: none; position: sticky; bottom: 20px; z-index: 99; background: #022648; color: white; padding: 12px 20px; border-radius: 8px; margin-top: 1.25rem; align-items: center; justify-content: space-between; box-shadow: 0 8px 24px rgba(2, 38, 72, 0.25);">
        <div style="display: flex; align-items: center; gap: 10px; font-size: 0.875rem;">
            <span style="background: #b7830f; color: white; width: 26px; height: 26px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem;" id="selected-surat-count">0</span>
            <strong>Surat Terpilih</strong>
        </div>
        <div style="display: flex; gap: 10px;">
            <button type="button" onclick="executeBulkDownloadSurat()" style="background: #059669; color: white; border: none; padding: 7px 16px; border-radius: 6px; font-weight: 700; font-size: 0.8rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: background 0.2s;" onmouseover="this.style.background='#047857'" onmouseout="this.style.background='#059669'">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Download Terpilih (ZIP)
            </button>
            <button type="button" onclick="executeBulkDeleteSurat()" style="background: #dc2626; color: white; border: none; padding: 7px 16px; border-radius: 6px; font-weight: 700; font-size: 0.8rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: background 0.2s;" onmouseover="this.style.background='#b91c1c'" onmouseout="this.style.background='#dc2626'">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                Hapus Terpilih
            </button>
        </div>
    </div>

    <form id="bulk-delete-surat-form" action="{{ route('admin.sekretariat.surat.bulk-delete') }}" method="POST" style="display:none;">
        @csrf
    </form>

    <form id="bulk-download-surat-form" action="{{ route('admin.sekretariat.surat.bulk-download') }}" method="POST" style="display:none;">
        @csrf
    </form>

    {{ $surats->appends(request()->query())->links() }}

    <!-- ============================== -->
    <!-- Modal 1: Create Surat -->
    <!-- ============================== -->
    <div class="modal-overlay" id="modalCreateSurat" onclick="if(event.target===this) closeModal('modalCreateSurat')">
        <div class="modal-content-lg">
            <div class="modal-header-prof">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(255,255,255,0.15); display: flex; align-items: center; justify-content: center;">
                        <svg viewBox="0 0 24 24" width="20" height="20" stroke="white" fill="none" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
                    </div>
                    <div>
                        <h3 style="font-size: 1.05rem; font-weight: 800; color: white; margin: 0;">Catat {{ $tipe == 'masuk' ? 'Surat Masuk' : 'Surat Keluar' }} Baru</h3>
                        <span style="font-size: 0.725rem; color: #94a3b8;">Isi data persuratan secara sistematis dan rapi</span>
                    </div>
                </div>
                <button type="button" onclick="closeModal('modalCreateSurat')" style="background: rgba(255,255,255,0.1); border: none; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.25)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">&times;</button>
            </div>

            <form action="{{ route('admin.sekretariat.surat.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="tipe" value="{{ $tipe }}">

                <div class="modal-body-prof">
                    <div class="form-grid-2">
                        <!-- Baris 1: Nomor & Tanggal -->
                        <div class="form-group">
                            <label style="font-size: 0.8rem; font-weight: 700; color: var(--navy);">Nomor Surat <span style="color: red;">*</span></label>
                            <input type="text" name="nomor_surat" class="form-control" placeholder="Contoh: 001/SK/PNKT/VII/2026" required style="font-size: 0.85rem; font-weight: 600;">
                        </div>

                        <div class="form-group">
                            <label style="font-size: 0.8rem; font-weight: 700; color: var(--navy);">Tanggal Surat <span style="color: red;">*</span></label>
                            <input type="date" name="tanggal" class="form-control datepicker" value="{{ date('Y-m-d') }}" required style="font-size: 0.85rem; font-weight: 600;">
                        </div>

                        <!-- Baris 2: Klasifikasi & Status -->
                        <div class="form-group">
                            <label style="font-size: 0.8rem; font-weight: 700; color: var(--navy);">Klasifikasi Surat <span style="color: red;">*</span></label>
                            <select name="klasifikasi" class="form-control select2-basic" style="width: 100%;" required>
                                <option value="internal" {{ $klasifikasi == 'internal' ? 'selected' : '' }}>Surat Internal</option>
                                <option value="eksternal" {{ $klasifikasi == 'eksternal' ? 'selected' : '' }}>Surat Eksternal</option>
                                <option value="penting" {{ $klasifikasi == 'penting' ? 'selected' : '' }}>Surat Penting</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label style="font-size: 0.8rem; font-weight: 700; color: var(--navy);">Status Awal</label>
                            <select name="status" class="form-control select2-basic" style="width: 100%;">
                                <option value="Draft">Draft</option>
                                <option value="Pending TTD">Pending TTD</option>
                                <option value="Terbit">Terbit</option>
                            </select>
                        </div>

                        <!-- Baris 3: Perihal -->
                        <div class="form-group form-group-full">
                            <label style="font-size: 0.8rem; font-weight: 700; color: var(--navy);">Perihal Surat <span style="color: red;">*</span></label>
                            <input type="text" name="perihal" class="form-control" placeholder="Tuliskan judul atau perihal utama surat..." required style="font-size: 0.85rem;">
                        </div>

                        <!-- Baris 4: Pengirim / Tujuan -->
                        <div class="form-group form-group-full">
                            <label style="font-size: 0.8rem; font-weight: 700; color: var(--navy);">{{ $tipe == 'masuk' ? 'Pengirim (Instansi/Nama)' : 'Tujuan (Instansi/Nama)' }} <span style="color: red;">*</span></label>
                            <input type="text" name="pengirim_tujuan" class="form-control" placeholder="{{ $tipe == 'masuk' ? 'Nama/Instansi Pengirim Surat' : 'Nama/Instansi Tujuan Surat' }}" required style="font-size: 0.85rem;">
                        </div>

                        <!-- Baris 5: File Lampiran & Google Drive Link -->
                        <div class="form-group">
                            <label style="font-size: 0.8rem; font-weight: 700; color: var(--navy);">Upload File Lampiran (PDF / Word)</label>
                            <div class="file-upload-zone" onclick="document.getElementById('createFileInput').click()">
                                <svg viewBox="0 0 24 24" width="24" height="24" stroke="#64748b" fill="none" stroke-width="2" style="margin-bottom: 4px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                                <div style="font-size: 0.775rem; font-weight: 600; color: #334155;" id="createFileNameLabel">Klik / Drag file .pdf, .doc, .docx</div>
                                <input type="file" id="createFileInput" name="file_lampiran" accept=".pdf,.doc,.docx" onchange="document.getElementById('createFileNameLabel').textContent = this.files[0]?.name || 'Klik / Drag file .pdf, .doc, .docx'">
                            </div>
                        </div>

                        <div class="form-group">
                            <label style="font-size: 0.8rem; font-weight: 700; color: var(--navy);">Link Google Drive (Opsional)</label>
                            <input type="url" name="link_drive" class="form-control" placeholder="https://drive.google.com/..." style="font-size: 0.85rem;">
                        </div>
                    </div>
                </div>

                <div class="modal-footer-prof">
                    <button type="button" onclick="closeModal('modalCreateSurat')" class="btn-outline-secondary">Batal</button>
                    <button type="submit" class="btn-solid-navy" style="font-weight: 700;">Simpan Surat</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================== -->
    <!-- Modal 2: Update Status Surat -->
    <!-- ============================== -->
    <div class="modal-overlay" id="modalStatusSurat" onclick="if(event.target===this) closeModal('modalStatusSurat')">
        <div class="modal-content-lg" style="max-width: 520px;">
            <div class="modal-header-prof">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(255,255,255,0.15); display: flex; align-items: center; justify-content: center;">
                        <svg viewBox="0 0 24 24" width="20" height="20" stroke="white" fill="none" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </div>
                    <div>
                        <h3 style="font-size: 1.05rem; font-weight: 800; color: white; margin: 0;">Ubah Status Surat</h3>
                        <span style="font-size: 0.725rem; color: #94a3b8;">Pilih status alur kerja surat yang baru</span>
                    </div>
                </div>
                <button type="button" onclick="closeModal('modalStatusSurat')" style="background: rgba(255,255,255,0.1); border: none; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer;">&times;</button>
            </div>

            <form id="formUpdateStatus" method="POST">
                @csrf
                @method('PATCH')

                <div class="modal-body-prof">
                    <div class="form-group" style="margin-bottom: 1.25rem;">
                        <label style="font-size: 0.8rem; font-weight: 700; color: var(--navy);">Status Baru <span style="color: red;">*</span></label>
                        <select name="status" id="statusSelect" class="form-control select2-basic" style="width: 100%;" required>
                            <option value="Draft">Draft</option>
                            <option value="Pending TTD">Pending TTD</option>
                            <option value="Terbit">Terbit</option>
                            <option value="Revisi">Revisi</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label style="font-size: 0.8rem; font-weight: 700; color: var(--navy);">Catatan / Alasan Perubahan (Disimpan ke Audit Trail)</label>
                        <textarea name="catatan" class="form-control" rows="3" placeholder="Contoh: Menunggu verifikasi tanda tangan pengurus..." style="font-size: 0.85rem;"></textarea>
                    </div>
                </div>

                <div class="modal-footer-prof">
                    <button type="button" onclick="closeModal('modalStatusSurat')" class="btn-outline-secondary">Batal</button>
                    <button type="submit" class="btn-solid-navy" style="font-weight: 700;">Perbarui Status</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================== -->
    <!-- Modal 3: Import Excel Surat -->
    <!-- ============================== -->
    <div class="modal-overlay" id="modalImportSurat" onclick="if(event.target===this) closeModal('modalImportSurat')">
        <div class="modal-content-lg" style="max-width: 920px; max-height: 88vh;">
            <div class="modal-header-prof">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 40px; height: 40px; border-radius: 8px; background: rgba(183, 131, 15, 0.2); display: flex; align-items: center; justify-content: center; border: 1px solid rgba(183, 131, 15, 0.4);">
                        <svg viewBox="0 0 24 24" width="22" height="22" stroke="#b7830f" fill="none" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    </div>
                    <div>
                        <h3 style="font-size: 1.1rem; font-weight: 800; color: white; margin: 0;">Import / Bulk Upload {{ $tipe == 'masuk' ? 'Surat Masuk' : 'Surat Keluar' }} (Excel)</h3>
                        <span style="font-size: 0.775rem; color: #94a3b8;">Unggah data persuratan massal menggunakan berkas Excel (.xls / .xlsx / .csv)</span>
                    </div>
                </div>
                <button type="button" onclick="closeModal('modalImportSurat')" style="background: rgba(255,255,255,0.1); border: none; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 1.2rem;">&times;</button>
            </div>

            <form action="{{ route('admin.sekretariat.surat.import') }}" method="POST" id="formImportSurat">
                @csrf
                <input type="hidden" name="tipe" value="{{ $tipe }}">
                <input type="hidden" name="surat_rows" id="importSuratRowsInput">

                <div class="modal-body-prof" style="padding: 1.5rem;">
                    <div style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; padding: 1.25rem; margin-bottom: 1.25rem;">
                        <div style="font-weight: 700; color: #022648; margin-bottom: 0.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                            <span style="font-size: 0.9rem;"><i class="fa fa-info-circle" style="color: #b7830f;"></i> Unduh Format Contoh Import Surat {{ ucfirst($tipe) }}</span>
                            <a href="{{ route('admin.sekretariat.surat.template-import', ['tipe' => $tipe]) }}" onclick="Toast.fire({ icon: 'success', title: 'Mengunduh format contoh Excel...' })" style="background: #059669; color: white; padding: 7px 14px; border-radius: 6px; font-size: 0.775rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: background 0.2s;" onmouseover="this.style.background='#047857'" onmouseout="this.style.background='#059669'">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                Unduh Format Contoh (.xls)
                            </a>
                        </div>
                        <p style="font-size: 0.8125rem; color: #64748b; margin: 0; line-height: 1.5;">
                            Silakan unduh berkas contoh untuk melihat susunan kolom: <strong>Nomor Surat, Perihal, {{ $tipe == 'masuk' ? 'Pengirim' : 'Tujuan' }}, Tanggal Surat, Klasifikasi, Status, Link Google Drive, Keterangan</strong>.
                        </p>
                    </div>

                    <div class="form-group-full">
                        <label class="form-label" style="font-weight: 700; color: #022648; font-size: 0.875rem; margin-bottom: 0.5rem; display: block;">Pilih Berkas Excel / CSV (.xls, .xlsx, .csv)</label>
                        <div class="file-upload-zone" id="importSuratDropZone" onclick="document.getElementById('importSuratFile').click()" style="border: 2px dashed #b7830f; background: #fffdf5; padding: 1.25rem 1rem; text-align: center; border-radius: 8px; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#fff9e6'" onmouseout="this.style.background='#fffdf5'">
                            <input type="file" id="importSuratFile" accept=".xls,.xlsx,.csv" onchange="handleImportSuratFile(this)" style="display: none;">
                            <div style="pointer-events: none;">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#b7830f" stroke-width="2" style="margin-bottom: 0.35rem;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                <div style="font-weight: 700; color: #022648; font-size: 0.9rem; margin-bottom: 2px;" id="importSuratFileLabel">Klik atau Tarik Berkas Excel ke Sini</div>
                                <span style="font-size: 0.775rem; color: #64748b;">Format yang didukung: .xls, .xlsx, .csv</span>
                            </div>
                        </div>
                    </div>

                    <div id="importSuratPreviewContainer" style="display: none; margin-top: 1.25rem;">
                        <div id="importSuratDuplicateAlert" style="display: none; background: #fffbeb; border: 1px solid #fde68a; color: #92400e; padding: 10px 14px; border-radius: 8px; margin-bottom: 0.75rem; font-size: 0.8125rem; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                            <div>
                                <i class="fa fa-exclamation-triangle" style="color: #d97706; margin-right: 6px;"></i>
                                Terdeteksi <strong id="importSuratDupCount" style="color: #dc2626; font-size: 0.9rem;">0</strong> data duplikat!
                            </div>
                            <button type="button" onclick="cleanImportSuratDuplicates()" style="background: #d97706; color: white; border: none; padding: 5px 12px; border-radius: 6px; font-size: 0.775rem; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: background 0.2s;" onmouseover="this.style.background='#b45309'" onmouseout="this.style.background='#d97706'">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                Bersihkan Data Duplikat
                            </button>
                        </div>

                        <div style="font-weight: 700; color: #022648; font-size: 0.85rem; margin-bottom: 0.5rem; display: flex; justify-content: space-between; align-items: center;">
                            <span>Pratinjau Data Terbaca (<span id="importSuratCount">0</span>)</span>
                        </div>
                        <div style="max-height: 220px; overflow-y: auto; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.775rem;">
                            <table style="width: 100%; border-collapse: collapse;">
                                <thead>
                                    <tr style="background: #022648; color: white;">
                                        <th style="padding: 10px 12px; text-align: left; width: 30%;">No Surat</th>
                                        <th style="padding: 10px 12px; text-align: left; width: 35%;">Perihal</th>
                                        <th style="padding: 10px 12px; text-align: left; width: 20%;">{{ $tipe == 'masuk' ? 'Pengirim' : 'Tujuan' }}</th>
                                        <th style="padding: 10px 12px; text-align: center; width: 10%;">Tanggal</th>
                                        <th style="padding: 10px 12px; text-align: center; width: 5%;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="importSuratPreviewTableBody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="modal-footer-prof" style="padding: 1rem 1.5rem; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <button type="button" class="btn-outline-secondary" onclick="closeModal('modalImportSurat')">Batal</button>
                    <button type="submit" id="btnSubmitImportSurat" class="btn-solid-navy" style="background: #022648 !important; color: white !important; font-weight: 700;" disabled>
                        <i class="fa fa-upload"></i> Proses Import Massal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================== -->
    <!-- Modal 4: Bulk Upload Multi-PDF -->
    <!-- ============================== -->
    <div class="modal-overlay" id="modalBulkPdfSurat" onclick="if(event.target===this) closeModal('modalBulkPdfSurat')">
        <div class="modal-content-lg" style="max-width: 920px; max-height: 88vh;">
            <div class="modal-header-prof" style="background: linear-gradient(135deg, #b7830f 0%, #7c5706 100%);">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 40px; height: 40px; border-radius: 8px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center;">
                        <svg viewBox="0 0 24 24" width="22" height="22" stroke="white" fill="none" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
                    </div>
                    <div>
                        <h3 style="font-size: 1.1rem; font-weight: 800; color: white; margin: 0;">Bulk Upload Multi-PDF Berkas {{ $tipe == 'masuk' ? 'Surat Masuk' : 'Surat Keluar' }}</h3>
                        <span style="font-size: 0.775rem; color: #fef08a;">Unggah banyak berkas PDF sekaligus dan lengkapi data perihal surat</span>
                    </div>
                </div>
                <button type="button" onclick="closeModal('modalBulkPdfSurat')" style="background: rgba(255,255,255,0.1); border: none; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 1.2rem;">&times;</button>
            </div>

            <form action="{{ route('admin.sekretariat.surat.bulk-store') }}" method="POST" enctype="multipart/form-data" id="formBulkPdfSurat">
                @csrf
                <input type="hidden" name="tipe" value="{{ $tipe }}">

                <div class="modal-body-prof" style="padding: 1.5rem;">
                    <div class="form-group-full" style="margin-bottom: 1.25rem;">
                        <label class="form-label" style="font-weight: 700; color: #022648; font-size: 0.875rem; margin-bottom: 0.5rem; display: block;">Pilih / Drag Banyak Berkas PDF Sekaligus (.pdf, .doc, .docx)</label>
                        <div class="file-upload-zone" id="bulkPdfDropZone" onclick="document.getElementById('bulkPdfInputFiles').click()" style="border: 2px dashed #b7830f; background: #fffdf5; padding: 1.5rem 1rem; text-align: center; border-radius: 8px; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#fff9e6'" onmouseout="this.style.background='#fffdf5'">
                            <input type="file" id="bulkPdfInputFiles" multiple accept=".pdf,.doc,.docx" style="display: none;">
                            <div style="pointer-events: none;">
                                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#b7830f" stroke-width="2" style="margin-bottom: 0.35rem;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                <div style="font-weight: 700; color: #022648; font-size: 0.95rem; margin-bottom: 2px;" id="bulkPdfFileLabel">Pilih Banyak File PDF Sekaligus (Bisa Multiple Select / Drag & Drop)</div>
                                <span style="font-size: 0.775rem; color: #64748b;">Mendukung berkas format .pdf, .doc, .docx (Maksimal 10MB/berkas)</span>
                            </div>
                        </div>
                    </div>

                    <div id="bulkPdfCardsContainer" style="display: flex; flex-direction: column; gap: 1rem;">
                        <!-- Dynamic file cards rendered here by JS -->
                    </div>
                </div>

                <div class="modal-footer-prof" style="padding: 1rem 1.5rem; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <button type="button" class="btn-outline-secondary" onclick="closeModal('modalBulkPdfSurat')">Batal</button>
                    <button type="submit" id="btnSubmitBulkPdf" class="btn-solid-navy" style="background: #b7830f !important; color: white !important; font-weight: 700;" disabled>
                        <i class="fa fa-save"></i> Simpan Semua Surat (<span id="bulkPdfFileCount">0</span> Berkas)
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
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
                    dropdown.classList.add('is-open');
                    const dropdownHeight = dropdown.offsetHeight || 220;
                    const spaceBelow = window.innerHeight - rect.bottom;

                    if (spaceBelow < dropdownHeight && rect.top > dropdownHeight) {
                        dropdown.style.top = (rect.top - dropdownHeight - 4) + 'px';
                    } else {
                        dropdown.style.top = (rect.bottom + 4) + 'px';
                    }
                    dropdown.style.left = (rect.right - 175) + 'px';
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

    window.openModalById = function(modalId) {
        const m = document.getElementById(modalId);
        if (m) {
            m.classList.add('active');
            m.style.setProperty('display', 'flex', 'important');
            m.style.setProperty('visibility', 'visible', 'important');
            m.style.setProperty('opacity', '1', 'important');
        }
    };

    window.closeModalById = function(modalId) {
        const m = document.getElementById(modalId);
        if (m) {
            m.classList.remove('active');
            m.style.setProperty('display', 'none', 'important');
            m.style.setProperty('visibility', 'hidden', 'important');
            m.style.setProperty('opacity', '0', 'important');
        }
    };

    function openCreateModal() {
        openModalById('modalCreateSurat');
        if (typeof $.fn.select2 !== 'undefined') {
            $('#modalCreateSurat .select2-basic').select2({ width: '100%', dropdownParent: $('#modalCreateSurat') });
        }
        if (typeof flatpickr !== 'undefined') {
            flatpickr('#modalCreateSurat .datepicker', { dateFormat: 'Y-m-d', allowInput: true });
        }
    }

    function openStatusModal(id, currentStatus) {
        const form = document.getElementById('formUpdateStatus');
        form.action = `/admin/sekretariat/surat/${id}/status`;
        openModalById('modalStatusSurat');
        if (typeof $.fn.select2 !== 'undefined') {
            $('#modalStatusSurat .select2-basic').select2({ width: '100%', dropdownParent: $('#modalStatusSurat') });
        }
        $('#statusSelect').val(currentStatus).trigger('change');
    }

    function closeModal(modalId) {
        closeModalById(modalId);
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

    function previewSuratLampiran(url, title) {
        if (!url) return;
        const isImage = /\.(jpg|jpeg|png|gif|webp)(\?|$)/i.test(url);
        const isPdf = /\.pdf(\?|$)/i.test(url);
        const isWord = /\.(doc|docx)(\?|$)/i.test(url);

        let content = '';
        if (isImage) {
            content = `<img src="${url}" style="max-width:100%;max-height:70vh;object-fit:contain;border-radius:8px;">`;
        } else if (isPdf) {
            content = `<iframe src="${url}" style="width:100%;height:72vh;border:none;border-radius:8px;"></iframe>`;
        } else if (isWord) {
            // Office Online viewer for Word documents
            const encoded = encodeURIComponent(url.startsWith('http') ? url : window.location.origin + url);
            content = `<iframe src="https://view.officeapps.live.com/op/embed.aspx?src=${encoded}" style="width:100%;height:72vh;border:none;border-radius:8px;"></iframe>`;
        } else {
            content = `<div style="display:flex;flex-direction:column;align-items:center;justify-content:center;padding:2.5rem 1rem;gap:1rem;background:#f8fafc;border-radius:10px;border:2px dashed #cbd5e1;">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#022648" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                <div style="font-weight:700;color:#022648;">Pratinjau tidak tersedia untuk format ini</div>
                <a href="${url}" target="_blank" style="display:inline-flex;align-items:center;gap:6px;background:#022648;color:white;padding:8px 18px;border-radius:6px;font-weight:700;text-decoration:none;font-size:0.875rem;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Unduh File
                </a>
            </div>`;
        }

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: `<div style="color:#022648;font-weight:800;font-size:1.05rem;text-align:left;padding-bottom:4px;border-bottom:2px solid #022648;">${title || 'Pratinjau Lampiran'}</div>`,
                html: `
                    <div style="margin-bottom:0.6rem;display:flex;justify-content:flex-end;">
                        <a href="${url}" target="_blank" style="font-size:0.75rem;padding:4px 12px;background:#022648;color:white;border-radius:4px;text-decoration:none;font-weight:700;display:inline-flex;align-items:center;gap:5px;">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            Unduh / Buka Tab Baru
                        </a>
                    </div>
                    ${content}`,
                width: '920px',
                showCloseButton: true,
                confirmButtonText: 'Tutup Pratinjau',
                confirmButtonColor: '#022648',
                customClass: { container: 'swal-high-zindex' }
            });
        }
    }

    function previewSuratDrive(url, title) {
        if (!url) return;
        const isDriveFolder = url.includes('/folders/') || (url.includes('drive.google.com') && !url.includes('/file/d/'));
        if (isDriveFolder) {
            window.open(url, '_blank');
            return;
        }
        const matches = url.match(/\/file\/d\/([^\/]+)/);
        const embedUrl = matches ? `https://drive.google.com/file/d/${matches[1]}/preview` : url;

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: `<div style="color:#022648;font-weight:800;font-size:1.05rem;text-align:left;padding-bottom:4px;border-bottom:2px solid #022648;">${title || 'Pratinjau Drive'}</div>`,
                html: `
                    <div style="margin-bottom:0.6rem;display:flex;justify-content:flex-end;">
                        <a href="${url}" target="_blank" style="font-size:0.75rem;padding:4px 12px;background:#022648;color:white;border-radius:4px;text-decoration:none;font-weight:700;display:inline-flex;align-items:center;gap:5px;">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                            Buka di Drive
                        </a>
                    </div>
                    <iframe src="${embedUrl}" style="width:100%;height:72vh;border:none;border-radius:8px;" allow="autoplay"></iframe>`,
                width: '920px',
                showCloseButton: true,
                confirmButtonText: 'Tutup Pratinjau',
                confirmButtonColor: '#022648',
                customClass: { container: 'swal-high-zindex' }
            });
        }
    }

    // Bulk Action Script for Surat
    document.addEventListener('DOMContentLoaded', function () {
        const checkAllSurat = document.getElementById('check-all-surat');
        const checkSuratItems = document.querySelectorAll('.check-surat-item');
        const bulkBarSurat = document.getElementById('bulk-action-bar-surat');
        const countSuratDisplay = document.getElementById('selected-surat-count');

        function updateSuratBulkBar() {
            const checked = document.querySelectorAll('.check-surat-item:checked');
            if (countSuratDisplay) countSuratDisplay.innerText = checked.length;
            if (bulkBarSurat) {
                bulkBarSurat.style.display = checked.length > 0 ? 'flex' : 'none';
            }
        }

        if (checkAllSurat) {
            checkAllSurat.addEventListener('change', function () {
                checkSuratItems.forEach(item => item.checked = this.checked);
                updateSuratBulkBar();
            });
        }

        checkSuratItems.forEach(item => {
            item.addEventListener('change', function () {
                if (checkAllSurat) {
                    checkAllSurat.checked = Array.from(checkSuratItems).every(i => i.checked);
                }
                updateSuratBulkBar();
            });
        });
    });

    function executeBulkDeleteSurat() {
        const checked = document.querySelectorAll('.check-surat-item:checked');
        if (checked.length === 0) return;

        Swal.fire({
            title: 'Konfirmasi Hapus Massal',
            text: `Apakah Anda yakin ingin menghapus ${checked.length} surat yang dipilih? Berkas lampiran juga akan terhapus secara permanen.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus Semua!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('bulk-delete-surat-form');
                form.querySelectorAll('input[name="ids[]"]').forEach(el => el.remove());
                checked.forEach(item => {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'ids[]';
                    hiddenInput.value = item.value;
                    form.appendChild(hiddenInput);
                });
                form.submit();
            }
        });
    }

    function executeBulkDownloadSurat() {
        const checked = document.querySelectorAll('.check-surat-item:checked');
        if (checked.length === 0) return;

        if (typeof Toast !== 'undefined') {
            Toast.fire({ icon: 'info', title: 'Memproses kompresi berkas ZIP...' });
        }

        const form = document.getElementById('bulk-download-surat-form');
        form.querySelectorAll('input[name="ids[]"]').forEach(el => el.remove());
        checked.forEach(item => {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'ids[]';
            hiddenInput.value = item.value;
            form.appendChild(hiddenInput);
        });
        form.submit();
    }

    // =====================================
    // Excel Import Drag & Drop & JS Logic
    // =====================================
    window.existingNomorSurats = @json($existingNomorSurats ?? []);
    window.currentParsedSuratRows = [];

    window.openImportSuratModal = function() {
        openModalById('modalImportSurat');
    };

    window.openBulkPdfModal = function() {
        openModalById('modalBulkPdfSurat');
    };

    function handleImportSuratFile(input) {
        const file = (input && input.files && input.files[0]) ? input.files[0] : input;
        if (!file || !file.name) return;

        const label = document.getElementById('importSuratFileLabel');
        if (label) label.innerText = file.name;

        function processRows(rows) {
            const validRows = rows.filter(r => r.nomor_surat && r.perihal && r.nomor_surat !== '' && r.perihal !== '');
            window.currentParsedSuratRows = validRows;
            renderSuratImportPreview();
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            let rows = [];
            try {
                if (typeof XLSX !== 'undefined') {
                    const data = new Uint8Array(e.target.result);
                    const workbook = XLSX.read(data, { type: 'array' });
                    const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                    const jsonSheet = XLSX.utils.sheet_to_json(firstSheet, { header: 1, raw: false });

                    jsonSheet.forEach((rowArray) => {
                        if (!rowArray || !Array.isArray(rowArray) || rowArray.length < 2) return;
                        
                        const rowStr = rowArray.map(c => String(c || '').trim()).join(' ').toLowerCase();
                        if (rowStr.includes('template import') || rowStr.includes('petunjuk:') || rowStr.includes('nomor surat')) return;

                        let nomor = '';
                        let perihal = '';
                        let pengirimTujuan = '';
                        let tgl = '2026-08-01';
                        let klasifikasi = 'internal';
                        let status = 'Terbit';
                        let link = '';

                        const firstCell = String(rowArray[0] || '').trim();
                        if (/^\d+$/.test(firstCell) || (rowArray.length >= 3 && String(rowArray[1] || '').trim() !== '')) {
                            nomor = String(rowArray[1] || '').trim();
                            perihal = String(rowArray[2] || '').trim();
                            pengirimTujuan = String(rowArray[3] || '').trim();
                            tgl = String(rowArray[4] || tgl).trim();
                            klasifikasi = String(rowArray[5] || klasifikasi).trim();
                            status = String(rowArray[6] || status).trim();
                            link = String(rowArray[7] || '').trim();
                        } else {
                            nomor = String(rowArray[0] || '').trim();
                            perihal = String(rowArray[1] || '').trim();
                            pengirimTujuan = String(rowArray[2] || '').trim();
                            tgl = String(rowArray[3] || tgl).trim();
                            klasifikasi = String(rowArray[4] || klasifikasi).trim();
                            status = String(rowArray[5] || status).trim();
                            link = String(rowArray[6] || '').trim();
                        }

                        function normDate(s, def) {
                            if (!s) return def;
                            const m = String(s).trim().match(/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{2,4})$/);
                            if (m) {
                                let p1 = parseInt(m[1]), p2 = parseInt(m[2]), y = parseInt(m[3]);
                                if (y < 100) y += 2000;
                                if (p1 > 12) return `${y}-${String(p2).padStart(2,'0')}-${String(p1).padStart(2,'0')}`;
                                return `${y}-${String(p1).padStart(2,'0')}-${String(p2).padStart(2,'0')}`;
                            }
                            return s;
                        }

                        if (nomor && perihal && nomor.toLowerCase() !== 'nomor surat' && nomor.toLowerCase() !== 'no') {
                            rows.push({
                                nomor_surat: nomor,
                                perihal: perihal,
                                pengirim_tujuan: pengirimTujuan || 'Sekretariat',
                                tanggal: normDate(tgl, '2026-08-01'),
                                klasifikasi: klasifikasi,
                                status: status,
                                link_drive: link
                            });
                        }
                    });
                }
            } catch (err) {
                console.warn('SheetJS read failed:', err);
            }

            if (rows.length > 0) {
                processRows(rows);
            }
        };
        reader.readAsArrayBuffer(file);
    }

    function renderSuratImportPreview() {
        const rows = window.currentParsedSuratRows || [];
        if (rows.length === 0) {
            document.getElementById('importSuratPreviewContainer').style.display = 'none';
            document.getElementById('btnSubmitImportSurat').disabled = true;
            return;
        }

        const nomorCounts = {};
        rows.forEach(r => {
            const key = (r.nomor_surat || '').toLowerCase();
            nomorCounts[key] = (nomorCounts[key] || 0) + 1;
        });

        let duplicateCount = 0;
        rows.forEach(r => {
            const key = (r.nomor_surat || '').toLowerCase();
            const isInternalDup = nomorCounts[key] > 1;
            const isDbDup = window.existingNomorSurats.some(n => n.toLowerCase() === key);
            r.is_duplicate = isInternalDup || isDbDup;
            r.dup_reason = isDbDup ? 'Sudah Ada di Database' : (isInternalDup ? 'Duplikat di Berkas' : '');
            if (r.is_duplicate) duplicateCount++;
        });

        const alertBox = document.getElementById('importSuratDuplicateAlert');
        const dupCountSpan = document.getElementById('importSuratDupCount');
        if (alertBox && dupCountSpan) {
            if (duplicateCount > 0) {
                dupCountSpan.innerText = duplicateCount;
                alertBox.style.display = 'flex';
            } else {
                alertBox.style.display = 'none';
            }
        }

        document.getElementById('importSuratRowsInput').value = JSON.stringify(rows);
        document.getElementById('importSuratCount').innerText = rows.length + (rows.length > 15 ? ' data — menampilkan 15 pertama' : ' data');

        const tbody = document.getElementById('importSuratPreviewTableBody');
        tbody.innerHTML = '';

        rows.slice(0, 15).forEach((r, idx) => {
            const bgStyle = r.is_duplicate ? 'background: #fef2f2;' : '';
            const dupBadge = r.is_duplicate ? `<span style="display: inline-block; background: #fee2e2; color: #991b1b; padding: 3px 8px; border-radius: 4px; font-size: 0.7rem; font-weight: 700; margin-top: 4px;">${r.dup_reason}</span>` : '';

            tbody.innerHTML += `<tr style="${bgStyle}">
                <td style="padding: 10px 12px; border-bottom: 1px solid #e2e8f0; vertical-align: top;">
                    <div style="font-weight: 700; color: #022648; font-size: 0.8125rem;">${r.nomor_surat}</div>
                    ${dupBadge}
                </td>
                <td style="padding: 10px 12px; border-bottom: 1px solid #e2e8f0; vertical-align: top; font-size: 0.8125rem; color: #334155; line-height: 1.4;">${r.perihal}</td>
                <td style="padding: 10px 12px; border-bottom: 1px solid #e2e8f0; vertical-align: top; font-size: 0.8125rem; color: #475569;">${r.pengirim_tujuan}</td>
                <td style="padding: 10px 12px; border-bottom: 1px solid #e2e8f0; text-align: center; vertical-align: top; font-size: 0.8125rem; color: #475569;">${r.tanggal}</td>
                <td style="padding: 10px 12px; border-bottom: 1px solid #e2e8f0; text-align: center; vertical-align: top;">
                    <button type="button" onclick="removeImportSuratRow(${idx})" title="Hapus baris ini" style="background: #fee2e2; color: #991b1b; border: none; width: 26px; height: 26px; border-radius: 50%; font-weight: bold; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; font-size: 0.875rem; transition: background 0.2s;" onmouseover="this.style.background='#fca5a5'" onmouseout="this.style.background='#fee2e2'">&times;</button>
                </td>
            </tr>`;
        });

        document.getElementById('importSuratPreviewContainer').style.display = 'block';
        document.getElementById('btnSubmitImportSurat').disabled = false;
    }

    window.cleanImportSuratDuplicates = function() {
        if (!window.currentParsedSuratRows) return;
        const seen = new Set();
        window.currentParsedSuratRows = window.currentParsedSuratRows.filter(r => {
            const key = (r.nomor_surat || '').toLowerCase();
            const isDbDup = window.existingNomorSurats.some(n => n.toLowerCase() === key);
            if (isDbDup || seen.has(key)) {
                return false;
            }
            seen.add(key);
            return true;
        });

        if (typeof Toast !== 'undefined') {
            Toast.fire({ icon: 'success', title: 'Data duplikat berhasil dibersihkan!' });
        }
        renderSuratImportPreview();
    };

    window.removeImportSuratRow = function(index) {
        if (window.currentParsedSuratRows && window.currentParsedSuratRows[index] !== undefined) {
            window.currentParsedSuratRows.splice(index, 1);
            renderSuratImportPreview();
        }
    };

    // =====================================
    // Bulk Multi-PDF Drag & Drop JS Engine
    // =====================================
    window.bulkPdfSelectedFiles = [];
    window.bulkPdfCardStates = {};

    function saveBulkPdfCurrentInputs() {
        if (!window.bulkPdfSelectedFiles) return;
        window.bulkPdfSelectedFiles.forEach((file, idx) => {
            const key = file.name + '_' + file.size;
            window.bulkPdfCardStates[key] = {
                nomor: document.getElementById(`pdf_nomor_${idx}`)?.value,
                tanggal: document.getElementById(`pdf_tanggal_${idx}`)?.value,
                perihal: document.getElementById(`pdf_perihal_${idx}`)?.value,
                pt: document.getElementById(`pdf_pt_${idx}`)?.value,
                klasifikasi: document.getElementById(`pdf_klasifikasi_${idx}`)?.value,
                status: document.getElementById(`pdf_status_${idx}`)?.value
            };
        });
    }

    function addBulkPdfFiles(newFiles) {
        saveBulkPdfCurrentInputs();
        const validFiles = Array.from(newFiles).filter(f => f.name.match(/\.(pdf|doc|docx)$/i));
        if (validFiles.length === 0) return;

        validFiles.forEach(nf => {
            if (!window.bulkPdfSelectedFiles.some(existing => existing.name === nf.name && existing.size === nf.size)) {
                window.bulkPdfSelectedFiles.push(nf);
            }
        });

        renderBulkPdfCards();
    }

    function renderBulkPdfCards() {
        const container = document.getElementById('bulkPdfCardsContainer');
        const submitBtn = document.getElementById('btnSubmitBulkPdf');
        const countSpan = document.getElementById('bulkPdfFileCount');
        const labelText = document.getElementById('bulkPdfFileLabel');

        if (!container) return;

        if (!window.bulkPdfSelectedFiles || window.bulkPdfSelectedFiles.length === 0) {
            container.innerHTML = '';
            if (submitBtn) submitBtn.disabled = true;
            if (countSpan) countSpan.innerText = '0';
            if (labelText) labelText.innerText = 'Pilih / Drag Banyak Berkas PDF Sekaligus';
            return;
        }

        if (submitBtn) submitBtn.disabled = false;
        if (countSpan) countSpan.innerText = window.bulkPdfSelectedFiles.length;
        if (labelText) labelText.innerText = `${window.bulkPdfSelectedFiles.length} berkas PDF terpilih (Klik / Drag untuk menambah lagi)`;

        let html = '';
        const todayStr = new Date().toISOString().split('T')[0];

        window.bulkPdfSelectedFiles.forEach((file, idx) => {
            const key = file.name + '_' + file.size;
            const state = window.bulkPdfCardStates[key] || {};

            const cleanName = state.perihal || file.name.replace(/\.[^/.]+$/, "");
            const fileSizeMb = (file.size / (1024 * 1024)).toFixed(2);
            const autoNo = state.nomor || `SRT-${String(idx + 1).padStart(3, '0')}/VIII/2026`;
            const dateVal = state.tanggal || todayStr;
            const ptVal = state.pt || 'Sekretariat';
            const klasVal = state.klasifikasi || 'internal';
            const statusVal = state.status || 'Terbit';

            html += `
                <div class="bulk-pdf-card" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; padding: 1.25rem; box-shadow: 0 2px 4px rgba(0,0,0,0.04);">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.75rem; flex-wrap: wrap; gap: 8px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="background: #b7830f; color: white; width: 24px; height: 24px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem;">${idx + 1}</span>
                            <strong style="color: #022648; font-size: 0.9rem;">${file.name}</strong>
                            <span style="color: #64748b; font-size: 0.75rem;">(${fileSizeMb} MB)</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <button type="button" onclick="toggleBulkPdfPreview(${idx})" style="background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; padding: 4px 10px; border-radius: 4px; font-weight: 700; font-size: 0.75rem; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                <span id="pdf_preview_btn_text_${idx}">Pratinjau Slide PDF</span>
                            </button>
                            <button type="button" onclick="removeBulkPdfCard(${idx})" style="background: #fee2e2; color: #991b1b; border: none; padding: 4px 10px; border-radius: 4px; font-weight: 700; font-size: 0.75rem; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                                &times; Hapus Berkas
                            </button>
                        </div>
                    </div>

                    <div id="pdf_preview_slide_${idx}" style="display: none; margin-bottom: 1.25rem; border: 1px solid #cbd5e1; border-radius: 8px; overflow: hidden; background: #0f172a; box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);">
                        <div style="background: #022648; color: white; padding: 6px 12px; font-size: 0.75rem; font-weight: 700; display: flex; justify-content: space-between; align-items: center;">
                            <span>📄 Slide Pratinjau Dokumen: ${file.name}</span>
                            <span style="color: #fef08a;">Scroll / slide untuk membaca seluruh isi dokumen PDF</span>
                        </div>
                        <iframe id="pdf_iframe_${idx}" style="width: 100%; height: 380px; border: none;" src="about:blank"></iframe>
                    </div>

                    <div class="form-grid-2">
                        <div class="form-group">
                            <label style="font-size: 0.775rem; font-weight: 700; color: #022648;">Nomor Surat <span style="color: red;">*</span></label>
                            <input type="text" id="pdf_nomor_${idx}" class="form-control" placeholder="Nomor Surat..." value="${autoNo}" required style="font-size: 0.8125rem;">
                        </div>

                        <div class="form-group">
                            <label style="font-size: 0.775rem; font-weight: 700; color: #022648;">Tanggal Surat <span style="color: red;">*</span></label>
                            <input type="date" id="pdf_tanggal_${idx}" class="form-control" value="${dateVal}" required style="font-size: 0.8125rem;">
                        </div>

                        <div class="form-group form-group-full">
                            <label style="font-size: 0.775rem; font-weight: 700; color: #022648;">Perihal Surat <span style="color: red;">*</span></label>
                            <input type="text" id="pdf_perihal_${idx}" class="form-control" placeholder="Perihal atau Judul Surat..." value="${cleanName}" required style="font-size: 0.8125rem;">
                        </div>

                        <div class="form-group form-group-full">
                            <label style="font-size: 0.775rem; font-weight: 700; color: #022648;">{{ $tipe == 'masuk' ? 'Pengirim (Instansi/Nama)' : 'Tujuan (Instansi/Nama)' }} <span style="color: red;">*</span></label>
                            <input type="text" id="pdf_pt_${idx}" class="form-control" placeholder="Pengirim / Tujuan..." value="${ptVal}" required style="font-size: 0.8125rem;">
                        </div>

                        <div class="form-group">
                            <label style="font-size: 0.775rem; font-weight: 700; color: #022648;">Klasifikasi</label>
                            <select id="pdf_klasifikasi_${idx}" class="form-control" style="font-size: 0.8125rem;">
                                <option value="internal" ${klasVal === 'internal' ? 'selected' : ''}>Surat Internal</option>
                                <option value="eksternal" ${klasVal === 'eksternal' ? 'selected' : ''}>Surat Eksternal</option>
                                <option value="penting" ${klasVal === 'penting' ? 'selected' : ''}>Surat Penting</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label style="font-size: 0.775rem; font-weight: 700; color: #022648;">Status Awal</label>
                            <select id="pdf_status_${idx}" class="form-control" style="font-size: 0.8125rem;">
                                <option value="Pending TTD" ${statusVal === 'Pending TTD' ? 'selected' : ''}>Pending TTD</option>
                                <option value="Terbit" ${statusVal === 'Terbit' ? 'selected' : ''}>Terbit</option>
                                <option value="Draft" ${statusVal === 'Draft' ? 'selected' : ''}>Draft</option>
                            </select>
                        </div>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;
    }

    window.toggleBulkPdfPreview = function(idx) {
        const slide = document.getElementById(`pdf_preview_slide_${idx}`);
        const iframe = document.getElementById(`pdf_iframe_${idx}`);
        const btnText = document.getElementById(`pdf_preview_btn_text_${idx}`);

        if (!slide || !iframe) return;

        if (slide.style.display === 'none') {
            const file = window.bulkPdfSelectedFiles[idx];
            if (file) {
                const blobUrl = URL.createObjectURL(file);
                iframe.src = blobUrl;
            }
            slide.style.display = 'block';
            if (btnText) btnText.innerText = 'Sembunyikan PDF';
        } else {
            slide.style.display = 'none';
            iframe.src = 'about:blank';
            if (btnText) btnText.innerText = 'Pratinjau Slide PDF';
        }
    };

    window.removeBulkPdfCard = function(idx) {
        saveBulkPdfCurrentInputs();
        if (window.bulkPdfSelectedFiles && window.bulkPdfSelectedFiles[idx]) {
            window.bulkPdfSelectedFiles.splice(idx, 1);
            renderBulkPdfCards();
        }
    };

    // Attach Drag & Drop and Form Handlers
    document.addEventListener('DOMContentLoaded', function () {
        const dropZone = document.getElementById('bulkPdfDropZone');
        const inputFiles = document.getElementById('bulkPdfInputFiles');
        const formBulk = document.getElementById('formBulkPdfSurat');

        if (dropZone) {
            ['dragenter', 'dragover'].forEach(evtName => {
                dropZone.addEventListener(evtName, (e) => {
                    e.preventDefault(); e.stopPropagation();
                    dropZone.style.background = '#fff9e6';
                    dropZone.style.borderColor = '#022648';
                }, false);
            });

            ['dragleave', 'drop'].forEach(evtName => {
                dropZone.addEventListener(evtName, (e) => {
                    e.preventDefault(); e.stopPropagation();
                    dropZone.style.background = '#fffdf5';
                    dropZone.style.borderColor = '#b7830f';
                }, false);
            });

            dropZone.addEventListener('drop', (e) => {
                const dt = e.dataTransfer;
                if (dt && dt.files && dt.files.length > 0) {
                    addBulkPdfFiles(dt.files);
                }
            }, false);
        }

        if (inputFiles) {
            inputFiles.addEventListener('change', function () {
                if (this.files && this.files.length > 0) {
                    addBulkPdfFiles(this.files);
                    this.value = '';
                }
            });
        }

        // Excel Dropzone Drag & Drop
        const importDropZone = document.getElementById('importSuratDropZone');

        if (importDropZone) {
            ['dragenter', 'dragover'].forEach(evtName => {
                importDropZone.addEventListener(evtName, (e) => {
                    e.preventDefault(); e.stopPropagation();
                    importDropZone.style.background = '#fff9e6';
                }, false);
            });

            ['dragleave', 'drop'].forEach(evtName => {
                importDropZone.addEventListener(evtName, (e) => {
                    e.preventDefault(); e.stopPropagation();
                    importDropZone.style.background = '#fffdf5';
                }, false);
            });

            importDropZone.addEventListener('drop', (e) => {
                const dt = e.dataTransfer;
                if (dt && dt.files && dt.files.length > 0) {
                    handleImportSuratFile(dt.files[0]);
                }
            }, false);
        }

        // Form Submit Handler via AJAX FormData
        if (formBulk) {
            formBulk.addEventListener('submit', function (e) {
                e.preventDefault();

                if (!window.bulkPdfSelectedFiles || window.bulkPdfSelectedFiles.length === 0) {
                    if (typeof Toast !== 'undefined') Toast.fire({ icon: 'warning', title: 'Belum ada berkas PDF yang dipilih!' });
                    return;
                }

                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('tipe', '{{ $tipe }}');

                let hasError = false;

                window.bulkPdfSelectedFiles.forEach((file, idx) => {
                    const nomor = document.getElementById(`pdf_nomor_${idx}`)?.value || '';
                    const tanggal = document.getElementById(`pdf_tanggal_${idx}`)?.value || '';
                    const perihal = document.getElementById(`pdf_perihal_${idx}`)?.value || '';
                    const pt = document.getElementById(`pdf_pt_${idx}`)?.value || '';
                    const klasifikasi = document.getElementById(`pdf_klasifikasi_${idx}`)?.value || 'internal';
                    const status = document.getElementById(`pdf_status_${idx}`)?.value || 'Terbit';

                    if (!nomor || !perihal || !pt) {
                        hasError = true;
                    }

                    formData.append(`files[${idx}]`, file);
                    formData.append(`surats[${idx}][nomor_surat]`, nomor);
                    formData.append(`surats[${idx}][tanggal]`, tanggal);
                    formData.append(`surats[${idx}][perihal]`, perihal);
                    formData.append(`surats[${idx}][pengirim_tujuan]`, pt);
                    formData.append(`surats[${idx}][klasifikasi]`, klasifikasi);
                    formData.append(`surats[${idx}][status]`, status);
                });

                if (hasError) {
                    Swal.fire({ icon: 'warning', title: 'Data Belum Lengkap', text: 'Mohon isi Nomor Surat, Perihal, dan Pengirim/Tujuan pada seluruh kartu surat.' });
                    return;
                }

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Mengunggah Surat...',
                        text: `Sedang memproses ${window.bulkPdfSelectedFiles.length} berkas PDF...`,
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading(); }
                    });
                }

                fetch(formBulk.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Bulk Upload Berhasil!',
                                text: data.message || 'Semua surat berhasil disimpan.',
                                confirmButtonColor: '#022648'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            window.location.reload();
                        }
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal Unggah', text: data.message || 'Terjadi kesalahan saat memproses data.' });
                    }
                })
                .catch(err => {
                    console.error('Bulk store error:', err);
                    formBulk.submit();
                });
            });
        }
    });
</script>
@endpush