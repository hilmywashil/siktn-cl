@extends('admin.layouts.admin-layout')

@section('title', 'Notulensi Rapat - SIKTN Admin')
@section('page-title', 'Sekretariat - Notulensi Rapat')

@push('styles')
<style>
    @keyframes select2DropdownFadeIn {
        from { opacity: 0; transform: translateY(-8px) scale(0.97); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    .select2-container--default .select2-selection--single {
        height: 40px; padding: 0.35rem 0.75rem; font-size: 0.8125rem; font-weight: 600;
        color: var(--navy); background-color: #fff; border: 1px solid var(--gray-300);
        border-radius: var(--radius-md); display: flex; align-items: center;
        transition: all 0.2s cubic-bezier(0.4,0,0.2,1);
    }
    .select2-dropdown {
        border: 1px solid var(--gray-300); border-radius: var(--radius-md); font-size: 0.8125rem;
        z-index: 9999; box-shadow: 0 12px 28px rgba(2,38,72,0.15); margin-top: 4px; overflow: hidden;
    }
    .select2-container--open .select2-dropdown {
        animation: select2DropdownFadeIn 0.2s cubic-bezier(0.16,1,0.3,1) forwards;
    }
    .select2-results__option--highlighted[aria-selected],
    .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
        background-color: #022648 !important; color: #ffffff !important; font-weight: 600 !important;
    }

    .admin-ui-scope {
        --navy: #022648; --navy-dark: #01162f; --navy-light: #0a3a6b;
        --gold: #b7830f; --green: #059669; --blue: #2563eb; --red: #dc2626; --amber: #d97706;
        --gray-50: #f9fafb; --gray-100: #f3f4f6; --gray-200: #e5e7eb; --gray-300: #d1d5db;
        --gray-500: #6b7280; --gray-700: #374151; --gray-900: #111827;
        --radius-sm: 4px; --radius-md: 6px; --radius-lg: 8px;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    /* Stat Cards Benchmark */
    .stat-cards-grid {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.25rem; margin-bottom: 1.75rem;
    }
    .stat-card {
        background: #fff; border-radius: var(--radius-lg); padding: 1.25rem;
        border: 1px solid var(--gray-200); box-shadow: 0 1px 3px rgba(2,38,72,0.05);
        display: flex; align-items: center; gap: 1rem; position: relative; overflow: hidden;
    }
    .stat-card::before {
        content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; background: var(--navy);
    }
    .stat-card.internal-card::before { background: var(--blue); }
    .stat-card.pleno-card::before   { background: var(--gold); }
    .stat-icon {
        width: 44px; height: 44px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; background: var(--gray-100); color: var(--navy);
    }
    .stat-info h4 {
        margin: 0; font-size: 0.75rem; color: var(--gray-500);
        font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;
    }
    .stat-info .value {
        font-size: 1.6rem; font-weight: 800; color: var(--navy); margin-top: 0.2rem;
        font-family: 'Montserrat', sans-serif;
    }

    /* Buttons */
    .btn-solid-navy {
        background: var(--navy); color: white; padding: 0.55rem 1.15rem;
        border-radius: var(--radius-md); font-weight: 600; font-size: 0.875rem;
        border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem;
        text-decoration: none; transition: all 0.2s cubic-bezier(0.4,0,0.2,1);
        box-shadow: 0 2px 4px rgba(2,38,72,0.12);
    }
    .btn-solid-navy:hover { background: var(--navy-light); color: white; transform: translateY(-1px); }
    .btn-outline-secondary {
        background: white; color: var(--gray-700); padding: 0.55rem 1.15rem;
        border-radius: var(--radius-md); font-weight: 600; font-size: 0.875rem;
        border: 1px solid var(--gray-300); cursor: pointer; display: inline-flex;
        align-items: center; gap: 0.5rem; text-decoration: none; transition: all 0.2s ease;
    }
    .btn-outline-secondary:hover { background: var(--gray-100); color: var(--navy); }

    /* Filter Card */
    .filter-card {
        background: white; border-radius: var(--radius-lg); padding: 1.25rem 1.5rem;
        border: 1px solid var(--gray-200); box-shadow: 0 1px 3px rgba(0,0,0,0.05); margin-bottom: 1.5rem;
    }
    .filter-grid {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.25rem; align-items: flex-end;
    }
    .form-group { display: flex; flex-direction: column; gap: 0.4rem; }
    .form-group label { font-size: 0.85rem; font-weight: 700; color: var(--navy); }
    .form-control {
        padding: 0.55rem 0.875rem; border-radius: var(--radius-md);
        border: 1px solid var(--gray-300); font-size: 0.875rem; outline: none;
        background: white; transition: all 0.2s ease; width: 100%;
    }
    .form-control:focus { border-color: var(--navy); box-shadow: 0 0 0 3px rgba(2,38,72,0.1); }

    /* Page Actions Row */
    .page-actions-row {
        display: flex; justify-content: flex-end; margin-bottom: 1.25rem; gap: 0.75rem;
    }

    /* Table Container */
    .table-container { background: white; border-radius: var(--radius-lg); border: 1px solid var(--gray-200); box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden; }
    .table-wrapper { overflow-x: auto; }
    .table { width: 100%; border-collapse: collapse; min-width: 800px; }
    .table thead { background: var(--gray-50); border-bottom: 1px solid var(--gray-200); }
    .table th { padding: 0.875rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: var(--gray-700); text-transform: uppercase; letter-spacing: 0.05em; }
    .table td { padding: 1rem; border-bottom: 1px solid var(--gray-100); font-size: 0.875rem; color: var(--gray-900); vertical-align: middle; }
    .table tbody tr:hover { background: var(--gray-50); }

    /* Action Trigger (⋮) */
    .aksi-wrapper { position: relative; display: inline-block; }
    .btn-aksi-trigger {
        width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center;
        background: var(--navy); color: #ffffff; border: none; border-radius: var(--radius-md);
        cursor: pointer; transition: all 0.2s cubic-bezier(0.4,0,0.2,1);
    }
    .btn-aksi-trigger:hover { background: var(--navy-light); transform: scale(1.08) translateY(-1px); }
    .aksi-dropdown {
        display: block; position: fixed; min-width: 175px; background: #fff;
        border: 1px solid var(--gray-200); border-radius: var(--radius-md);
        box-shadow: 0 14px 32px rgba(2,38,72,0.18); padding: 6px; z-index: 9999;
        opacity: 0; visibility: hidden; transform: translateY(-8px) scale(0.96);
        transition: opacity 0.18s cubic-bezier(0.16,1,0.3,1), transform 0.18s cubic-bezier(0.16,1,0.3,1), visibility 0.18s;
        pointer-events: none;
    }
    .aksi-dropdown.is-open { opacity: 1; visibility: visible; transform: translateY(0) scale(1); pointer-events: auto; }
    .aksi-item {
        display: flex; align-items: center; gap: 9px; width: 100%;
        padding: 0.55rem 0.65rem; font-size: 0.8125rem; font-weight: 600;
        border-radius: var(--radius-sm); color: var(--gray-900); text-decoration: none !important;
        border: none; background: transparent; text-align: left; cursor: pointer;
        transition: all 0.18s cubic-bezier(0.4,0,0.2,1);
    }
    .aksi-item:hover { background: var(--gray-100); transform: translateX(4px); }
    .aksi-item.aksi-edit:hover  { color: var(--blue); }
    .aksi-item.aksi-delete:hover { color: var(--red); background: #fef2f2; }
    .aksi-divider { height: 1px; background: var(--gray-200); margin: 4px 0; }

    /* Modals Custom Standard & Professional */
    .modal-overlay {
        --navy: #022648; --navy-dark: #01162f; --navy-light: #0a3a6b;
        --gray-50: #f9fafb; --gray-100: #f3f4f6; --gray-200: #e5e7eb; --gray-300: #d1d5db;
        --gray-500: #6b7280; --gray-700: #374151; --gray-900: #111827;
        --radius-sm: 4px; --radius-md: 6px; --radius-lg: 8px;
        position: fixed !important;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(2, 38, 72, 0.48); backdrop-filter: blur(4px);
        display: none !important; align-items: center; justify-content: center;
        z-index: 9999; padding: 1.5rem;
        opacity: 0 !important; visibility: hidden !important; pointer-events: none !important;
    }
    .modal-overlay.active {
        display: flex !important; visibility: visible !important; opacity: 1 !important;
        pointer-events: auto !important;
    }
    .modal-content-lg {
        background: #ffffff; border-radius: 12px; max-width: 960px; width: 95vw;
        box-shadow: 0 24px 48px rgba(2, 38, 72, 0.25); border: 1px solid rgba(2, 38, 72, 0.1);
        overflow: hidden; transform: scale(0.94) translateY(12px);
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1); max-height: 88vh;
        display: flex; flex-direction: column;
    }
    #modalImportNotulensi .modal-content-lg,
    #modalBulkPdfNotulensi .modal-content-lg {
        max-width: 960px !important;
        width: 95vw !important;
    }
    .modal-content-lg form {
        display: flex;
        flex-direction: column;
        flex: 1 1 auto;
        min-height: 0;
        overflow: hidden;
    }
    .modal-overlay.active .modal-content-lg { transform: scale(1) translateY(0); }
    .modal-header-prof {
        padding: 1.2rem 1.5rem; background: linear-gradient(135deg, #022648 0%, #01162f 100%);
        color: white; display: flex; justify-content: space-between; align-items: center;
        flex-shrink: 0;
    }
    .modal-body-prof {
        padding: 1.5rem;
        overflow-y: auto;
        max-height: calc(85vh - 130px);
        flex: 1 1 auto;
    }
    .modal-footer-prof {
        padding: 1rem 1.5rem; background: #f8f9fc; border-top: 1px solid #e5e7eb;
        display: flex; justify-content: flex-end; gap: 0.75rem;
        flex-shrink: 0; margin-top: auto;
    }

    .notulensi-foto-card {
        position: relative;
        width: 88px;
        height: 88px;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #cbd5e1;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        background: #0f172a;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .notulensi-foto-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(2, 38, 72, 0.15);
    }
    .notulensi-foto-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .notulensi-foto-card .btn-remove-foto {
        position: absolute;
        top: 4px;
        right: 4px;
        background: #dc2626;
        color: white;
        border: none;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        transition: background 0.2s;
    }
    .notulensi-foto-card .btn-remove-foto:hover {
        background: #991b1b;
    }

    .btn-badge-pdf {
        background: #f0f7ff;
        color: #022648;
        border: 1px solid #cbd5e1;
        padding: 4px 10px;
        border-radius: 6px;
        font-weight: 700;
        font-size: 0.775rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 1px 2px rgba(2, 38, 72, 0.04);
    }
    .btn-badge-pdf:hover {
        background: #022648;
        color: #ffffff;
        border-color: #022648;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(2, 38, 72, 0.15);
    }

    .btn-badge-foto {
        background: #fffdf5;
        color: #b7830f;
        border: 1px solid #fef08a;
        padding: 4px 10px;
        border-radius: 6px;
        font-weight: 700;
        font-size: 0.775rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 1px 2px rgba(183, 131, 15, 0.04);
    }
    .btn-badge-foto:hover {
        background: #b7830f;
        color: #ffffff;
        border-color: #b7830f;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(183, 131, 15, 0.18);
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
</style>
@endpush

@section('content')
<div class="admin-ui-scope" style="padding-top: 0.5rem;">

    <!-- Stat Cards Top Benchmark -->
    <div class="stat-cards-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: #e0f2fe; color: #0369a1;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
            </div>
            <div class="stat-info">
                <h4>Total Notulensi</h4>
                <div class="value">{{ $notulensis->total() }}</div>
            </div>
        </div>

        <div class="stat-card internal-card">
            <div class="stat-icon" style="background: #eff6ff; color: #2563eb;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </div>
            <div class="stat-info">
                <h4>Rapat Internal</h4>
                <div class="value" style="color: var(--blue);">{{ $notulensis->whereNull('agenda_id')->count() }}</div>
            </div>
        </div>

        <div class="stat-card pleno-card">
            <div class="stat-icon" style="background: #fefce8; color: var(--gold);">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            </div>
            <div class="stat-info">
                <h4>Terkait Agenda</h4>
                <div class="value" style="color: var(--gold);">{{ $notulensis->whereNotNull('agenda_id')->count() }}</div>
            </div>
        </div>
    </div>

    <!-- Filter Box -->
    <div class="filter-card">
        <form action="{{ route('admin.sekretariat.notulensi.index') }}" method="GET">
            <div class="filter-grid">
                <div class="form-group">
                    <label for="search">Cari Judul Rapat / Pemimpin</label>
                    <input type="text" name="search" id="search" class="form-control" placeholder="Ketik kata kunci..." value="{{ request('search') }}">
                </div>

                <div style="display: flex; gap: 0.5rem; align-items: flex-end;">
                    <button type="submit" class="btn-solid-navy" style="white-space: nowrap;">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        Filter
                    </button>
                    @if(request()->filled('search'))
                        <a href="{{ route('admin.sekretariat.notulensi.index') }}" class="btn-outline-secondary">Reset</a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Page Action Row (100% Identical to Surat & SK Benchmark) -->
    <div class="page-actions-row" style="display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 1.25rem;">
        <button type="button" class="btn-solid-navy" onclick="openCreateModal()">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Tambah Notulensi Baru
        </button>

        <button type="button" onclick="openImportNotulensiModal()" style="background: #059669; color: white; border: none; padding: 0.55rem 1rem; border-radius: var(--radius-md); font-weight: 600; font-size: 0.875rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s; box-shadow: 0 2px 6px rgba(5, 150, 105, 0.2);" onmouseover="this.style.background='#047857'" onmouseout="this.style.background='#059669'">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            Import Excel Notulensi
        </button>

        <button type="button" onclick="openBulkPdfNotulensiModal()" style="background: #b7830f; color: white; border: none; padding: 0.55rem 1rem; border-radius: var(--radius-md); font-weight: 600; font-size: 0.875rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s; box-shadow: 0 2px 6px rgba(183, 131, 15, 0.2);" onmouseover="this.style.background='#966a0c'" onmouseout="this.style.background='#b7830f'">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
            Bulk Upload Multi-PDF
        </button>
    </div>

    <!-- Table Container -->
    <div class="table-container">
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">
                            <input type="checkbox" id="check-all-notulensi" style="cursor: pointer; width: 16px; height: 16px;">
                        </th>
                        <th>JUDUL RAPAT</th>
                        <th>BERKAS RISALAH & FOTO</th>
                        <th>TAUTAN AGENDA</th>
                        <th>TANGGAL & WAKTU</th>
                        <th>PEMIMPIN RAPAT</th>
                        <th style="text-align: center; width: 80px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notulensis as $item)
                    <tr>
                        <td style="text-align: center;">
                            <input type="checkbox" class="check-notulensi-item" value="{{ $item->id }}" style="cursor: pointer; width: 16px; height: 16px;">
                        </td>
                        <td>
                            <strong style="color: var(--navy);">{{ $item->judul_rapat }}</strong>
                            @if($item->ringkasan_hasil)
                                <div style="font-size: 0.8rem; color: var(--gray-500); margin-top: 2px;">{{ Str::limit($item->ringkasan_hasil, 80) }}</div>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; flex-direction: column; gap: 5px; align-items: flex-start;">
                                @if($item->file_pdf)
                                    <button type="button" onclick="previewNotulensiPdf('{{ Storage::url($item->file_pdf) }}', '{{ addslashes($item->judul_rapat) }}')" class="btn-badge-pdf">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
                                        <span>Risalah PDF</span>
                                    </button>
                                @endif
                                @if($item->foto_dokumentasi && is_array($item->foto_dokumentasi) && count($item->foto_dokumentasi) > 0)
                                    <button type="button" onclick="openFotoGallery({{ json_encode(array_map(fn($f) => Storage::url($f), $item->foto_dokumentasi)) }}, '{{ addslashes($item->judul_rapat) }}')" class="btn-badge-foto">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                        <span>Dokumentasi ({{ count($item->foto_dokumentasi) }} Foto)</span>
                                    </button>
                                @endif
                                @if(!$item->file_pdf && (!$item->foto_dokumentasi || count($item->foto_dokumentasi) == 0))
                                    <span style="color: var(--gray-500); font-size: 0.8125rem; font-weight: 500;">-</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($item->agenda)
                                <span style="font-size: 0.8rem; padding: 3px 8px; background: #e0f2fe; color: #0369a1; border-radius: 6px; font-weight: 600; display: inline-block;">
                                    {{ $item->agenda->judul }}
                                </span>
                            @else
                                <span style="color: var(--gray-500); font-size: 0.8rem;">-</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_rapat)->format('d M Y, H:i') }} WIB</td>
                        <td>{{ $item->pemimpin_rapat ?? '-' }}</td>
                        <td style="text-align: center;">
                            <!-- Action Dropdown Trigger (⋮) -->
                            <div class="aksi-wrapper">
                                <button type="button" class="btn-aksi-trigger" data-target="dropdown-not-{{ $item->id }}" aria-label="Menu Aksi">
                                    <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                        <circle cx="12" cy="5" r="1.75"></circle>
                                        <circle cx="12" cy="12" r="1.75"></circle>
                                        <circle cx="12" cy="19" r="1.75"></circle>
                                    </svg>
                                </button>

                                <div class="aksi-dropdown" id="dropdown-not-{{ $item->id }}">
                                    @if($item->file_pdf)
                                    <button type="button" class="aksi-item aksi-view" onclick="previewNotulensiPdf('{{ Storage::url($item->file_pdf) }}', '{{ addslashes($item->judul_rapat) }}')">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        Pratinjau PDF Risalah
                                    </button>
                                    @endif

                                    @if($item->foto_dokumentasi && is_array($item->foto_dokumentasi) && count($item->foto_dokumentasi) > 0)
                                    <button type="button" class="aksi-item aksi-view" onclick="openFotoGallery({{ json_encode(array_map(fn($f) => Storage::url($f), $item->foto_dokumentasi)) }}, '{{ addslashes($item->judul_rapat) }}')">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                        Galeri Dokumentasi ({{ count($item->foto_dokumentasi) }} Foto)
                                    </button>
                                    @endif

                                    @if($item->link_drive)
                                    <a href="{{ $item->link_drive }}" target="_blank" class="aksi-item aksi-view">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                                        Buka Dokumen Drive
                                    </a>
                                    @endif

                                    @if($item->file_pdf || ($item->foto_dokumentasi && count($item->foto_dokumentasi) > 0) || $item->link_drive)
                                    <div class="aksi-divider"></div>
                                    @endif

                                    <button type="button" class="aksi-item aksi-edit" onclick="openEditModal({{ json_encode($item) }})">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        Edit Notulensi
                                    </button>

                                    <div class="aksi-divider"></div>

                                    <button type="button" class="aksi-item aksi-delete" onclick="confirmDeleteNotulensi({{ $item->id }}, '{{ addslashes($item->judul_rapat) }}')">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        Hapus Notulensi
                                    </button>
                                    <form id="delete-form-{{ $item->id }}" action="{{ route('admin.sekretariat.notulensi.destroy', $item->id) }}" method="POST" style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 3rem; color: var(--gray-500);">Belum ada data Notulensi Rapat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Floating / Sticky Bulk Action Bar for Notulensi -->
    <div id="bulk-action-bar-notulensi" style="display: none; position: sticky; bottom: 20px; z-index: 99; background: #022648; color: white; padding: 12px 20px; border-radius: 8px; margin-top: 1.25rem; align-items: center; justify-content: space-between; box-shadow: 0 8px 24px rgba(2, 38, 72, 0.25);">
        <div style="display: flex; align-items: center; gap: 10px; font-size: 0.875rem;">
            <span style="background: #b7830f; color: white; width: 26px; height: 26px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem;" id="selected-notulensi-count">0</span>
            <strong>Notulensi Rapat Terpilih</strong>
        </div>
        <div style="display: flex; gap: 10px;">
            <button type="button" onclick="executeBulkDownloadNotulensi()" style="background: #059669; color: white; border: none; padding: 7px 16px; border-radius: 6px; font-weight: 700; font-size: 0.8rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: background 0.2s;" onmouseover="this.style.background='#047857'" onmouseout="this.style.background='#059669'">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Download Terpilih (ZIP)
            </button>
            <button type="button" onclick="executeBulkDeleteNotulensi()" style="background: #dc2626; color: white; border: none; padding: 7px 16px; border-radius: 6px; font-weight: 700; font-size: 0.8rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: background 0.2s;" onmouseover="this.style.background='#b91c1c'" onmouseout="this.style.background='#dc2626'">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                Hapus Terpilih
            </button>
        </div>
    </div>

    <form id="bulk-delete-notulensi-form" action="{{ route('admin.sekretariat.notulensi.bulk-delete') }}" method="POST" style="display:none;">
        @csrf
    </form>

    <form id="bulk-download-notulensi-form" action="{{ route('admin.sekretariat.notulensi.bulk-download') }}" method="POST" style="display:none;">
        @csrf
    </form>

    <div style="margin-top: 1rem;">
        {{ $notulensis->links() }}
    </div>

</div>

<!-- Create Modal (Standard & Professional) -->
<div class="modal-overlay" id="createModal" onclick="if(event.target===this) closeCreateModal()">
    <div class="modal-content-lg">
        <div class="modal-header-prof">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(255,255,255,0.15); display: flex; align-items: center; justify-content: center;">
                    <svg viewBox="0 0 24 24" width="20" height="20" stroke="white" fill="none" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                </div>
                <div>
                    <h3 style="font-size: 1.05rem; font-weight: 800; color: white; margin: 0;">Tambah Notulensi Rapat Baru</h3>
                    <span style="font-size: 0.725rem; color: #94a3b8;">Catat hasil rapat dan keputusannya secara rapi</span>
                </div>
            </div>
            <button type="button" onclick="closeCreateModal()" style="background: rgba(255,255,255,0.1); border: none; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer;">&times;</button>
        </div>

        <form action="{{ route('admin.sekretariat.notulensi.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body-prof">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label style="font-size: 0.8rem; font-weight: 700; color: var(--navy);">Tautkan ke Agenda / Rapat (Opsional)</label>
                        <select name="agenda_id" class="form-control select2-basic" style="width: 100%;">
                            <option value="">-- Pilih Agenda Rapat Terkait --</option>
                            @foreach($agendas as $ag)
                                <option value="{{ $ag->id }}">{{ $ag->judul }} ({{ \Carbon\Carbon::parse($ag->waktu_mulai)->format('d M Y') }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label style="font-size: 0.8rem; font-weight: 700; color: var(--navy);">Judul Rapat <span style="color: red;">*</span></label>
                        <input type="text" name="judul_rapat" class="form-control" placeholder="Contoh: Rapat Kerja Sekretariat Nasional..." required style="font-size: 0.85rem;">
                    </div>

                    <div class="form-group">
                        <label style="font-size: 0.8rem; font-weight: 700; color: var(--navy);">Tanggal & Waktu Rapat <span style="color: red;">*</span></label>
                        <input type="text" name="tanggal_rapat" class="form-control datetimepicker" style="background: white; font-size: 0.85rem;" value="{{ date('Y-m-d H:i') }}" placeholder="Pilih tanggal & waktu..." required>
                    </div>

                    <div class="form-group">
                        <label style="font-size: 0.8rem; font-weight: 700; color: var(--navy);">Pemimpin Rapat</label>
                        <input type="text" name="pemimpin_rapat" class="form-control" placeholder="Nama pemimpin rapat..." style="font-size: 0.85rem;">
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label style="font-size: 0.8rem; font-weight: 700; color: var(--navy);">Ringkasan Hasil Notulensi</label>
                        <textarea name="ringkasan_hasil" class="form-control" style="height: 85px; font-size: 0.85rem;" placeholder="Poin-poin penting hasil rapat..."></textarea>
                    </div>

                    <!-- Upload PDF Risalah Notulensi -->
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label style="font-size: 0.8rem; font-weight: 700; color: var(--navy);">Upload File Risalah Notulensi (PDF / Word)</label>
                        <div class="file-upload-zone" onclick="document.getElementById('createPdfInput').click()" style="border: 2px dashed var(--gray-300); background: #f8fafc; padding: 1rem; text-align: center; border-radius: 8px; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#022648" stroke-width="2" style="margin-bottom: 4px;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
                            <div style="font-size: 0.775rem; font-weight: 600; color: #334155;" id="createPdfFileNameLabel">Klik / Drag berkas risalah format .pdf, .doc, .docx</div>
                            <input type="file" id="createPdfInput" name="file_pdf" accept=".pdf,.doc,.docx" style="display: none;" onchange="handleCreatePdfChange(this)">
                        </div>
                        <div id="createPdfPreviewBox" style="display: none; margin-top: 0.75rem; border: 1px solid #cbd5e1; border-radius: 8px; overflow: hidden; background: #0f172a;">
                            <div style="background: #022648; color: white; padding: 6px 12px; font-size: 0.75rem; font-weight: 700; display: flex; justify-content: space-between; align-items: center;">
                                <span>📄 Pratinjau Dokumen Risalah PDF</span>
                                <button type="button" onclick="toggleCreatePdfPreview()" style="background: rgba(255,255,255,0.15); border: none; color: white; padding: 2px 8px; border-radius: 4px; font-size: 0.7rem; cursor: pointer;" id="btnToggleCreatePdf">Sembunyikan</button>
                            </div>
                            <iframe id="createPdfIframe" style="width: 100%; height: 320px; border: none;" src="about:blank"></iframe>
                        </div>
                    </div>

                    <!-- Upload Foto Dokumentasi Rapat (Multiple) -->
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label style="font-size: 0.8rem; font-weight: 700; color: var(--navy);">Upload Gambar Dokumentasi Rapat (Bisa Pilih Banyak Foto)</label>
                        <div class="file-upload-zone" onclick="document.getElementById('createFotoInput').click()" style="border: 2px dashed #b7830f; background: #fffdf5; padding: 1rem; text-align: center; border-radius: 8px; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#fff9e6'" onmouseout="this.style.background='#fffdf5'">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#b7830f" stroke-width="2" style="margin-bottom: 4px;"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            <div style="font-size: 0.775rem; font-weight: 600; color: #022648;" id="createFotoNameLabel">Klik / Drag foto-foto dokumentasi (.jpg, .jpeg, .png, .webp)</div>
                            <input type="file" id="createFotoInput" name="foto_dokumentasi[]" multiple accept="image/*" style="display: none;" onchange="handleCreateFotoChange(this)">
                        </div>
                        <div id="createFotoPreviewGrid" style="display: none; margin-top: 0.75rem; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; padding: 0.75rem;">
                            <div style="font-size: 0.75rem; font-weight: 700; color: #022648; margin-bottom: 0.5rem;" id="createFotoPreviewTitle">Pratinjau Foto Dokumentasi Terpilih:</div>
                            <div id="createFotoThumbnails" style="display: flex; gap: 10px; flex-wrap: wrap;"></div>
                        </div>
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label style="font-size: 0.8rem; font-weight: 700; color: var(--navy);">Link Tautan Dokumen (Google Drive)</label>
                        <input type="url" name="link_drive" class="form-control" placeholder="https://drive.google.com/file/d/..." style="font-size: 0.85rem;">
                    </div>
                </div>
            </div>

            <div class="modal-footer-prof">
                <button type="button" onclick="closeCreateModal()" class="btn-outline-secondary">Batal</button>
                <button type="submit" class="btn-solid-navy" style="font-weight: 700;" onclick="if(typeof Toast !== 'undefined') Toast.fire({ icon: 'success', title: 'Notulensi Rapat sedang disimpan...' })">Simpan Notulensi</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal (Standard & Professional) -->
<div class="modal-overlay" id="editModal" onclick="if(event.target===this) closeEditModal()">
    <div class="modal-content-lg">
        <div class="modal-header-prof">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(255,255,255,0.15); display: flex; align-items: center; justify-content: center;">
                    <svg viewBox="0 0 24 24" width="20" height="20" stroke="white" fill="none" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                </div>
                <div>
                    <h3 style="font-size: 1.05rem; font-weight: 800; color: white; margin: 0;">Edit Notulensi Rapat</h3>
                    <span style="font-size: 0.725rem; color: #94a3b8;">Perbarui informasi hasil rapat</span>
                </div>
            </div>
            <button type="button" onclick="closeEditModal()" style="background: rgba(255,255,255,0.1); border: none; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer;">&times;</button>
        </div>

        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="modal-body-prof">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label style="font-size: 0.8rem; font-weight: 700; color: var(--navy);">Tautkan ke Agenda / Rapat (Opsional)</label>
                        <select name="agenda_id" id="editAgendaId" class="form-control select2-basic" style="width: 100%;">
                            <option value="">-- Pilih Agenda Rapat Terkait --</option>
                            @foreach($agendas as $ag)
                                <option value="{{ $ag->id }}">{{ $ag->judul }} ({{ \Carbon\Carbon::parse($ag->waktu_mulai)->format('d M Y') }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label style="font-size: 0.8rem; font-weight: 700; color: var(--navy);">Judul Rapat <span style="color: red;">*</span></label>
                        <input type="text" name="judul_rapat" id="editJudulRapat" class="form-control" required style="font-size: 0.85rem;">
                    </div>

                    <div class="form-group">
                        <label style="font-size: 0.8rem; font-weight: 700; color: var(--navy);">Tanggal & Waktu Rapat <span style="color: red;">*</span></label>
                        <input type="text" name="tanggal_rapat" id="editTanggalRapat" class="form-control datetimepicker" style="background: white; font-size: 0.85rem;" required>
                    </div>

                    <div class="form-group">
                        <label style="font-size: 0.8rem; font-weight: 700; color: var(--navy);">Pemimpin Rapat</label>
                        <input type="text" name="pemimpin_rapat" id="editPemimpinRapat" class="form-control" style="font-size: 0.85rem;">
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label style="font-size: 0.8rem; font-weight: 700; color: var(--navy);">Ringkasan Hasil Notulensi</label>
                        <textarea name="ringkasan_hasil" id="editRingkasanHasil" class="form-control" style="height: 85px; font-size: 0.85rem;"></textarea>
                    </div>

                    <!-- Upload PDF Risalah Notulensi Edit -->
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label style="font-size: 0.8rem; font-weight: 700; color: var(--navy);">Ganti File Risalah Notulensi (PDF / Word)</label>
                        <div class="file-upload-zone" onclick="document.getElementById('editPdfInput').click()" style="border: 2px dashed var(--gray-300); background: #f8fafc; padding: 1rem; text-align: center; border-radius: 8px; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#022648" stroke-width="2" style="margin-bottom: 4px;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
                            <div style="font-size: 0.775rem; font-weight: 600; color: #334155;" id="editPdfFileNameLabel">Klik / Drag untuk mengganti file risalah PDF</div>
                            <input type="file" id="editPdfInput" name="file_pdf" accept=".pdf,.doc,.docx" style="display: none;" onchange="handleEditPdfChange(this)">
                        </div>
                        <div id="editPdfPreviewBox" style="display: none; margin-top: 0.75rem; border: 1px solid #cbd5e1; border-radius: 8px; overflow: hidden; background: #0f172a;">
                            <div style="background: #022648; color: white; padding: 6px 12px; font-size: 0.75rem; font-weight: 700; display: flex; justify-content: space-between; align-items: center;">
                                <span>📄 Pratinjau Dokumen Risalah PDF Baru</span>
                                <button type="button" onclick="toggleEditPdfPreview()" style="background: rgba(255,255,255,0.15); border: none; color: white; padding: 2px 8px; border-radius: 4px; font-size: 0.7rem; cursor: pointer;" id="btnToggleEditPdf">Sembunyikan</button>
                            </div>
                            <iframe id="editPdfIframe" style="width: 100%; height: 320px; border: none;" src="about:blank"></iframe>
                        </div>
                    </div>

                    <!-- Upload Foto Dokumentasi Edit -->
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label style="font-size: 0.8rem; font-weight: 700; color: var(--navy);">Tambah Gambar Dokumentasi (Bisa Pilih Banyak Foto)</label>
                        <div class="file-upload-zone" onclick="document.getElementById('editFotoInput').click()" style="border: 2px dashed #b7830f; background: #fffdf5; padding: 1rem; text-align: center; border-radius: 8px; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#fff9e6'" onmouseout="this.style.background='#fffdf5'">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#b7830f" stroke-width="2" style="margin-bottom: 4px;"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            <div style="font-size: 0.775rem; font-weight: 600; color: #022648;" id="editFotoNameLabel">Klik / Drag untuk menambah foto dokumentasi</div>
                            <input type="file" id="editFotoInput" name="foto_dokumentasi[]" multiple accept="image/*" style="display: none;" onchange="handleEditFotoChange(this)">
                        </div>
                        <div id="editFotoPreviewGrid" style="display: none; margin-top: 0.75rem; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; padding: 0.75rem;">
                            <div style="font-size: 0.75rem; font-weight: 700; color: #022648; margin-bottom: 0.5rem;" id="editFotoPreviewTitle">Pratinjau Foto Tambahan Terpilih:</div>
                            <div id="editFotoThumbnails" style="display: flex; gap: 10px; flex-wrap: wrap;"></div>
                        </div>
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label style="font-size: 0.8rem; font-weight: 700; color: var(--navy);">Link Tautan Dokumen (Google Drive)</label>
                        <input type="url" name="link_drive" id="editLinkDrive" class="form-control" style="font-size: 0.85rem;">
                    </div>
                </div>
            </div>

            <div class="modal-footer-prof">
                <button type="button" onclick="closeEditModal()" class="btn-outline-secondary">Batal</button>
                <button type="submit" class="btn-solid-navy" style="font-weight: 700;" onclick="if(typeof Toast !== 'undefined') Toast.fire({ icon: 'success', title: 'Notulensi Rapat berhasil diperbarui...' })">Update Notulensi</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Galeri Foto Dokumentasi Rapat -->
<div class="modal-overlay" id="modalGalleryFoto" onclick="if(event.target===this) closeFotoGallery()">
    <div class="modal-content-lg" style="max-width: 860px; max-height: 90vh; background: #0f172a; border-color: #334155;">
        <div class="modal-header-prof" style="background: #022648; padding: 1rem 1.5rem;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(183, 131, 15, 0.2); display: flex; align-items: center; justify-content: center;">
                    <svg viewBox="0 0 24 24" width="20" height="20" stroke="#b7830f" fill="none" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                </div>
                <div>
                    <h3 style="font-size: 1.05rem; font-weight: 800; color: white; margin: 0;" id="galleryModalTitle">Dokumentasi Foto Rapat</h3>
                    <span style="font-size: 0.725rem; color: #94a3b8;" id="galleryCounterLabel">Foto 1 dari 1</span>
                </div>
            </div>
            <button type="button" onclick="closeFotoGallery()" style="background: rgba(255,255,255,0.1); border: none; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer;">&times;</button>
        </div>

        <div class="modal-body-prof" style="padding: 1.5rem; display: flex; flex-direction: column; align-items: center; justify-content: center; background: #020617; position: relative; min-height: 420px;">
            <button type="button" onclick="prevGalleryImage()" id="btnGalleryPrev" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); background: rgba(2, 38, 72, 0.85); color: white; border: 1px solid rgba(255,255,255,0.2); width: 44px; height: 44px; border-radius: 50%; font-size: 1.5rem; font-weight: bold; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; z-index: 10;" onmouseover="this.style.background='#b7830f'" onmouseout="this.style.background='rgba(2, 38, 72, 0.85)'">&lsaquo;</button>

            <img id="galleryMainImage" src="" alt="Dokumentasi Rapat" style="max-width: 100%; max-height: 52vh; border-radius: 8px; object-fit: contain; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">

            <button type="button" onclick="nextGalleryImage()" id="btnGalleryNext" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); background: rgba(2, 38, 72, 0.85); color: white; border: 1px solid rgba(255,255,255,0.2); width: 44px; height: 44px; border-radius: 50%; font-size: 1.5rem; font-weight: bold; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; z-index: 10;" onmouseover="this.style.background='#b7830f'" onmouseout="this.style.background='rgba(2, 38, 72, 0.85)'">&rsaquo;</button>
            
            <div id="galleryThumbnailsContainer" style="display: flex; gap: 8px; margin-top: 1.25rem; overflow-x: auto; max-width: 100%; padding: 4px;">
                <!-- Thumbnail images populated by JS -->
            </div>
        </div>

        <div class="modal-footer-prof" style="background: #0f172a; border-top: 1px solid #1e293b; padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center;">
            <a id="btnDownloadGalleryImage" href="" target="_blank" download style="background: #059669; color: white; padding: 7px 16px; border-radius: 6px; font-weight: 700; font-size: 0.8rem; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Unduh Foto Ini
            </a>
            <button type="button" onclick="closeFotoGallery()" class="btn-outline-secondary" style="background: #1e293b; color: white; border-color: #334155;">Tutup Galeri</button>
        </div>
    </div>
</div>

<!-- Modal 1: Import Excel Notulensi Rapat (Strict SK Navy & Gold Benchmark Styling) -->
<div class="modal-overlay" id="modalImportNotulensi" onclick="if(event.target===this) closeImportNotulensiModal()">
    <div class="modal-content-lg" style="width: 92vw !important; max-width: 960px !important;">
        <div class="modal-header-prof" style="background: linear-gradient(135deg, #022648 0%, #01162f 100%); padding: 1.25rem 1.5rem; color: white;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: rgba(183, 131, 15, 0.2); display: flex; align-items: center; justify-content: center; border: 1px solid rgba(183, 131, 15, 0.4);">
                    <svg viewBox="0 0 24 24" width="22" height="22" stroke="#b7830f" fill="none" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                </div>
                <div>
                    <h3 style="font-size: 1.1rem; font-weight: 800; color: white; margin: 0;">Import / Bulk Upload Notulensi Rapat</h3>
                    <span style="font-size: 0.775rem; color: #94a3b8;">Unggah berkas data Notulensi secara massal menggunakan format Excel (.xls / .csv)</span>
                </div>
            </div>
            <button type="button" onclick="closeImportNotulensiModal()" style="background: rgba(255,255,255,0.1); border: none; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 1.2rem;">&times;</button>
        </div>

        <form action="{{ route('admin.sekretariat.notulensi.bulk-store') }}" method="POST" enctype="multipart/form-data" id="formImportNotulensi">
            @csrf
            <input type="hidden" name="bulk_type" value="excel">
            <input type="hidden" name="notulensi_rows" id="importNotulensiRowsInput">

            <div class="modal-body-prof" style="padding: 1.5rem;">
                <div style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; padding: 1.25rem; margin-bottom: 1.25rem;">
                    <div style="font-weight: 700; color: #022648; margin-bottom: 0.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                        <span style="font-size: 0.9rem;"><i class="fa fa-info-circle" style="color: #b7830f;"></i> Unduh Format Contoh Import Notulensi</span>
                        <a href="{{ route('admin.sekretariat.notulensi.template') }}" onclick="Toast.fire({ icon: 'success', title: 'Mengunduh format contoh Excel Notulensi...' })" style="background: #059669; color: white; padding: 7px 14px; border-radius: 6px; font-size: 0.775rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: background 0.2s;" onmouseover="this.style.background='#047857'" onmouseout="this.style.background='#059669'">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            Unduh Format Contoh (.xls)
                        </a>
                    </div>
                    <p style="font-size: 0.8125rem; color: #64748b; margin: 0; line-height: 1.5;">
                        Silakan unduh berkas contoh di atas untuk melihat susunan 5 kolom: <strong>Judul Rapat, Tanggal Rapat, Pemimpin Rapat, Ringkasan Hasil Rapat, Link Google Drive</strong>.
                    </p>
                </div>

                <div class="form-group-full">
                    <label class="form-label" style="font-weight: 700; color: #022648; font-size: 0.875rem; margin-bottom: 0.5rem; display: block;">Pilih Berkas Excel / CSV (.xls, .xlsx, .csv)</label>
                    <div class="file-upload-zone" onclick="document.getElementById('importNotulensiFile').click()" style="border: 2px dashed #b7830f; background: #fffdf5; padding: 1.25rem 1rem; text-align: center; border-radius: 8px; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#fff9e6'" onmouseout="this.style.background='#fffdf5'">
                        <input type="file" id="importNotulensiFile" name="excel_file" accept=".xls,.xlsx,.csv" onchange="handleImportNotulensiFile(this)" style="display: none;">
                        <div style="pointer-events: none;">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#b7830f" stroke-width="2" style="margin-bottom: 0.35rem;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            <div style="font-weight: 700; color: #022648; font-size: 0.9rem; margin-bottom: 2px;" id="importFileLabelNotulensi">Klik atau Tarik Berkas Excel ke Sini</div>
                            <span style="font-size: 0.775rem; color: #64748b;">Format yang didukung: .xls, .xlsx, .csv</span>
                        </div>
                    </div>
                </div>

                <div id="importNotulensiPreviewContainer" style="display: none; margin-top: 1.25rem;">
                    <div id="importNotulensiDuplicateAlert" style="display: none; background: #fffbeb; border: 1px solid #fde68a; color: #92400e; padding: 10px 14px; border-radius: 8px; margin-bottom: 0.75rem; font-size: 0.8125rem; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                        <div>
                            <i class="fa fa-exclamation-triangle" style="color: #d97706; margin-right: 6px;"></i>
                            Terdeteksi <strong id="importNotulensiDupCount" style="color: #dc2626; font-size: 0.9rem;">0</strong> data duplikat!
                        </div>
                        <button type="button" onclick="cleanImportNotulensiDuplicates()" style="background: #d97706; color: white; border: none; padding: 5px 12px; border-radius: 6px; font-size: 0.775rem; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: background 0.2s;" onmouseover="this.style.background='#b45309'" onmouseout="this.style.background='#d97706'">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                            Bersihkan Data Duplikat
                        </button>
                    </div>

                    <div style="font-weight: 700; color: #022648; font-size: 0.85rem; margin-bottom: 0.5rem; display: flex; justify-content: space-between; align-items: center;">
                        <span>Pratinjau Data Terbaca (<span id="importNotulensiCount">0</span>)</span>
                    </div>
                    <div style="max-height: 280px; overflow-y: auto; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.775rem;">
                        <table style="width: 100%; border-collapse: collapse; min-width: 820px;">
                            <thead>
                                <tr style="background: #022648; color: white;">
                                    <th style="padding: 10px 14px; text-align: left; width: 26%;">Judul Rapat</th>
                                    <th style="padding: 10px 14px; text-align: center; width: 18%; white-space: nowrap;">Tanggal Rapat</th>
                                    <th style="padding: 10px 14px; text-align: left; width: 20%;">Pemimpin Rapat</th>
                                    <th style="padding: 10px 14px; text-align: left; width: 28%;">Ringkasan Hasil</th>
                                    <th style="padding: 10px 14px; text-align: center; width: 8%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="importNotulensiPreviewTableBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>    </div>

            <div class="modal-footer-prof" style="padding: 1rem 1.5rem; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 0.75rem;">
                <button type="button" class="btn-outline-secondary" onclick="closeImportNotulensiModal()">Batal</button>
                <button type="submit" id="btnSubmitImportNotulensi" class="btn-solid-navy" style="padding: 0.55rem 1.25rem;" onclick="if(typeof Toast !== 'undefined') Toast.fire({ icon: 'success', title: 'Mengimpor data Notulensi Rapat dari Excel...' })" disabled>
                    Proses Import Massal
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 2: Bulk Upload Multi-PDF Notulensi Rapat (Strict SK Navy & Gold Benchmark Styling) -->
<div class="modal-overlay" id="modalBulkPdfNotulensi" onclick="if(event.target===this) closeBulkPdfNotulensiModal()">
    <div class="modal-content-lg" style="max-width: 820px; max-height: 90vh;">
        <div class="modal-header-prof" style="background: linear-gradient(135deg, #022648 0%, #01162f 100%); padding: 1.25rem 1.5rem; color: white; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: rgba(183, 131, 15, 0.2); display: flex; align-items: center; justify-content: center; border: 1px solid rgba(183, 131, 15, 0.4);">
                    <svg viewBox="0 0 24 24" width="22" height="22" stroke="#b7830f" fill="none" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
                </div>
                <div>
                    <h3 style="font-size: 1.1rem; font-weight: 800; color: white; margin: 0;">Bulk Upload Multi-PDF Notulensi Rapat</h3>
                    <span style="font-size: 0.775rem; color: #94a3b8;">Unggah banyak berkas PDF / Word sekaligus & isi metadata masing-masing berkas</span>
                </div>
            </div>
            <button type="button" onclick="closeBulkPdfNotulensiModal()" style="background: rgba(255,255,255,0.1); border: none; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 1.2rem;">&times;</button>
        </div>

        <form action="{{ route('admin.sekretariat.notulensi.bulk-store') }}" method="POST" enctype="multipart/form-data" id="formBulkPdfNotulensi">
            @csrf
            <input type="hidden" name="bulk_type" value="files">

            <div class="modal-body-prof" style="padding: 1.5rem; overflow-y: auto; max-height: 65vh;">
                <!-- Drag & Drop Zone -->
                <div id="bulkPdfNotulensiDropZone" class="file-upload-zone" style="border: 2px dashed #b7830f; background: #fffdf5; padding: 1.5rem; text-align: center; border-radius: 8px; cursor: pointer; margin-bottom: 1.5rem; transition: all 0.2s;">
                    <input type="file" id="bulkPdfNotulensiInputFiles" multiple accept=".pdf,.doc,.docx" style="display: none;">
                    <div onclick="document.getElementById('bulkPdfNotulensiInputFiles').click()">
                        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#b7830f" stroke-width="2" style="margin-bottom: 0.5rem;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        <div style="font-size: 0.9rem; font-weight: 800; color: #022648;">Klik / Drag Banyak Berkas PDF Notulensi ke Sini</div>
                        <div style="font-size: 0.775rem; color: #966a0c; margin-top: 4px;">Dapat memilih sekaligus beberapa berkas format .pdf, .doc, .docx (Maksimal 10MB per file)</div>
                    </div>
                </div>

                <!-- Cards Container -->
                <div style="font-weight: 800; font-size: 0.875rem; color: #022648; margin-bottom: 0.75rem; display: flex; justify-content: space-between; align-items: center;">
                    <span>Daftar Berkas Terpilih (<span id="bulkPdfSelectedCountNotulensi" style="color: #b7830f;">0</span> Berkas)</span>
                </div>

                <div id="bulkPdfCardsContainerNotulensi">
                    <!-- Dynamic PDF cards generated by JS -->
                </div>
            </div>

            <div class="modal-footer-prof" style="padding: 1rem 1.5rem; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 0.75rem;">
                <button type="button" class="btn-outline-secondary" onclick="closeBulkPdfNotulensiModal()">Batal</button>
                <button type="submit" id="btnSubmitBulkPdfNotulensi" class="btn-solid-navy" style="padding: 0.55rem 1.25rem;" disabled>
                    Simpan Semua Notulensi
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof $.fn.select2 !== 'undefined') {
            $('.select2-basic').select2({ minimumResultsForSearch: -1, width: '100%' });
        }

        if (typeof flatpickr !== 'undefined') {
            flatpickr(".datetimepicker", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                allowInput: true
            });
        }

        let activeDropdown = null;

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

        document.addEventListener('click', function () {
            if (activeDropdown) { activeDropdown.classList.remove('is-open'); activeDropdown = null; }
        });
        window.addEventListener('scroll', function () {
            if (activeDropdown) { activeDropdown.classList.remove('is-open'); activeDropdown = null; }
        }, true);
    });

    // Instant Live Preview Handlers in Modal
    window.handleCreatePdfChange = function(input) {
        const file = input.files[0];
        const label = document.getElementById('createPdfFileNameLabel');
        const box = document.getElementById('createPdfPreviewBox');
        const iframe = document.getElementById('createPdfIframe');

        if (file) {
            if (label) label.textContent = file.name;
            if (file.type === 'application/pdf' && box && iframe) {
                iframe.src = URL.createObjectURL(file);
                box.style.display = 'block';
            } else if (box) {
                box.style.display = 'none';
            }
        } else {
            if (label) label.textContent = 'Klik / Drag berkas risalah format .pdf, .doc, .docx';
            if (box) box.style.display = 'none';
        }
    };

    window.toggleCreatePdfPreview = function() {
        const iframe = document.getElementById('createPdfIframe');
        const btn = document.getElementById('btnToggleCreatePdf');
        if (!iframe) return;
        if (iframe.style.display === 'none') {
            iframe.style.display = 'block';
            if (btn) btn.innerText = 'Sembunyikan';
        } else {
            iframe.style.display = 'none';
            if (btn) btn.innerText = 'Buka Pratinjau';
        }
    };

    window.createFotoSelectedFiles = [];
    window.editFotoSelectedFiles = [];

    window.handleCreateFotoChange = function(input) {
        if (input.files && input.files.length > 0) {
            window.createFotoSelectedFiles = Array.from(input.files);
        }
        renderCreateFotoPreviews();
    };

    window.removeCreateFoto = function(idx) {
        window.createFotoSelectedFiles.splice(idx, 1);
        const dt = new DataTransfer();
        window.createFotoSelectedFiles.forEach(f => dt.items.add(f));
        const input = document.getElementById('createFotoInput');
        if (input) input.files = dt.files;
        renderCreateFotoPreviews();
    };

    function renderCreateFotoPreviews() {
        const files = window.createFotoSelectedFiles || [];
        const label = document.getElementById('createFotoNameLabel');
        const grid = document.getElementById('createFotoPreviewGrid');
        const container = document.getElementById('createFotoThumbnails');
        const title = document.getElementById('createFotoPreviewTitle');

        if (files.length > 0) {
            if (label) label.textContent = `${files.length} foto dokumentasi terpilih`;
            if (title) title.textContent = `Pratinjau ${files.length} Foto Dokumentasi Terpilih (Bisa Dihapus Per Foto):`;
            if (container) {
                container.innerHTML = '';
                files.forEach((file, idx) => {
                    const url = URL.createObjectURL(file);
                    const sizeMb = (file.size / (1024 * 1024)).toFixed(1);
                    container.innerHTML += `
                        <div class="notulensi-foto-card" title="${file.name} (${sizeMb} MB)">
                            <img src="${url}">
                            <button type="button" class="btn-remove-foto" onclick="removeCreateFoto(${idx})" title="Hapus foto ini">&times;</button>
                            <span style="position: absolute; bottom: 3px; left: 3px; background: rgba(2,38,72,0.85); color: white; font-size: 0.65rem; padding: 1px 5px; border-radius: 3px; font-weight: 700;">#${idx+1}</span>
                        </div>
                    `;
                });
            }
            if (grid) grid.style.display = 'block';
        } else {
            if (label) label.textContent = 'Klik / Drag foto-foto dokumentasi (.jpg, .jpeg, .png, .webp)';
            if (grid) grid.style.display = 'none';
        }
    }

    window.handleEditPdfChange = function(input) {
        const file = input.files[0];
        const label = document.getElementById('editPdfFileNameLabel');
        const box = document.getElementById('editPdfPreviewBox');
        const iframe = document.getElementById('editPdfIframe');

        if (file) {
            if (label) label.textContent = file.name;
            if (file.type === 'application/pdf' && box && iframe) {
                iframe.src = URL.createObjectURL(file);
                box.style.display = 'block';
            } else if (box) {
                box.style.display = 'none';
            }
        } else {
            if (label) label.textContent = 'Klik / Drag untuk mengganti file risalah PDF';
            if (box) box.style.display = 'none';
        }
    };

    window.toggleEditPdfPreview = function() {
        const iframe = document.getElementById('editPdfIframe');
        const btn = document.getElementById('btnToggleEditPdf');
        if (!iframe) return;
        if (iframe.style.display === 'none') {
            iframe.style.display = 'block';
            if (btn) btn.innerText = 'Sembunyikan';
        } else {
            iframe.style.display = 'none';
            if (btn) btn.innerText = 'Buka Pratinjau';
        }
    };

    window.handleEditFotoChange = function(input) {
        if (input.files && input.files.length > 0) {
            window.editFotoSelectedFiles = Array.from(input.files);
        }
        renderEditFotoPreviews();
    };

    window.removeEditFoto = function(idx) {
        window.editFotoSelectedFiles.splice(idx, 1);
        const dt = new DataTransfer();
        window.editFotoSelectedFiles.forEach(f => dt.items.add(f));
        const input = document.getElementById('editFotoInput');
        if (input) input.files = dt.files;
        renderEditFotoPreviews();
    };

    function renderEditFotoPreviews() {
        const files = window.editFotoSelectedFiles || [];
        const label = document.getElementById('editFotoNameLabel');
        const grid = document.getElementById('editFotoPreviewGrid');
        const container = document.getElementById('editFotoThumbnails');
        const title = document.getElementById('editFotoPreviewTitle');

        if (files.length > 0) {
            if (label) label.textContent = `${files.length} foto tambahan terpilih`;
            if (title) title.textContent = `Pratinjau ${files.length} Foto Tambahan Terpilih (Bisa Dihapus Per Foto):`;
            if (container) {
                container.innerHTML = '';
                files.forEach((file, idx) => {
                    const url = URL.createObjectURL(file);
                    const sizeMb = (file.size / (1024 * 1024)).toFixed(1);
                    container.innerHTML += `
                        <div class="notulensi-foto-card" title="${file.name} (${sizeMb} MB)">
                            <img src="${url}">
                            <button type="button" class="btn-remove-foto" onclick="removeEditFoto(${idx})" title="Hapus foto ini">&times;</button>
                            <span style="position: absolute; bottom: 3px; left: 3px; background: rgba(2,38,72,0.85); color: white; font-size: 0.65rem; padding: 1px 5px; border-radius: 3px; font-weight: 700;">#${idx+1}</span>
                        </div>
                    `;
                });
            }
            if (grid) grid.style.display = 'block';
        } else {
            if (label) label.textContent = 'Klik / Drag untuk menambah foto dokumentasi';
            if (grid) grid.style.display = 'none';
        }
    }

    function openModalById(id) {
        var modal = document.getElementById(id);
        if (modal) {
            modal.classList.add('active');
            modal.style.setProperty('display', 'flex', 'important');
            modal.style.setProperty('opacity', '1', 'important');
            modal.style.setProperty('visibility', 'visible', 'important');
            modal.style.setProperty('pointer-events', 'auto', 'important');
        }
    }

    function closeModalById(id) {
        var modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('active');
            modal.style.setProperty('display', 'none', 'important');
            modal.style.setProperty('opacity', '0', 'important');
            modal.style.setProperty('visibility', 'hidden', 'important');
            modal.style.setProperty('pointer-events', 'none', 'important');
        }
    }

    function openCreateModal() {
        openModalById('createModal');
        if (typeof $.fn.select2 !== 'undefined') {
            $('#createModal .select2-basic').select2({ width: '100%', dropdownParent: $('#createModal') });
        }
    }
    function closeCreateModal() { closeModalById('createModal'); }

    function openEditModal(item) {
        document.getElementById('editForm').action = "/admin/sekretariat/notulensi/" + item.id;
        document.getElementById('editAgendaId').value = item.agenda_id || '';
        document.getElementById('editJudulRapat').value = item.judul_rapat;
        document.getElementById('editTanggalRapat').value = item.tanggal_rapat ? item.tanggal_rapat.substring(0, 16) : '';
        document.getElementById('editPemimpinRapat').value = item.pemimpin_rapat || '';
        document.getElementById('editRingkasanHasil').value = item.ringkasan_hasil || '';
        document.getElementById('editLinkDrive').value = item.link_drive || '';
        openModalById('editModal');
        if (typeof $.fn.select2 !== 'undefined') {
            $('#editModal .select2-basic').select2({ width: '100%', dropdownParent: $('#editModal') });
        }
    }
    function closeEditModal() { closeModalById('editModal'); }

    function confirmDeleteNotulensi(id, judul) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Hapus Notulensi?',
                text: `Apakah Anda yakin ingin menghapus notulensi "${judul}"?`,
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
        } else if (confirm(`Apakah Anda yakin ingin menghapus notulensi "${judul}"?`)) {
            document.getElementById('delete-form-' + id).submit();
        }
    }

    // Galeri Foto Dokumentasi Logic
    window.currentGalleryImages = [];
    window.currentGalleryIndex = 0;

    window.openFotoGallery = function(images, title) {
        if (!images || images.length === 0) return;
        window.currentGalleryImages = images;
        window.currentGalleryIndex = 0;
        document.getElementById('galleryModalTitle').innerText = `Dokumentasi Foto: ${title || ''}`;
        renderGalleryIndex();
        document.getElementById('modalGalleryFoto').classList.add('active');
    };

    window.closeFotoGallery = function() {
        document.getElementById('modalGalleryFoto').classList.remove('active');
    };

    function renderGalleryIndex() {
        const total = window.currentGalleryImages.length;
        const idx = window.currentGalleryIndex;
        const currentUrl = window.currentGalleryImages[idx];

        document.getElementById('galleryCounterLabel').innerText = `Foto ${idx + 1} dari ${total}`;
        document.getElementById('galleryMainImage').src = currentUrl;
        document.getElementById('btnDownloadGalleryImage').href = currentUrl;

        const thumbsContainer = document.getElementById('galleryThumbnailsContainer');
        thumbsContainer.innerHTML = '';

        if (total > 1) {
            window.currentGalleryImages.forEach((url, i) => {
                const border = (i === idx) ? '2px solid #b7830f' : '2px solid transparent';
                const opacity = (i === idx) ? '1' : '0.4';
                thumbsContainer.innerHTML += `
                    <img src="${url}" onclick="setGalleryIndex(${i})" style="width: 56px; height: 42px; object-fit: cover; border-radius: 4px; cursor: pointer; border: ${border}; opacity: ${opacity}; transition: all 0.2s;" onmouseover="this.style.opacity='1'" onmouseout="if(${i}!==${idx}) this.style.opacity='0.4'">
                `;
            });
        }
    }

    window.setGalleryIndex = function(i) {
        window.currentGalleryIndex = i;
        renderGalleryIndex();
    };

    window.prevGalleryImage = function() {
        if (window.currentGalleryImages.length === 0) return;
        window.currentGalleryIndex = (window.currentGalleryIndex - 1 + window.currentGalleryImages.length) % window.currentGalleryImages.length;
        renderGalleryIndex();
    };

    window.nextGalleryImage = function() {
        if (window.currentGalleryImages.length === 0) return;
        window.currentGalleryIndex = (window.currentGalleryIndex + 1) % window.currentGalleryImages.length;
        renderGalleryIndex();
    };

    window.previewNotulensiPdf = function(url, title) {
        if (!url) return;
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: `<div style="color:#022648;font-weight:800;font-size:1.05rem;text-align:left;padding-bottom:4px;border-bottom:2px solid #022648;">Risalah Notulensi PDF: ${title || ''}</div>`,
                html: `
                    <div style="margin-bottom:0.6rem;display:flex;justify-content:flex-end;">
                        <a href="${url}" target="_blank" style="font-size:0.75rem;padding:4px 12px;background:#022648;color:white;border-radius:4px;text-decoration:none;font-weight:700;display:inline-flex;align-items:center;gap:5px;">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            Unduh / Buka Tab Baru
                        </a>
                    </div>
                    <iframe src="${url}" style="width:100%;height:72vh;border:none;border-radius:8px;"></iframe>`,
                width: '920px',
                showCloseButton: true,
                confirmButtonText: 'Tutup Pratinjau',
                confirmButtonColor: '#022648',
                customClass: { container: 'swal-high-zindex' }
            });
        }
    };

    // Bulk Action Script for Notulensi
    document.addEventListener('DOMContentLoaded', function () {
        const checkAllNotulensi = document.getElementById('check-all-notulensi');
        const checkNotulensiItems = document.querySelectorAll('.check-notulensi-item');
        const bulkBarNotulensi = document.getElementById('bulk-action-bar-notulensi');
        const countNotulensiDisplay = document.getElementById('selected-notulensi-count');

        function updateNotulensiBulkBar() {
            const checked = document.querySelectorAll('.check-notulensi-item:checked');
            if (countNotulensiDisplay) countNotulensiDisplay.innerText = checked.length;
            if (bulkBarNotulensi) {
                bulkBarNotulensi.style.display = checked.length > 0 ? 'flex' : 'none';
            }
        }

        if (checkAllNotulensi) {
            checkAllNotulensi.addEventListener('change', function () {
                checkNotulensiItems.forEach(item => item.checked = this.checked);
                updateNotulensiBulkBar();
            });
        }

        checkNotulensiItems.forEach(item => {
            item.addEventListener('change', function () {
                if (checkAllNotulensi) {
                    checkAllNotulensi.checked = Array.from(checkNotulensiItems).every(i => i.checked);
                }
                updateNotulensiBulkBar();
            });
        });
    });

    function executeBulkDeleteNotulensi() {
        const checked = document.querySelectorAll('.check-notulensi-item:checked');
        if (checked.length === 0) return;

        Swal.fire({
            title: 'Konfirmasi Hapus Massal Notulensi',
            text: `Apakah Anda yakin ingin menghapus ${checked.length} Notulensi Rapat yang dipilih? Berkas dokumen juga akan terhapus secara permanen.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus Semua!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('bulk-delete-notulensi-form');
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

    function executeBulkDownloadNotulensi() {
        const checked = document.querySelectorAll('.check-notulensi-item:checked');
        if (checked.length === 0) return;

        if (typeof Toast !== 'undefined') {
            Toast.fire({ icon: 'info', title: 'Memproses kompresi berkas ZIP Notulensi...' });
        }

        const form = document.getElementById('bulk-download-notulensi-form');
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

    window.openImportNotulensiModal = function() {
        openModalById('modalImportNotulensi');
    };
    window.closeImportNotulensiModal = function() {
        closeModalById('modalImportNotulensi');
    };

    // =====================================
    // Excel Import Drag & Drop & JS Logic
    // =====================================
    window.currentParsedNotulensiRows = [];
    window.existingNotulensiJuduls = @json($existingNotulensiJuduls ?? []);

    function renderNotulensiImportPreview() {
        const rows = window.currentParsedNotulensiRows || [];
        if (rows.length === 0) {
            document.getElementById('importNotulensiPreviewContainer').style.display = 'none';
            document.getElementById('btnSubmitImportNotulensi').disabled = true;
            return;
        }

        const judulCounts = {};
        rows.forEach(r => {
            const key = (r.judul_rapat || '').toLowerCase();
            judulCounts[key] = (judulCounts[key] || 0) + 1;
        });

        let duplicateCount = 0;
        rows.forEach(r => {
            const key = (r.judul_rapat || '').toLowerCase();
            const isInternalDup = judulCounts[key] > 1;
            const isDbDup = (window.existingNotulensiJuduls || []).some(n => (n || '').toLowerCase() === key);
            r.is_duplicate = isInternalDup || isDbDup;
            r.dup_reason = isDbDup ? 'Sudah Ada di Database' : (isInternalDup ? 'Duplikat di Berkas' : '');
            if (r.is_duplicate) duplicateCount++;
        });

        const alertBox = document.getElementById('importNotulensiDuplicateAlert');
        const dupCountSpan = document.getElementById('importNotulensiDupCount');
        if (alertBox && dupCountSpan) {
            if (duplicateCount > 0) {
                dupCountSpan.innerText = duplicateCount;
                alertBox.style.display = 'flex';
            } else {
                alertBox.style.display = 'none';
            }
        }

        document.getElementById('importNotulensiRowsInput').value = JSON.stringify(rows);
        document.getElementById('importNotulensiCount').innerText = rows.length + (rows.length > 15 ? ' data — menampilkan 15 pertama' : ' data');

        const tbody = document.getElementById('importNotulensiPreviewTableBody');
        tbody.innerHTML = '';

        rows.slice(0, 15).forEach((r, idx) => {
            const bgStyle = r.is_duplicate ? 'background: #fef2f2;' : '';
            const dupBadge = r.is_duplicate ? `<span style="display: inline-block; background: #fee2e2; color: #991b1b; padding: 3px 8px; border-radius: 4px; font-size: 0.7rem; font-weight: 700; margin-top: 4px;">${r.dup_reason}</span>` : '';

            tbody.innerHTML += `<tr style="${bgStyle}">
                <td style="padding: 10px 12px; border-bottom: 1px solid #e2e8f0; vertical-align: top;">
                    <div style="font-weight: 700; color: #022648; font-size: 0.8125rem;">${r.judul_rapat}</div>
                    ${dupBadge}
                </td>
                <td style="padding: 10px 12px; border-bottom: 1px solid #e2e8f0; text-align: center; vertical-align: top; font-size: 0.8125rem; color: #475569;">${r.tanggal_rapat}</td>
                <td style="padding: 10px 12px; border-bottom: 1px solid #e2e8f0; vertical-align: top; font-size: 0.8125rem; color: #334155;">${r.pemimpin_rapat || '-'}</td>
                <td style="padding: 10px 12px; border-bottom: 1px solid #e2e8f0; vertical-align: top; font-size: 0.8125rem; color: #334155; line-height: 1.4;">${r.ringkasan_hasil || '-'}</td>
                <td style="padding: 10px 12px; border-bottom: 1px solid #e2e8f0; text-align: center; vertical-align: top;">
                    <button type="button" onclick="removeImportNotulensiRow(${idx})" title="Hapus baris ini" style="background: #fee2e2; color: #991b1b; border: none; width: 26px; height: 26px; border-radius: 50%; font-weight: bold; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; font-size: 0.875rem; transition: background 0.2s;" onmouseover="this.style.background='#fca5a5'" onmouseout="this.style.background='#fee2e2'">&times;</button>
                </td>
            </tr>`;
        });

        document.getElementById('importNotulensiPreviewContainer').style.display = 'block';
        document.getElementById('btnSubmitImportNotulensi').disabled = false;
    }

    window.cleanImportNotulensiDuplicates = function() {
        if (!window.currentParsedNotulensiRows) return;
        const seen = new Set();
        window.currentParsedNotulensiRows = window.currentParsedNotulensiRows.filter(r => {
            const key = (r.judul_rapat || '').toLowerCase();
            const isDbDup = (window.existingNotulensiJuduls || []).some(n => (n || '').toLowerCase() === key);
            if (isDbDup || seen.has(key)) {
                return false;
            }
            seen.add(key);
            return true;
        });

        if (typeof Toast !== 'undefined') {
            Toast.fire({ icon: 'success', title: 'Data duplikat berhasil dibersihkan!' });
        }
        renderNotulensiImportPreview();
    };

    window.removeImportNotulensiRow = function(index) {
        if (window.currentParsedNotulensiRows && window.currentParsedNotulensiRows[index] !== undefined) {
            window.currentParsedNotulensiRows.splice(index, 1);
            renderNotulensiImportPreview();
        }
    };

    function handleImportNotulensiFile(input) {
        const file = input.files[0];
        if (!file) return;

        const label = document.getElementById('importFileLabelNotulensi');
        if (label) label.innerText = '✓ ' + file.name;

        function processRows(rows) {
            const validRows = rows.filter(r => r.judul_rapat && r.judul_rapat !== '');
            window.currentParsedNotulensiRows = validRows;
            renderNotulensiImportPreview();
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
                        if (!rowArray || !Array.isArray(rowArray) || rowArray.length < 1) return;
                        
                        const rowStr = rowArray.map(c => String(c || '').trim()).join(' ').toLowerCase();
                        if (rowStr.includes('template import') || rowStr.includes('judul rapat')) return;

                        let judul = String(rowArray[0] || '').trim();
                        let tanggal = String(rowArray[1] || '').trim();
                        let pemimpin = String(rowArray[2] || '').trim();
                        let ringkasan = String(rowArray[3] || '').trim();
                        let drive = String(rowArray[4] || '').trim();

                        if (!judul || judul === '&nbsp;') return;

                        if (!tanggal || tanggal === '&nbsp;') {
                            tanggal = new Date().toISOString().slice(0, 16).replace('T', ' ');
                        }

                        rows.push({
                            judul_rapat: judul,
                            tanggal_rapat: tanggal,
                            pemimpin_rapat: pemimpin !== '&nbsp;' ? pemimpin : '',
                            ringkasan_hasil: ringkasan !== '&nbsp;' ? ringkasan : '',
                            link_drive: drive !== '&nbsp;' ? drive : ''
                        });
                    });

                    processRows(rows);
                    return;
                }
            } catch (err) {
                console.error('XLSX parsing error:', err);
            }

            // Text / CSV Fallback
            try {
                const text = new TextDecoder("utf-8").decode(e.target.result);
                const lines = text.split(/\r\n|\n/);
                lines.forEach((line, index) => {
                    if (index === 0 || !line.trim()) return;
                    const cols = line.split(',');
                    if (cols.length >= 1 && cols[0].trim() !== '') {
                        let judul = cols[0].trim().replace(/^"|"$/g, '');
                        if (judul.toLowerCase() === 'judul rapat' || judul.includes('TEMPLATE')) return;
                        rows.push({
                            judul_rapat: judul,
                            tanggal_rapat: cols[1] ? cols[1].trim().replace(/^"|"$/g, '') : '',
                            pemimpin_rapat: cols[2] ? cols[2].trim().replace(/^"|"$/g, '') : '',
                            ringkasan_hasil: cols[3] ? cols[3].trim().replace(/^"|"$/g, '') : '',
                            link_drive: cols[4] ? cols[4].trim().replace(/^"|"$/g, '') : ''
                        });
                    }
                });
                processRows(rows);
            } catch (csvErr) {
                console.error('CSV fallback parsing error:', csvErr);
            }
        };

        reader.readAsArrayBuffer(file);
    }

    // ===============================================
    // Bulk Multi-PDF Drag & Drop JS Engine for Notulensi
    // ===============================================
    window.bulkPdfSelectedFiles = [];
    window.bulkPdfCardStates = {};

    window.openBulkPdfNotulensiModal = function() {
        openModalById('modalBulkPdfNotulensi');
    };

    window.closeBulkPdfNotulensiModal = function() {
        closeModalById('modalBulkPdfNotulensi');
    };

    function saveBulkPdfCurrentInputs() {
        window.bulkPdfSelectedFiles.forEach((file, idx) => {
            const key = file.name + '_' + file.size;
            window.bulkPdfCardStates[key] = {
                judul: document.getElementById(`pdf_judul_${idx}`)?.value || '',
                tanggal: document.getElementById(`pdf_tanggal_${idx}`)?.value || '',
                pemimpin: document.getElementById(`pdf_pemimpin_${idx}`)?.value || '',
                ringkasan: document.getElementById(`pdf_ringkasan_${idx}`)?.value || '',
                drive: document.getElementById(`pdf_drive_${idx}`)?.value || '',
            };
        });
    }

    function addBulkPdfFiles(files) {
        saveBulkPdfCurrentInputs();
        Array.from(files).forEach(file => {
            const ext = file.name.split('.').pop().toLowerCase();
            if (['pdf', 'doc', 'docx'].includes(ext)) {
                const exists = window.bulkPdfSelectedFiles.some(f => f.name === file.name && f.size === file.size);
                if (!exists) {
                    window.bulkPdfSelectedFiles.push(file);
                }
            }
        });
        renderBulkPdfCards();
    }

    function renderBulkPdfCards() {
        const container = document.getElementById('bulkPdfCardsContainerNotulensi');
        const countSpan = document.getElementById('bulkPdfSelectedCountNotulensi');
        const submitBtn = document.getElementById('btnSubmitBulkPdfNotulensi');

        if (!container) return;

        if (countSpan) countSpan.innerText = window.bulkPdfSelectedFiles.length;
        if (submitBtn) submitBtn.disabled = window.bulkPdfSelectedFiles.length === 0;

        if (window.bulkPdfSelectedFiles.length === 0) {
            container.innerHTML = `
                <div style="text-align: center; padding: 2.5rem 1rem; color: #64748b;">
                    <svg viewBox="0 0 24 24" width="40" height="40" stroke="#cbd5e1" fill="none" stroke-width="1.5" style="margin-bottom: 0.5rem;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    <div style="font-weight: 700; font-size: 0.9rem; color: #022648;">Belum ada berkas PDF / Word dipilih</div>
                    <div style="font-size: 0.775rem;">Silakan klik atau tarik banyak berkas ke dalam area di atas.</div>
                </div>
            `;
            return;
        }

        let html = '';
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const defaultDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;

        window.bulkPdfSelectedFiles.forEach((file, idx) => {
            const key = file.name + '_' + file.size;
            const state = window.bulkPdfCardStates[key] || {};

            const cleanName = state.judul || file.name.replace(/\.[^/.]+$/, "").replace(/[_-]/g, ' ');
            const fileSizeMb = (file.size / (1024 * 1024)).toFixed(2);
            const dateVal = state.tanggal || defaultDateTime;
            const pemimpinVal = state.pemimpin || '';
            const ringkasanVal = state.ringkasan || '';
            const driveVal = state.drive || '';

            html += `
                <div class="bulk-pdf-card" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; padding: 1.25rem; margin-bottom: 1rem; box-shadow: 0 2px 4px rgba(0,0,0,0.04);">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.75rem; flex-wrap: wrap; gap: 8px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="background: #b7830f; color: white; width: 24px; height: 24px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem;">${idx + 1}</span>
                            <strong style="color: #022648; font-size: 0.9rem;">${file.name}</strong>
                            <span style="color: #64748b; font-size: 0.75rem;">(${fileSizeMb} MB)</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <button type="button" onclick="toggleBulkPdfNotulensiPreview(${idx})" style="background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; padding: 4px 10px; border-radius: 4px; font-weight: 700; font-size: 0.75rem; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                <span id="pdf_preview_btn_notulensi_${idx}">Pratinjau Slide PDF</span>
                            </button>
                            <button type="button" onclick="removeBulkPdfNotulensiCard(${idx})" style="background: #fee2e2; color: #991b1b; border: none; padding: 4px 10px; border-radius: 4px; font-weight: 700; font-size: 0.75rem; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                                &times; Hapus Berkas
                            </button>
                        </div>
                    </div>

                    <div id="pdf_preview_slide_notulensi_${idx}" style="display: none; margin-bottom: 1.25rem; border: 1px solid #cbd5e1; border-radius: 8px; overflow: hidden; background: #0f172a; box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);">
                        <div style="background: #022648; color: white; padding: 6px 12px; font-size: 0.75rem; font-weight: 700; display: flex; justify-content: space-between; align-items: center;">
                            <span>📄 Slide Pratinjau Dokumen: ${file.name}</span>
                            <span style="color: #fef08a;">Scroll untuk membaca isi dokumen PDF</span>
                        </div>
                        <iframe id="pdf_iframe_notulensi_${idx}" style="width: 100%; height: 360px; border: none;" src="about:blank"></iframe>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label style="font-size: 0.775rem; font-weight: 700; color: #022648;">Judul Rapat <span style="color: red;">*</span></label>
                            <input type="text" id="pdf_judul_${idx}" class="form-control" placeholder="Judul Rapat..." value="${cleanName}" required style="font-size: 0.8125rem;">
                        </div>

                        <div class="form-group">
                            <label style="font-size: 0.775rem; font-weight: 700; color: #022648;">Tanggal & Waktu Rapat <span style="color: red;">*</span></label>
                            <input type="datetime-local" id="pdf_tanggal_${idx}" class="form-control" value="${dateVal}" required style="font-size: 0.8125rem;">
                        </div>

                        <div class="form-group">
                            <label style="font-size: 0.775rem; font-weight: 700; color: #022648;">Pemimpin Rapat</label>
                            <input type="text" id="pdf_pemimpin_${idx}" class="form-control" placeholder="Nama pemimpin rapat..." value="${pemimpinVal}" style="font-size: 0.8125rem;">
                        </div>

                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label style="font-size: 0.775rem; font-weight: 700; color: #022648;">Ringkasan Hasil Notulensi</label>
                            <textarea id="pdf_ringkasan_${idx}" class="form-control" placeholder="Ringkasan poin hasil rapat..." style="height: 65px; font-size: 0.8125rem;">${ringkasanVal}</textarea>
                        </div>

                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label style="font-size: 0.775rem; font-weight: 700; color: #022648;">Link Google Drive (Opsional)</label>
                            <input type="url" id="pdf_drive_${idx}" class="form-control" placeholder="https://drive.google.com/..." value="${driveVal}" style="font-size: 0.8125rem;">
                        </div>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;
    }

    window.toggleBulkPdfNotulensiPreview = function(idx) {
        const slide = document.getElementById(`pdf_preview_slide_notulensi_${idx}`);
        const iframe = document.getElementById(`pdf_iframe_notulensi_${idx}`);
        const btnText = document.getElementById(`pdf_preview_btn_notulensi_${idx}`);

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

    window.removeBulkPdfNotulensiCard = function(idx) {
        saveBulkPdfCurrentInputs();
        if (window.bulkPdfSelectedFiles && window.bulkPdfSelectedFiles[idx]) {
            window.bulkPdfSelectedFiles.splice(idx, 1);
            renderBulkPdfCards();
        }
    };

    document.addEventListener('DOMContentLoaded', function () {
        const dropZone = document.getElementById('bulkPdfNotulensiDropZone');
        const inputFiles = document.getElementById('bulkPdfNotulensiInputFiles');
        const formBulk = document.getElementById('formBulkPdfNotulensi');

        if (dropZone) {
            ['dragenter', 'dragover'].forEach(evtName => {
                dropZone.addEventListener(evtName, (e) => {
                    e.preventDefault(); e.stopPropagation();
                    dropZone.style.background = '#fff9e6';
                }, false);
            });

            ['dragleave', 'drop'].forEach(evtName => {
                dropZone.addEventListener(evtName, (e) => {
                    e.preventDefault(); e.stopPropagation();
                    dropZone.style.background = '#fffdf5';
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

        if (formBulk) {
            formBulk.addEventListener('submit', function (e) {
                e.preventDefault();

                if (!window.bulkPdfSelectedFiles || window.bulkPdfSelectedFiles.length === 0) {
                    if (typeof Toast !== 'undefined') Toast.fire({ icon: 'warning', title: 'Belum ada berkas PDF yang dipilih!' });
                    return;
                }

                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('bulk_type', 'files');

                let hasError = false;

                window.bulkPdfSelectedFiles.forEach((file, idx) => {
                    const judul = document.getElementById(`pdf_judul_${idx}`)?.value || '';
                    const tanggal = document.getElementById(`pdf_tanggal_${idx}`)?.value || '';
                    const pemimpin = document.getElementById(`pdf_pemimpin_${idx}`)?.value || '';
                    const ringkasan = document.getElementById(`pdf_ringkasan_${idx}`)?.value || '';
                    const drive = document.getElementById(`pdf_drive_${idx}`)?.value || '';

                    if (!judul) {
                        hasError = true;
                    }

                    // Append inputs dynamically for standard form submission
                    const inputJudul = document.createElement('input'); inputJudul.type = 'hidden'; inputJudul.name = 'judul_rapat[]'; inputJudul.value = judul; formBulk.appendChild(inputJudul);
                    const inputTanggal = document.createElement('input'); inputTanggal.type = 'hidden'; inputTanggal.name = 'tanggal_rapat[]'; inputTanggal.value = tanggal; formBulk.appendChild(inputTanggal);
                    const inputPemimpin = document.createElement('input'); inputPemimpin.type = 'hidden'; inputPemimpin.name = 'pemimpin_rapat[]'; inputPemimpin.value = pemimpin; formBulk.appendChild(inputPemimpin);
                    const inputRingkasan = document.createElement('input'); inputRingkasan.type = 'hidden'; inputRingkasan.name = 'ringkasan_hasil[]'; inputRingkasan.value = ringkasan; formBulk.appendChild(inputRingkasan);
                    const inputDrive = document.createElement('input'); inputDrive.type = 'hidden'; inputDrive.name = 'link_drive[]'; inputDrive.value = drive; formBulk.appendChild(inputDrive);
                });

                if (hasError) {
                    e.preventDefault();
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'warning', title: 'Data Belum Lengkap', text: 'Mohon isi Judul Rapat pada seluruh kartu berkas.' });
                    }
                    return;
                }

                // Add selected files via DataTransfer to form submission
                const dt = new DataTransfer();
                window.bulkPdfSelectedFiles.forEach(file => dt.items.add(file));
                const inputFiles = document.createElement('input');
                inputFiles.type = 'file';
                inputFiles.name = 'files[]';
                inputFiles.multiple = true;
                inputFiles.files = dt.files;
                inputFiles.style.display = 'none';
                formBulk.appendChild(inputFiles);

                if (typeof Toast !== 'undefined') {
                    Toast.fire({
                        icon: 'success',
                        title: `Mengunggah ${window.bulkPdfSelectedFiles.length} berkas Notulensi Rapat...`
                    });
                }
            });
        }
    });
</script>
@endpush