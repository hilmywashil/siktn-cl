@extends('admin.layouts.admin-layout')

@section('title', 'Kelola E-Katalog')
@section('page-title', 'E-Katalog')

@php
    $activeMenu = 'katalog';
@endphp

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    .ekatalog-scope, .ekatalog-scope * {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        box-sizing: border-box;
    }

    .ekatalog-scope {
        --navy: #022648;
        --navy-dark: #01162f;
        --navy-light: #0a3a6b;
        --gold: #b7830f;
        --green: #059669;
        --blue: #2563eb;
        --red: #dc2626;
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
    }

    /* Select2 Smooth Animations */
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
        height: 40px; padding: 0.35rem 0.75rem; font-size: 0.8125rem;
        font-weight: 600; color: var(--navy); background-color: #fff; border: 1px solid var(--gray-300);
        border-radius: var(--radius-md); display: flex; align-items: center; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); min-width: 140px;
        box-shadow: 0 1px 2px rgba(2, 38, 72, 0.04);
    }
    .select2-container--default .select2-selection--single:hover {
        border-color: var(--navy);
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(2, 38, 72, 0.1);
    }
    .select2-container--default .select2-selection--single:focus,
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: var(--navy); outline: none; box-shadow: 0 0 0 3px rgba(2, 38, 72, 0.12);
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered { color: var(--navy); padding-left: 0; line-height: normal; font-weight: 600; }
    .select2-container--default .select2-selection--single .select2-selection__placeholder { color: var(--gray-500); font-weight: 500; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: 38px; right: 8px; }

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

    /* Stat cards */
    .katalog-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        display: flex;
        align-items: center;
        gap: 14px;
        background: #fff;
        border: 1px solid var(--gray-200);
        border-left: 4px solid;
        border-radius: var(--radius-lg);
        padding: 1.1rem 1.25rem;
        box-shadow: 0 1px 2px rgba(2, 38, 72, 0.04);
    }

    .stat-card.total { border-left-color: var(--navy); }
    .stat-card.pending { border-left-color: var(--gold); }
    .stat-card.revision { border-left-color: var(--blue); }
    .stat-card.approved { border-left-color: var(--green); }
    .stat-card.rejected { border-left-color: var(--red); }

    .stat-icon {
        width: 42px;
        height: 42px;
        min-width: 42px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
    }
    .stat-card.total .stat-icon { background: var(--navy); }
    .stat-card.pending .stat-icon { background: var(--gold); }
    .stat-card.revision .stat-icon { background: var(--blue); }
    .stat-card.approved .stat-icon { background: var(--green); }
    .stat-card.rejected .stat-icon { background: var(--red); }

    .stat-label {
        font-size: 0.72rem;
        color: var(--gray-500);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--gray-900);
        margin-top: 0.15rem;
        line-height: 1.1;
    }

    /* Table container */
    .katalog-table-container {
        background: #fff;
        border-radius: var(--radius-lg);
        border: 1px solid var(--gray-200);
        box-shadow: 0 1px 2px rgba(2, 38, 72, 0.04);
        overflow: hidden;
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--gray-200);
    }

    .table-header h3 {
        font-size: 1.0625rem;
        font-weight: 700;
        color: var(--navy);
        margin: 0;
    }

    .filter-group {
        display: flex;
        gap: 0.6rem;
        flex-wrap: wrap;
        align-items: center;
    }

    .filter-select, .search-input {
        height: 40px;
        padding: 0 0.85rem;
        border: 1px solid var(--gray-300);
        border-radius: var(--radius-md);
        font-size: 0.8125rem;
        font-weight: 500;
        background: #fff;
        color: var(--gray-900);
    }

    .filter-select:focus, .search-input:focus {
        outline: none;
        border-color: var(--navy);
        box-shadow: 0 0 0 3px rgba(2, 38, 72, 0.12);
    }

    .search-input { min-width: 220px; }

    /* Buttons (Kategori / Tambah / Search submit) */
    .btn-add {
        height: 40px;
        background: var(--navy);
        color: #fff;
        padding: 0 1rem !important;
        font-size: 0.8125rem !important;
        font-weight: 700 !important;
        border-radius: var(--radius-md) !important;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        text-decoration: none !important;
        transition: background 0.15s ease;
    }
    .btn-add:hover { background: var(--navy-light); }

    /* Table */
    .table-responsive-wrapper {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .data-table {
        width: 100%;
        min-width: 980px;
        border-collapse: collapse;
    }

    .data-table th {
        background: var(--gray-50);
        padding: 0.8rem 1rem;
        text-align: left;
        font-weight: 700;
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--gray-700);
        border-bottom: 1px solid var(--gray-200);
        white-space: nowrap;
    }

    .data-table td {
        padding: 0.85rem 1rem;
        border-bottom: 1px solid var(--gray-200);
        font-size: 0.8125rem;
        color: var(--gray-700);
        vertical-align: middle;
    }

    .data-table tr:last-child td { border-bottom: none; }
    .data-table tr:hover { background: var(--gray-50); }

    .data-table th:last-child,
    .data-table td.cell-aksi {
        min-width: 76px;
        text-align: right;
    }

    /* Logo */
    .katalog-logo-wrap {
        width: 52px;
        height: 52px;
        border-radius: var(--radius-sm);
        border: 1px solid var(--gray-200);
        background: var(--gray-50);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        padding: 5px;
    }
    .katalog-logo { max-width: 100%; max-height: 100%; object-fit: contain; }

    .company-info { display: flex; flex-direction: column; gap: 0.2rem; }
    .company-name { font-weight: 700; color: var(--gray-900); display: inline-flex; align-items: center; gap: 6px; }
    .company-field { font-size: 0.75rem; color: var(--gray-500); }

    .wilayah-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 0.3rem 0.65rem;
        background: var(--navy);
        color: #fff;
        font-weight: 600;
        border-radius: var(--radius-sm);
        font-size: 0.72rem;
        white-space: nowrap;
    }

    .status-badge {
        padding: 0.35rem 0.7rem;
        border-radius: var(--radius-sm);
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: #fff;
    }
    .status-pending { background: var(--gold); }
    .status-approved { background: var(--green); }
    .status-rejected { background: var(--red); }
    .status-revision { background: var(--blue); }

    .pending-badge {
        background: var(--gold);
        color: #fff;
        padding: 0.15rem 0.45rem;
        border-radius: var(--radius-sm);
        font-size: 0.6rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    /* Aksi dropdown */
    .aksi-wrapper { position: relative; display: inline-block; }

    .btn-aksi-trigger {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--navy);
        color: #fff;
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
    .btn-aksi-trigger:active {
        transform: scale(0.94);
    }

    .aksi-dropdown {
        display: block;
        position: fixed;
        min-width: 190px;
        background: #fff;
        border: 1px solid var(--gray-200);
        border-radius: var(--radius-md);
        box-shadow: 0 14px 32px rgba(2, 38, 72, 0.18);
        padding: 6px;
        z-index: 1000;
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
    .aksi-item svg {
        flex-shrink: 0;
        transition: transform 0.18s ease;
    }
    .aksi-item:hover svg {
        transform: scale(1.15);
    }

    .aksi-view svg { color: var(--navy); }
    .aksi-approve svg { color: var(--green); }
    .aksi-revision svg { color: var(--blue); }
    .aksi-reject svg { color: var(--gold); }
    .aksi-edit svg { color: var(--gray-500); }
    .aksi-delete, .aksi-delete svg { color: var(--red); }

    .aksi-divider { height: 1px; background: var(--gray-200); margin: 5px 2px; }

    .alert {
        padding: 0.9rem 1.1rem;
        border-radius: var(--radius-md);
        margin-bottom: 1.25rem;
        font-size: 0.8125rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .alert-success { background: #d1fae5; color: #065f46; border-left: 4px solid var(--green); }
    .alert svg { width: 20px; height: 20px; }

    .empty-state { text-align: center; padding: 3rem; color: var(--gray-500); }
    .empty-state svg { width: 72px; height: 72px; margin: 0 auto 1rem; stroke: var(--gray-300); }
    .empty-state h3 { color: var(--gray-900); font-size: 1rem; margin-bottom: 0.35rem; }

    .pagination { display: flex; justify-content: center; padding: 1.1rem 1.5rem; }

    .pag-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 34px;
        height: 34px;
        padding: 0 10px;
        border-radius: var(--radius-md);
        border: 1px solid var(--gray-200);
        background: #fff;
        color: var(--gray-700);
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s;
        text-decoration: none !important;
    }
    .pag-btn:hover { background: var(--gray-50); border-color: var(--gray-300); }
    .pag-active { background: var(--navy) !important; color: #fff !important; border-color: var(--navy) !important; pointer-events: none; }
    .pag-disabled { opacity: 0.35; pointer-events: none; cursor: default; }

    @media (max-width: 768px) {
        .table-header { flex-direction: column; align-items: stretch; }
        .search-input { min-width: 100%; }
    }
</style>
@endpush

@section('content')
<div class="ekatalog-scope">

<!-- Statistik -->
<div class="katalog-stats">
    <div class="stat-card total">
        <div class="stat-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect>
                <rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect>
            </svg>
        </div>
        <div>
            <div class="stat-label">Total Katalog</div>
            <div class="stat-value">{{ $stats['total'] }}</div>
        </div>
    </div>
    <div class="stat-card pending">
        <div class="stat-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="9"></circle><polyline points="12 7 12 12 15.5 14"></polyline>
            </svg>
        </div>
        <div>
            <div class="stat-label">Menunggu</div>
            <div class="stat-value">{{ $stats['pending'] }}</div>
        </div>
    </div>
    <div class="stat-card revision">
        <div class="stat-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
            </svg>
        </div>
        <div>
            <div class="stat-label">Revisi</div>
            <div class="stat-value">{{ $stats['revision'] }}</div>
        </div>
    </div>
    <div class="stat-card approved">
        <div class="stat-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="9"></circle><polyline points="8 12.5 11 15.5 16 9"></polyline>
            </svg>
        </div>
        <div>
            <div class="stat-label">Disetujui</div>
            <div class="stat-value">{{ $stats['approved'] }}</div>
        </div>
    </div>
    <div class="stat-card rejected">
        <div class="stat-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="9"></circle><line x1="9" y1="9" x2="15" y2="15"></line><line x1="15" y1="9" x2="9" y2="15"></line>
            </svg>
        </div>
        <div>
            <div class="stat-label">Ditolak</div>
            <div class="stat-value">{{ $stats['rejected'] }}</div>
        </div>
    </div>
</div>

<div class="katalog-table-container">

    <div class="table-header">
        <h3>Daftar E-Katalog Perusahaan</h3>

        <div class="filter-group">
            @if(auth()->guard('admin')->user()->canManageContent())
            <button type="button" class="btn-add" onclick="openKategoriModal()" style="background: var(--gray-500);">
                <svg viewBox="0 0 24 24" width="15" height="15" stroke="currentColor" fill="none" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect>
                    <rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect>
                </svg>
                Kategori
            </button>
            <a href="{{ route('admin.katalog.create') }}" class="btn-add">
                <svg viewBox="0 0 24 24" width="15" height="15" stroke="currentColor" fill="none" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Tambah Katalog
            </a>
            @endif

            <form method="GET" action="{{ route('admin.katalog.index') }}" style="display: contents;" id="filterForm">
                <select name="status" class="filter-select">
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="revision" {{ request('status') == 'revision' ? 'selected' : '' }}>Revisi</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>

                <select name="kategori" class="filter-select">
                    <option value="all">Semua Kategori</option>
                    @foreach($kategoris as $kat)
                        <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>{{ $kat->nama }}</option>
                    @endforeach
                </select>

                <select name="wilayah" id="wilayahFilterSelect" class="filter-select" style="min-width: 150px;">
                    <option value="">Semua Wilayah</option>
                    @if(request('wilayah'))
                        <option value="{{ request('wilayah') }}" selected>{{ request('wilayah') }}</option>
                    @endif
                </select>

                <input type="text" name="search" class="search-input" placeholder="Cari..." value="{{ request('search') }}">
                <button type="submit" class="btn-add" style="padding: 0 0.85rem !important;">
                    <svg viewBox="0 0 24 24" width="15" height="15" stroke="currentColor" fill="none" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    @if($katalogs->count() > 0)
        <div class="table-responsive-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Logo</th>
                    <th>Perusahaan</th>
                    <th>Wilayah</th>
                    <th>Kontak</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($katalogs as $katalog)
                <tr>
                    <td>
                        <div class="katalog-logo-wrap">
                            <img src="{{ $katalog->logo_url }}" alt="{{ $katalog->company_name }}" class="katalog-logo">
                        </div>
                    </td>
                    <td>
                        <div class="company-info">
                            <span class="company-name">
                                {{ $katalog->company_name }}
                                @if($katalog->isPending())
                                    <span class="pending-badge">New</span>
                                @endif
                            </span>
                            <span class="company-field">{{ $katalog->business_field }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="wilayah-badge">
                            📍 {{ $katalog->wilayah ?? ($katalog->anggota ? $katalog->anggota->domisili : 'Nasional') }}
                        </span>
                    </td>
                    <td>
                        <div>{{ $katalog->phone }}</div>
                        <div style="font-size: 0.72rem; color: var(--gray-500);">{{ $katalog->email }}</div>
                    </td>
                    <td>
                        @if($katalog->status === 'pending')
                            <span class="status-badge status-pending">Pending</span>
                        @elseif($katalog->status === 'revision')
                            <span class="status-badge status-revision">Revisi</span>
                        @elseif($katalog->status === 'approved')
                            <span class="status-badge status-approved">Approved</span>
                        @else
                            <span class="status-badge status-rejected">Rejected</span>
                        @endif
                    </td>
                    <td>
                        <div style="font-size: 0.75rem;">
                            <div>{{ $katalog->created_at->format('d M Y') }}</div>
                            @if($katalog->approved_at)
                                <div style="color: var(--green); font-weight: 600;">✓ {{ $katalog->approved_at->format('d M Y') }}</div>
                            @endif
                        </div>
                    </td>
                    <td class="cell-aksi">
                        <div class="aksi-wrapper">
                            <button type="button" class="btn-aksi-trigger" data-target="dropdown-aksi-{{ $katalog->id }}" aria-label="Buka menu aksi">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                    <circle cx="12" cy="5" r="1.75"></circle><circle cx="12" cy="12" r="1.75"></circle><circle cx="12" cy="19" r="1.75"></circle>
                                </svg>
                            </button>

                            <div class="aksi-dropdown" id="dropdown-aksi-{{ $katalog->id }}">
                                <a href="{{ route('admin.katalog.show', $katalog) }}" class="aksi-item aksi-view">
                                    <svg viewBox="0 0 24 24" width="15" height="15" stroke="currentColor" fill="none" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    Lihat Detail
                                </a>

                                @if(auth()->guard('admin')->user()->canManageContent())
                                    @if($katalog->isPending())
                                        <button type="button" class="aksi-item aksi-approve btn-confirm-approve" data-form="approve-form-{{ $katalog->id }}">
                                            <svg viewBox="0 0 24 24" width="15" height="15" stroke="currentColor" fill="none" stroke-width="2">
                                                <polyline points="20 6 9 17 4 12"></polyline>
                                            </svg>
                                            Setujui
                                        </button>

                                        <button type="button" class="aksi-item aksi-revision" onclick="openRevisionModal({{ $katalog->id }}, '{{ addslashes($katalog->company_name) }}')">
                                            <svg viewBox="0 0 24 24" width="15" height="15" stroke="currentColor" fill="none" stroke-width="2">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                            Kirim Revisi
                                        </button>

                                        <button type="button" class="aksi-item aksi-reject" onclick="openRejectModal({{ $katalog->id }}, '{{ addslashes($katalog->company_name) }}')">
                                            <svg viewBox="0 0 24 24" width="15" height="15" stroke="currentColor" fill="none" stroke-width="2">
                                                <line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>
                                            </svg>
                                            Tolak
                                        </button>
                                    @endif

                                    <a href="{{ route('admin.katalog.edit', $katalog) }}" class="aksi-item aksi-edit">
                                        <svg viewBox="0 0 24 24" width="15" height="15" stroke="currentColor" fill="none" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                        Edit
                                    </a>

                                    <div class="aksi-divider"></div>

                                    <button type="button" class="aksi-item aksi-delete btn-confirm-delete" data-form="delete-form-{{ $katalog->id }}">
                                        <svg viewBox="0 0 24 24" width="15" height="15" stroke="currentColor" fill="none" stroke-width="2">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        </svg>
                                        Hapus
                                    </button>
                                @endif
                            </div>

                            @if(auth()->guard('admin')->user()->canManageContent())
                                @if($katalog->isPending())
                                    <form id="approve-form-{{ $katalog->id }}" action="{{ route('admin.katalog.approve', $katalog) }}" method="POST" style="display:none;">
                                        @csrf
                                    </form>
                                @endif
                                <form id="delete-form-{{ $katalog->id }}" action="{{ route('admin.katalog.destroy', $katalog) }}" method="POST" style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>

        <div class="pagination">
            {{-- Custom Pagination --}}
@if($katalogs->hasPages())
<div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; width: 100%;">

    <span style="font-size: 0.78rem; color: var(--gray-500); font-weight: 500;">
        Menampilkan {{ $katalogs->firstItem() }}–{{ $katalogs->lastItem() }} dari {{ $katalogs->total() }} data
    </span>

    <div style="display: flex; gap: 6px; align-items: center; flex-wrap: wrap;">

        @if($katalogs->onFirstPage())
            <span class="pag-btn pag-disabled">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
            </span>
        @else
            <a href="{{ $katalogs->previousPageUrl() }}&{{ http_build_query(request()->except('page')) }}" class="pag-btn">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
            </a>
        @endif

        @php
            $currentPage = $katalogs->currentPage();
            $lastPage    = $katalogs->lastPage();
            $pages = [];

            if ($lastPage <= 7) {
                $pages = range(1, $lastPage);
            } else {
                $pages[] = 1;
                if ($currentPage > 3) $pages[] = '...';
                $start = max(2, $currentPage - 1);
                $end   = min($lastPage - 1, $currentPage + 1);
                foreach (range($start, $end) as $p) $pages[] = $p;
                if ($currentPage < $lastPage - 2) $pages[] = '...';
                $pages[] = $lastPage;
            }
        @endphp

        @foreach($pages as $page)
            @if($page === '...')
                <span style="font-size: 0.8rem; color: var(--gray-300); padding: 0 4px; line-height: 34px;">···</span>
            @elseif($page === $currentPage)
                <span class="pag-btn pag-active">{{ $page }}</span>
            @else
                <a href="{{ $katalogs->url($page) }}&{{ http_build_query(request()->except('page')) }}" class="pag-btn">{{ $page }}</a>
            @endif
        @endforeach

        @if($katalogs->hasMorePages())
            <a href="{{ $katalogs->nextPageUrl() }}&{{ http_build_query(request()->except('page')) }}" class="pag-btn">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
        @else
            <span class="pag-btn pag-disabled">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            </span>
        @endif

    </div>
</div>
@endif
        </div>
    @else
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                <line x1="9" y1="9" x2="15" y2="9"/>
                <line x1="9" y1="15" x2="15" y2="15"/>
            </svg>
            <h3>Belum ada data katalog</h3>
            <p>Data katalog dari anggota akan muncul di sini</p>
        </div>
    @endif
</div>

<!-- Reject Modal -->
<div id="rejectModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 50; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 8px; padding: 2rem; max-width: 500px; width: 90%;">
        <h3 style="font-size: 1.125rem; font-weight: 700; margin-bottom: 1rem; color: var(--gray-900);">Tolak Katalog</h3>
        <p style="color: var(--gray-500); margin-bottom: 1.5rem; font-size: 0.875rem;">Berikan alasan penolakan untuk <strong id="rejectCompanyName" style="color: var(--gray-900);"></strong></p>

        <form id="rejectForm" method="POST">
            @csrf
            <textarea name="rejection_reason" rows="4" placeholder="Contoh: Logo kurang jelas..." style="width: 100%; padding: 0.75rem; border: 1px solid var(--gray-300); border-radius: 6px; font-family: inherit; font-size: 0.875rem;" required></textarea>

            <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
                <button type="submit" style="flex: 1; background: var(--red); color: white; padding: 0.75rem; border-radius: 6px; border: none; font-weight: 700; font-size: 0.875rem; cursor: pointer;">
                    Tolak
                </button>
                <button type="button" onclick="closeRejectModal()" style="flex: 1; background: var(--gray-100); color: var(--gray-700); padding: 0.75rem; border-radius: 6px; border: none; font-weight: 700; font-size: 0.875rem; cursor: pointer;">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Revision Modal -->
<div id="revisionModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 50; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 8px; padding: 2rem; max-width: 500px; width: 90%;">
        <h3 style="font-size: 1.125rem; font-weight: 700; margin-bottom: 1rem; color: var(--gray-900);">Kirim untuk Revisi</h3>
        <p style="color: var(--gray-500); margin-bottom: 1.5rem; font-size: 0.875rem;">Berikan catatan revisi untuk <strong id="revisionCompanyName" style="color: var(--gray-900);"></strong></p>

        <form id="revisionForm" method="POST">
            @csrf
            <textarea name="revision_notes" rows="4" placeholder="Contoh: Mohon perbaiki deskripsi produk..." style="width: 100%; padding: 0.75rem; border: 1px solid var(--gray-300); border-radius: 6px; font-family: inherit; font-size: 0.875rem;" required></textarea>

            <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
                <button type="submit" style="flex: 1; background: var(--blue); color: white; padding: 0.75rem; border-radius: 6px; border: none; font-weight: 700; font-size: 0.875rem; cursor: pointer;">
                    Kirim Revisi
                </button>
                <button type="button" onclick="closeRevisionModal()" style="flex: 1; background: var(--gray-100); color: var(--gray-700); padding: 0.75rem; border-radius: 6px; border: none; font-weight: 700; font-size: 0.875rem; cursor: pointer;">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Kategori Modal -->
<div id="kategoriModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 10px; max-width: 480px; width: 95%; max-height: 85vh; overflow: hidden; display: flex; flex-direction: column; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
        <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--gray-200); display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3 style="margin: 0; font-size: 1.0625rem; font-weight: 700; color: var(--gray-900);">Kelola Kategori Produk</h3>
                <p style="margin: 0.25rem 0 0; font-size: 0.8rem; color: var(--gray-500);">Tambah atau edit kategori katalog</p>
            </div>
            <button onclick="closeKategoriModal()" style="background: var(--gray-100); border: none; width: 32px; height: 32px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; color: var(--gray-500);">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <div style="padding: 1.5rem; overflow-y: auto; flex: 1;">
            <form action="{{ route('admin.katalog.kategori.store') }}" method="POST" style="margin-bottom: 1.5rem;">
                @csrf
                <div style="display: flex; gap: 0.75rem; align-items: flex-end;">
                    <div style="flex: 1;">
                        <label style="display: block; font-size: 0.8rem; font-weight: 700; color: var(--gray-700); margin-bottom: 0.375rem;">Nama Kategori</label>
                        <input type="text" name="nama" placeholder="Contoh: Fashion" required style="width: 100%; padding: 0.6rem 0.85rem; border: 1px solid var(--gray-300); border-radius: 6px; font-size: 0.875rem;">
                    </div>
                    <button type="submit" style="background: var(--navy); color: white; border: none; padding: 0.6rem 1.15rem; border-radius: 6px; font-size: 0.875rem; font-weight: 700; cursor: pointer; white-space: nowrap; display: flex; align-items: center; gap: 0.5rem;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        Tambah
                    </button>
                </div>
            </form>

            <div style="border: 1px solid var(--gray-200); border-radius: 8px; overflow: hidden;">
                @forelse(\App\Models\KategoriEatalog::orderBy('nama')->get() as $kat)
                <form action="{{ route('admin.katalog.kategori.update', $kat) }}" method="POST" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.85rem 1rem; border-bottom: 1px solid var(--gray-100);">
                    @csrf
                    @method('PUT')
                    <input type="text" name="nama" value="{{ $kat->nama }}" style="flex: 1; padding: 0.5rem 0.75rem; border: 1px solid var(--gray-200); border-radius: 6px; font-size: 0.8rem;">
                    <button type="submit" title="Update" style="background: var(--gray-100); color: var(--gray-700); border: 1px solid var(--gray-200); padding: 0.5rem 0.75rem; border-radius: 6px; font-size: 0.75rem; font-weight: 700; cursor: pointer;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </button>
                    <a href="{{ route('admin.katalog.kategori.destroy', $kat) }}" onclick="return confirm('Hapus?')" title="Hapus" style="background: #fee2e2; color: var(--red); padding: 0.5rem 0.75rem; border-radius: 6px; text-decoration: none; display:flex; align-items:center;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                    </a>
                </form>
                @empty
                <div style="padding: 2rem; text-align: center; color: var(--gray-300);">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 0.75rem;"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="9" x2="15" y2="9"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
                    <p style="margin: 0; font-size: 0.875rem; color: var(--gray-500);">Belum ada kategori</p>
                </div>
                @endforelse
            </div>
        </div>

        <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--gray-200); background: var(--gray-50); display: flex; justify-content: flex-end;">
            <button onclick="closeKategoriModal()" style="background: white; color: var(--gray-700); border: 1px solid var(--gray-300); padding: 0.6rem 1.15rem; border-radius: 6px; font-size: 0.875rem; font-weight: 700; cursor: pointer;">
                Tutup
            </button>
        </div>
    </div>
</div>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // SweetAlert for Delete
        document.querySelectorAll('.btn-confirm-delete').forEach(function (button) {
            button.addEventListener('click', function () {
                const form = document.getElementById(this.dataset.form);
                Swal.fire({
                    title: 'Hapus Katalog?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });

        // SweetAlert for Approve
        document.querySelectorAll('.btn-confirm-approve').forEach(function (button) {
            button.addEventListener('click', function () {
                const form = document.getElementById(this.dataset.form);
                Swal.fire({
                    title: 'Setujui Katalog?',
                    text: "Katalog akan disetujui dan ditampilkan ke publik.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#059669',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Setujui',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });

        // Aksi dropdown
        const triggers = document.querySelectorAll('.btn-aksi-trigger');

        function closeAllAksiDropdowns() {
            document.querySelectorAll('.aksi-dropdown.is-open').forEach(function (d) {
                d.classList.remove('is-open');
            });
        }

        function openAksiDropdown(trigger, dropdown) {
            dropdown.style.visibility = 'hidden';
            dropdown.classList.add('is-open');

            const triggerRect = trigger.getBoundingClientRect();
            const dropRect = dropdown.getBoundingClientRect();

            let left = triggerRect.right - dropRect.width;
            if (left < 8) left = 8;
            if (left + dropRect.width > window.innerWidth - 8) {
                left = window.innerWidth - dropRect.width - 8;
            }

            let top = triggerRect.bottom + 6;
            if (top + dropRect.height > window.innerHeight - 8) {
                top = triggerRect.top - dropRect.height - 6;
            }

            dropdown.style.top = top + 'px';
            dropdown.style.left = left + 'px';
            dropdown.style.visibility = 'visible';
        }

        triggers.forEach(function (trigger) {
            trigger.addEventListener('click', function (e) {
                e.stopPropagation();
                const dropdown = document.getElementById(trigger.dataset.target);
                const isOpen = dropdown.classList.contains('is-open');
                closeAllAksiDropdowns();
                if (!isOpen) openAksiDropdown(trigger, dropdown);
            });
        });

        document.addEventListener('click', closeAllAksiDropdowns);
        window.addEventListener('resize', closeAllAksiDropdowns);
        window.addEventListener('scroll', closeAllAksiDropdowns, true);
    });

    $(document).ready(function() {
        const currentWilayah = "{{ request('wilayah') }}";

        Promise.all([
            fetch("{{ asset('provinces.json') }}").then(res => res.json()),
            fetch("{{ asset('regencies.json') }}").then(res => res.json())
        ]).then(([provinces, regencies]) => {
            let optgroupProv = $('<optgroup label="Tingkat Provinsi"></optgroup>');
            provinces.forEach(prov => {
                if (prov !== currentWilayah) optgroupProv.append(`<option value="${prov}">${prov}</option>`);
            });

            let optgroupReg = $('<optgroup label="Tingkat Kabupaten/Kota"></optgroup>');
            regencies.forEach(reg => {
                if (reg !== currentWilayah) optgroupReg.append(`<option value="${reg}">${reg}</option>`);
            });

            $('#wilayahFilterSelect').append(optgroupProv).append(optgroupReg);

            $('.filter-select').select2({
                width: 'auto'
            }).on('change', function() {
                $('#filterForm').submit();
            });
        }).catch(err => {
            $('.filter-select').select2({
                width: 'auto'
            }).on('change', function() {
                $('#filterForm').submit();
            });
        });
    });

    function openRejectModal(katalogId, companyName) {
        document.getElementById('rejectModal').style.display = 'flex';
        document.getElementById('rejectCompanyName').textContent = companyName;
        document.getElementById('rejectForm').action = `/admin/katalog/${katalogId}/reject`;
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').style.display = 'none';
        document.getElementById('rejectForm').reset();
    }

    function openRevisionModal(katalogId, companyName) {
        document.getElementById('revisionModal').style.display = 'flex';
        document.getElementById('revisionCompanyName').textContent = companyName;
        document.getElementById('revisionForm').action = `/admin/katalog/${katalogId}/revision`;
    }

    function closeRevisionModal() {
        document.getElementById('revisionModal').style.display = 'none';
        document.getElementById('revisionForm').reset();
    }

    function openKategoriModal() {
        document.getElementById('kategoriModal').style.display = 'flex';
    }

    function closeKategoriModal() {
        document.getElementById('kategoriModal').style.display = 'none';
    }

    document.getElementById('rejectModal').addEventListener('click', function(e) {
        if (e.target === this) closeRejectModal();
    });
    document.getElementById('revisionModal').addEventListener('click', function(e) {
        if (e.target === this) closeRevisionModal();
    });
    document.getElementById('kategoriModal').addEventListener('click', function(e) {
        if (e.target === this) closeKategoriModal();
    });
</script>
@endpush
@endsection