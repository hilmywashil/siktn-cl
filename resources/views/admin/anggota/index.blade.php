@extends('admin.layouts.admin-layout')

@section('title', 'Kelola Anggota')
@section('page-title', 'Kelola Anggota')

@php
    $activeMenu = 'anggota';
    $admin = auth()->guard('admin')->user();
@endphp

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
            --gold: #b7830f; --green: #059669; --blue: #2563eb; --red: #dc2626; --amber: #d97706;
            --gray-50: #f9fafb; --gray-100: #f3f4f6; --gray-200: #e5e7eb; --gray-300: #d1d5db;
            --gray-500: #6b7280; --gray-700: #374151; --gray-900: #111827;
            --radius-sm: 4px; --radius-md: 6px; --radius-lg: 8px;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        /* ===== Stat Cards ===== */
        .stat-cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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
            content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; background: var(--navy);
        }
        .stat-card.pending-profile::before { background: var(--gray-500); }
        .stat-card.pending-verify::before { background: var(--amber); }
        .stat-card.approved::before { background: var(--green); }
        .stat-card.rejected::before { background: var(--red); }

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
            font-size: 1.6rem; font-weight: 800; color: var(--navy);
            margin-top: 0.2rem; font-family: 'Montserrat', sans-serif;
        }

        /* ===== Filter Box ===== */
        .filter-card {
            background: white; border-radius: var(--radius-lg); padding: 1.25rem 1.5rem;
            border: 1px solid var(--gray-200); box-shadow: 0 1px 3px rgba(0,0,0,0.05); margin-bottom: 1.5rem;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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
            display: flex; gap: 0.5rem; align-items: center;
        }
        /* invisible label keeps the button row aligned with the select/input columns */
        .filter-actions-spacer { font-size: 0.85rem; visibility: hidden; }

        /* ===== Buttons ===== */
        .btn-solid-navy {
            background: var(--navy); color: white; padding: 0.55rem 1.15rem; border-radius: var(--radius-md);
            font-weight: 600; font-size: 0.875rem; border: none; cursor: pointer;
            display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 2px 4px rgba(2, 38, 72, 0.12);
            white-space: nowrap;
        }
        .btn-solid-navy:hover { background: var(--navy-light); color: white; transform: translateY(-1px); box-shadow: 0 4px 10px rgba(2, 38, 72, 0.2); }

        .btn-outline-secondary {
            background: white; color: var(--gray-700); padding: 0.55rem 1.15rem; border-radius: var(--radius-md);
            font-weight: 600; font-size: 0.875rem; border: 1px solid var(--gray-300); cursor: pointer;
            display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none;
            transition: all 0.2s ease; white-space: nowrap;
        }
        .btn-outline-secondary:hover { background: var(--gray-100); color: var(--navy); }

        /* ===== Filter Tabs Header ===== */
        .filter-tabs-header {
            display: flex; justify-content: space-between; align-items: center;
            flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem;
        }

        .filter-tabs { display: flex; gap: 0.5rem; flex-wrap: wrap; }

        .filter-tab {
            padding: 0.5rem 1rem; border-radius: var(--radius-md); border: 1px solid var(--gray-200);
            background: white; color: var(--gray-700); font-weight: 600; font-size: 0.85rem;
            cursor: pointer; transition: all 0.2s; text-decoration: none;
        }
        .filter-tab:hover { background: var(--gray-100); border-color: var(--gray-300); }
        .filter-tab.active { background: var(--navy); color: white; border-color: var(--navy); }

        .toolbar-actions { display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap; }

        /* ===== View toggle ===== */
        .view-mode-toggle {
            display: inline-flex; background: #f1f5f9; border-radius: 6px; padding: 3px;
            border: 1px solid #cbd5e1; margin-left: 0.25rem;
        }
        .btn-toggle-view {
            padding: 6px 12px; border-radius: 4px; border: none; font-weight: 700; font-size: 0.8rem;
            cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
            transition: all 0.2s; background: transparent; color: #475569;
        }
        .btn-toggle-view.active { background: var(--navy); color: #ffffff; }

        /* ===== Table & Action Dropdown (⋮) ===== */
        .table-container {
            background: white; border-radius: var(--radius-lg); border: 1px solid var(--gray-200);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); overflow: hidden;
        }
        .table-wrapper { overflow-x: auto; }
        .table { width: 100%; border-collapse: collapse; min-width: 1000px; }
        .table thead { background: var(--gray-50); border-bottom: 1px solid var(--gray-200); }
        .table th {
            padding: 0.875rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 700;
            color: var(--gray-700); text-transform: uppercase; letter-spacing: 0.05em;
        }
        .table td {
            padding: 1rem; border-bottom: 1px solid var(--gray-100); font-size: 0.875rem;
            color: var(--gray-900); vertical-align: middle;
        }
        .table tbody tr:last-child td { border-bottom: none; }
        .table tbody tr:hover { background: var(--gray-50); }

        .row-avatar {
            width: 38px; height: 38px; border-radius: 50%; background: var(--navy);
            display: flex; align-items: center; justify-content: center; color: #ffffff;
            font-weight: 700; font-size: 0.875rem; flex-shrink: 0; overflow: hidden;
            border: 2px solid var(--gray-200);
        }
        .row-avatar img { width: 100%; height: 100%; object-fit: cover; display: block; }

        .status-badge {
            display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.35rem 0.75rem;
            border-radius: 9999px; font-size: 0.75rem; font-weight: 700; white-space: nowrap;
        }
        .status-badge.pending_verification { background: #fef3c7; color: #d97706; }
        .status-badge.pending_profile { background: #f3f4f6; color: #4b5563; }
        .status-badge.approved { background: #d1fae5; color: #065f46; }
        .status-badge.rejected { background: #fee2e2; color: #991b1b; }

        .aksi-wrapper { position: relative; display: inline-block; }

        .btn-aksi-trigger {
            width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center;
            background: var(--navy); color: #ffffff; border: none; border-radius: var(--radius-md);
            cursor: pointer; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 1px 3px rgba(2, 38, 72, 0.12);
        }
        .btn-aksi-trigger:hover { background: var(--navy-light); transform: scale(1.08) translateY(-1px); box-shadow: 0 4px 12px rgba(2, 38, 72, 0.25); }

        .btn-aksi-trigger.subtle {
            background: transparent; color: #64748b; width: 32px; height: 32px;
            border-radius: 50%; border: 1px solid transparent; box-shadow: none;
        }
        .btn-aksi-trigger.subtle:hover { background: #f1f5f9; color: var(--navy); transform: none; box-shadow: none; }

        .aksi-dropdown {
            position: fixed; min-width: 175px; background: #ffffff; border: 1px solid var(--gray-200);
            border-radius: var(--radius-md); box-shadow: 0 14px 32px rgba(2, 38, 72, 0.18); padding: 6px;
            z-index: 9999; opacity: 0; visibility: hidden; transform: translateY(-8px) scale(0.96);
            transition: opacity 0.18s cubic-bezier(0.16, 1, 0.3, 1), transform 0.18s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.18s;
            pointer-events: none;
        }
        .aksi-dropdown.is-open { opacity: 1; visibility: visible; transform: translateY(0) scale(1); pointer-events: auto; }

        .aksi-item {
            display: flex; align-items: center; gap: 9px; width: 100%; padding: 0.55rem 0.65rem;
            font-size: 0.8125rem; font-weight: 600; border-radius: var(--radius-sm); color: var(--gray-900);
            text-decoration: none !important; border: none; background: transparent; text-align: left;
            cursor: pointer; transition: all 0.18s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .aksi-item:hover { background: var(--gray-100); transform: translateX(4px); }
        .aksi-item.aksi-view:hover { color: var(--navy); }
        .aksi-item.aksi-delete:hover { color: var(--red); background: #fef2f2; }

        .aksi-divider { height: 1px; background: var(--gray-200); margin: 4px 0; }

        .btn-bulk {
            font-family: 'Inter', sans-serif; display: inline-flex; align-items: center; gap: 0.5rem;
            background: var(--red); border: none; padding: 0.55rem 1.15rem; border-radius: var(--radius-md);
            color: white; cursor: pointer; font-size: 0.875rem; font-weight: 600; transition: all 0.2s;
        }
        .btn-bulk:hover { background: #b91c1c; }

        .empty-state {
            text-align: center; padding: 4rem 2rem; color: var(--gray-500);
        }
        .empty-state svg { margin-bottom: 1rem; opacity: 0.5; }
        .empty-state h3 { color: var(--navy); font-weight: 700; margin: 0 0 0.5rem 0; }
        .empty-state p { margin: 0; font-size: 0.875rem; }

        /* ===== Grid View ===== */
        #anggotaGridViewContainer {
            display: none;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.25rem;
            margin-bottom: 2rem;
            align-items: stretch;
        }

        .member-card {
            background: white; border-radius: 8px; padding: 1.25rem; border: 1px solid var(--gray-200);
            box-shadow: 0 1px 3px rgba(2,38,72,0.05); display: flex; flex-direction: column;
            align-items: center; text-align: center; position: relative; width: 100%;
            transition: box-shadow 0.2s ease, border-color 0.2s ease;
        }
        .member-card:hover {
            box-shadow: 0 12px 24px -4px rgba(2, 38, 72, 0.14);
            border-color: var(--gray-300);
        }

        .member-card-menu { position: absolute; top: 10px; right: 10px; z-index: 10; }

        .member-avatar {
            width: 72px; height: 72px; border-radius: 50%; overflow: hidden; margin-bottom: 0.75rem;
            border: 3px solid var(--gray-200); background: var(--navy); color: white;
            display: flex; align-items: center; justify-content: center; font-size: 1.75rem;
            font-weight: 800; font-family: 'Montserrat', sans-serif; flex-shrink: 0;
            box-shadow: 0 4px 10px rgba(2,38,72,0.1);
        }
        .member-avatar img { width: 100%; height: 100%; object-fit: cover; display: block; }

        .member-name { font-size: 1.05rem; font-weight: 800; color: var(--navy); margin-bottom: 3px; line-height: 1.3; word-break: break-word; }
        .member-jabatan-badge {
            display: inline-block; padding: 3px 10px; border-radius: 9999px; font-size: 0.75rem;
            font-weight: 700; background: #eff6ff; color: #1e40af; margin-bottom: 4px; border: 1px solid #dbeafe;
        }
        .member-username { font-size: 0.775rem; color: var(--gray-500); font-weight: 600; margin-bottom: 6px; }

        .member-info {
            width: 100%; font-size: 0.8125rem; color: var(--gray-700); padding-top: 10px;
            border-top: 1px solid var(--gray-100); margin-top: 8px; display: flex; flex-direction: column;
            gap: 6px; align-items: center; flex: 1;
        }
        .member-info .info-row { display: flex; align-items: center; gap: 6px; justify-content: center; max-width: 100%; }
        .member-info .info-row span { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 220px; }

        .member-actions { margin-top: 12px; width: 100%; }
        .member-actions a {
            width: 100%; padding: 7px 12px; border-radius: 6px; font-size: 0.8125rem; font-weight: 700;
            text-decoration: none; display: inline-flex; align-items: center; justify-content: center;
            gap: 6px; transition: background 0.2s;
        }
        .member-actions .btn-wa { background: #25d366; color: white; box-shadow: 0 2px 4px rgba(37,211,102,0.2); }
        .member-actions .btn-wa:hover { background: #1da851; }
        .member-actions .btn-detail { background: var(--navy); color: white; box-shadow: 0 2px 4px rgba(2,38,72,0.12); }
        .member-actions .btn-detail:hover { background: var(--navy-light); }
    </style>
@endpush

@section('content')
<div class="admin-ui-scope">

    <!-- Stat Cards Top Benchmark -->
    <div class="stat-cards-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </div>
            <div class="stat-info">
                <h4>Total Anggota</h4>
                <div class="value">{{ number_format($stats['total']) }}</div>
            </div>
        </div>

        <div class="stat-card pending-profile">
            <div class="stat-icon" style="background: #f3f4f6; color: #4b5563;">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            </div>
            <div class="stat-info">
                <h4>Belum Lengkap</h4>
                <div class="value">{{ number_format($stats['pending_profile']) }}</div>
            </div>
        </div>

        <div class="stat-card pending-verify">
            <div class="stat-icon" style="background: #fffbeb; color: #d97706;">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            </div>
            <div class="stat-info">
                <h4>Menunggu Approve</h4>
                <div class="value">{{ number_format($stats['pending_verification']) }}</div>
            </div>
        </div>

        <div class="stat-card approved">
            <div class="stat-icon" style="background: #ecfdf5; color: #059669;">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            </div>
            <div class="stat-info">
                <h4>Disetujui</h4>
                <div class="value">{{ number_format($stats['approved']) }}</div>
            </div>
        </div>

        <div class="stat-card rejected">
            <div class="stat-icon" style="background: #fef2f2; color: #dc2626;">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
            </div>
            <div class="stat-info">
                <h4>Ditolak</h4>
                <div class="value">{{ number_format($stats['rejected']) }}</div>
            </div>
        </div>
    </div>

    {{-- Filter Box --}}
    <div class="filter-card">
        <form method="GET" action="{{ route('admin.anggota.index') }}">
            <input type="hidden" name="status" value="{{ $status }}">
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
                        @if(request()->anyFilled(['search', 'domisili', 'jabatan']))
                            <a href="{{ route('admin.anggota.index', ['status' => $status]) }}" class="btn-outline-secondary">Reset</a>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Filter Tabs Header --}}
    <div class="filter-tabs-header">
        <div class="filter-tabs">
            <a href="{{ route('admin.anggota.index', array_merge(request()->query(), ['status' => 'all'])) }}"
                class="filter-tab {{ $status === 'all' ? 'active' : '' }}">
                Semua ({{ $stats['total'] }})
            </a>
            <a href="{{ route('admin.anggota.index', array_merge(request()->query(), ['status' => 'pending_profile'])) }}"
                class="filter-tab {{ $status === 'pending_profile' ? 'active' : '' }}">
                Belum Lengkap ({{ $stats['pending_profile'] }})
            </a>
            <a href="{{ route('admin.anggota.index', array_merge(request()->query(), ['status' => 'pending_verification'])) }}"
                class="filter-tab {{ $status === 'pending_verification' ? 'active' : '' }}">
                Menunggu Approve ({{ $stats['pending_verification'] }})
            </a>
            <a href="{{ route('admin.anggota.index', array_merge(request()->query(), ['status' => 'approved'])) }}"
                class="filter-tab {{ $status === 'approved' ? 'active' : '' }}">
                Disetujui ({{ $stats['approved'] }})
            </a>
            <a href="{{ route('admin.anggota.index', array_merge(request()->query(), ['status' => 'rejected'])) }}"
                class="filter-tab {{ $status === 'rejected' ? 'active' : '' }}">
                Ditolak ({{ $stats['rejected'] }})
            </a>
        </div>

        <div class="toolbar-actions">
            @if(in_array($admin->category, ['super_admin', 'pimpinan', 'pnkt', 'ppkt', 'pkkt']))
            <a href="{{ route('admin.anggota.export', request()->query()) }}" class="btn-outline-secondary" onclick="Toast.fire({ icon: 'success', title: 'File Excel Data Anggota sedang diunduh...' })">
                <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" fill="none" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                Export Excel
            </a>
            @endif
            <a href="{{ route('admin.anggota.create') }}" class="btn-solid-navy">
                <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" fill="none" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah Anggota
            </a>

            <div class="view-mode-toggle">
                <button type="button" class="btn-toggle-view active" id="btnAnggotaViewList" onclick="switchAnggotaView('list')" title="Tampilan Tabel List">
                    <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                    List
                </button>
                <button type="button" class="btn-toggle-view" id="btnAnggotaViewGrid" onclick="switchAnggotaView('grid')" title="Tampilan Kartu Grid">
                    <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    Grid
                </button>
            </div>
        </div>
    </div>

    {{-- Bulk Action & Table --}}
    <div class="table-container" id="anggotaTableContainer">
        @if($anggota->count() > 0)
            <div style="padding: 1rem 1.5rem; border-bottom: 1px solid var(--gray-200); display: none;" id="bulk-action-container">
                <button type="button" onclick="bulkDestroy()" class="btn-bulk">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                        <polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                    Hapus Terpilih (<span id="selected-count">0</span>)
                </button>
            </div>
            <div class="table-wrapper">
                <table class="table" id="anggota-table">
                    <thead>
                        <tr>
                            <th style="width: 40px; text-align: center;">
                                <input type="checkbox" id="select-all" style="cursor: pointer;">
                            </th>
                            <th>No</th>
                            <th>Nama Lengkap</th>
                            <th>Username</th>
                            <th>Jabatan</th>
                            <th>Domisili</th>
                            <th>Status</th>
                            <th>Tanggal Daftar</th>
                            <th style="text-align: center; width: 80px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($anggota as $index => $item)
                            @php
                                $hasPhoto = $item->foto_diri && Storage::disk('public')->exists($item->foto_diri);
                                $sourceName = $item->nama_lengkap ?? $item->username;
                                $words = explode(' ', trim($sourceName));
                                $initials = '';
                                foreach (array_slice($words, 0, 2) as $word) {
                                    $initials .= strtoupper(substr($word, 0, 1));
                                }
                            @endphp
                            <tr>
                                <td style="text-align: center;">
                                    @if($admin->isSuperAdmin() || strcasecmp($item->jabatan ?? '', 'Pimpinan') !== 0)
                                        <input type="checkbox" class="row-checkbox" value="{{ $item->id }}" style="cursor: pointer;">
                                    @endif
                                </td>
                                <td>{{ $anggota->firstItem() + $index }}</td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        <div class="row-avatar">
                                            @if($hasPhoto)
                                                <img src="{{ asset('storage/' . $item->foto_diri) }}" alt="{{ $item->nama_lengkap }}" loading="lazy">
                                            @else
                                                {{ $initials }}
                                            @endif
                                        </div>
                                        <div>
                                            <div style="font-weight: 700; color: var(--navy);">{{ $item->nama_lengkap ?? '-' }}</div>
                                            <div style="font-size: 0.75rem; color: var(--gray-500);">{{ $item->email ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $item->username ?? '-' }}</td>
                                <td>
                                    <span style="display: inline-block; padding: 2px 8px; border-radius: 9999px; background: #eff6ff; color: #1e40af; font-size: 0.75rem; font-weight: 700;">
                                        {{ $item->jabatan ?? 'Anggota' }}
                                    </span>
                                </td>
                                <td>{{ $item->domisili ?? 'Nasional' }}</td>
                                <td>
                                    <span class="status-badge {{ $item->status }}">
                                        @if($item->status === 'pending_verification')
                                            ● Menunggu Approve
                                        @elseif($item->status === 'pending_profile')
                                            ● Belum Lengkap
                                        @elseif($item->status === 'approved')
                                            ● Disetujui
                                        @else
                                            ● Ditolak
                                        @endif
                                    </span>
                                </td>
                                <td>{{ $item->created_at ? $item->created_at->format('d M Y') : '-' }}</td>
                                <td style="text-align: center;">
                                    <div class="aksi-wrapper">
                                        <button type="button" class="btn-aksi-trigger" data-target="dropdown-anggota-{{ $item->id }}" aria-haspopup="true" aria-expanded="false" aria-label="Menu Aksi">
                                            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                                <circle cx="12" cy="5" r="1.75"></circle>
                                                <circle cx="12" cy="12" r="1.75"></circle>
                                                <circle cx="12" cy="19" r="1.75"></circle>
                                            </svg>
                                        </button>

                                        <div class="aksi-dropdown" id="dropdown-anggota-{{ $item->id }}">
                                            <a href="{{ route('admin.anggota.show', $item) }}" class="aksi-item aksi-view">
                                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                                Lihat & Verifikasi
                                            </a>
                                            @if($admin->isSuperAdmin() || strcasecmp($item->jabatan ?? '', 'Pimpinan') !== 0)
                                                <div class="aksi-divider"></div>
                                                <button type="button" class="aksi-item aksi-delete" onclick="confirmDelete({{ $item->id }}, '{{ addslashes($item->nama_lengkap ?? $item->username) }}')">
                                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                    Hapus Data
                                                </button>
                                                <form id="delete-form-{{ $item->id }}" action="{{ route('admin.anggota.destroy', $item) }}" method="POST" style="display:none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div style="padding: 1.5rem; border-top: 1px solid var(--gray-200);">
                {{ $anggota->appends(request()->query())->links() }}
            </div>
        @else
            <div class="empty-state">
                <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                </svg>
                <h3>Tidak ada data pendaftar</h3>
                <p>Belum ada pendaftar anggota Karang Taruna untuk kriteria ini.</p>
            </div>
        @endif
    </div>

    {{-- Grid View Container --}}
    @if($anggota->count() > 0)
        <div id="anggotaGridViewContainer">
            @foreach($anggota as $item)
                @php
                    $waNumber = preg_replace('/[^0-9]/', '', $item->no_hp ?? '');
                    if (str_starts_with($waNumber, '0')) {
                        $waNumber = '62' . substr($waNumber, 1);
                    }
                    $hasPhoto = $item->foto_diri && Storage::disk('public')->exists($item->foto_diri);
                @endphp
                <div class="member-card">
                    <div class="member-card-menu">
                        <div class="aksi-wrapper" style="position: relative;">
                            <button type="button" class="btn-aksi-trigger subtle" onclick="toggleGridDropdown(event, {{ $item->id }})" aria-label="Menu Aksi">
                                <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                                    <circle cx="12" cy="5" r="1.75"></circle>
                                    <circle cx="12" cy="12" r="1.75"></circle>
                                    <circle cx="12" cy="19" r="1.75"></circle>
                                </svg>
                            </button>

                            <div class="aksi-dropdown grid-dropdown" id="dropdown-grid-{{ $item->id }}" style="position: absolute !important; top: 38px !important; right: 0 !important; left: auto !important; transform: none !important; min-width: 175px; z-index: 9999;">
                                <a href="{{ route('admin.anggota.show', $item) }}" class="aksi-item aksi-view">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    Lihat & Verifikasi
                                </a>
                                @if($admin->isSuperAdmin() || strcasecmp($item->jabatan ?? '', 'Pimpinan') !== 0)
                                    <div class="aksi-divider"></div>
                                    <button type="button" class="aksi-item aksi-delete" onclick="confirmDelete({{ $item->id }}, '{{ addslashes($item->nama_lengkap ?? $item->username) }}')">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        Hapus Data
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="member-avatar">
                        @if($hasPhoto)
                            <img src="{{ asset('storage/' . $item->foto_diri) }}" alt="{{ $item->nama_lengkap }}">
                        @else
                            {{ strtoupper(substr($item->nama_lengkap ?? 'A', 0, 1)) }}
                        @endif
                    </div>

                    <div class="member-name">{{ $item->nama_lengkap ?? 'Anggota' }}</div>
                    <div class="member-jabatan-badge">{{ $item->jabatan ?? 'Anggota Karang Taruna' }}</div>
                    <div class="member-username">{{ '@' . ($item->username ?? 'user') }}</div>

                    <div class="member-info">
                        <div class="info-row">
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="#64748b" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <span title="{{ $item->domisili ?? 'Nasional' }}">{{ $item->domisili ?? 'Nasional' }}</span>
                        </div>
                        @if($item->email)
                            <div class="info-row">
                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="#64748b" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                <span title="{{ $item->email }}">{{ $item->email }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="member-actions">
                        @if($waNumber)
                            <a href="https://wa.me/{{ $waNumber }}" target="_blank" class="btn-wa">
                                <i class="fab fa-whatsapp" style="font-size: 1rem; margin-right: 4px;"></i> WhatsApp
                            </a>
                        @else
                            <a href="{{ route('admin.anggota.show', $item) }}" class="btn-detail">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                Detail Anggota
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection

@push('scripts')
<style>
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    .animate-view-fade { animation: fadeIn 0.3s ease-in-out; }
</style>
<script>
    function switchAnggotaView(mode) {
        const gridView = document.getElementById('anggotaGridViewContainer');
        const listView = document.getElementById('anggotaTableContainer');
        const btnGrid = document.getElementById('btnAnggotaViewGrid');
        const btnList = document.getElementById('btnAnggotaViewList');

        closeActiveDropdown();

        if (mode === 'grid') {
            if (listView) listView.style.display = 'none';
            if (gridView) {
                gridView.style.display = 'grid';
                gridView.classList.remove('animate-view-fade');
                void gridView.offsetWidth;
                gridView.classList.add('animate-view-fade');
            }
            btnGrid?.classList.add('active');
            btnList?.classList.remove('active');
            localStorage.setItem('siktn_view_mode_anggota', 'grid');
        } else {
            if (gridView) gridView.style.display = 'none';
            if (listView) {
                listView.style.display = 'block';
                listView.classList.remove('animate-view-fade');
                void listView.offsetWidth;
                listView.classList.add('animate-view-fade');
            }
            btnList?.classList.add('active');
            btnGrid?.classList.remove('active');
            localStorage.setItem('siktn_view_mode_anggota', 'list');
        }
    }

    let activeDropdown = null;
    let activeTrigger = null;

    function closeActiveDropdown() {
        if (activeDropdown) {
            activeDropdown.classList.remove('is-open');
            activeTrigger?.setAttribute('aria-expanded', 'false');
            activeDropdown = null;
            activeTrigger = null;
        }
        document.querySelectorAll('.grid-dropdown.is-open').forEach(d => {
            d.classList.remove('is-open');
        });
    }

    function toggleGridDropdown(event, id) {
        event.stopPropagation();
        event.preventDefault();
        const dropdown = document.getElementById('dropdown-grid-' + id);
        if (!dropdown) return;

        const isOpen = dropdown.classList.contains('is-open');

        // Close table view active dropdown
        closeActiveDropdown();

        if (isOpen) {
            dropdown.classList.remove('is-open');
        } else {
            dropdown.classList.add('is-open');
        }
    }

    function positionDropdown(trigger, dropdown) {
        const rect = trigger.getBoundingClientRect();
        const margin = 8;
        const dropdownWidth = dropdown.offsetWidth || 175;

        // Horizontal: align to the right edge of the trigger, but keep on-screen
        let left = rect.right - dropdownWidth;
        left = Math.max(margin, Math.min(left, window.innerWidth - dropdownWidth - margin));

        // Vertical: open below by default, flip above if it would overflow the viewport
        const estimatedHeight = dropdown.offsetHeight || 96;
        let top = rect.bottom + 4;
        if (top + estimatedHeight > window.innerHeight - margin) {
            top = Math.max(margin, rect.top - estimatedHeight - 4);
        }

        dropdown.style.left = left + 'px';
        dropdown.style.top = top + 'px';
    }

    document.addEventListener('DOMContentLoaded', function () {
        const savedMode = localStorage.getItem('siktn_view_mode_anggota') || 'list';
        switchAnggotaView(savedMode);

        if (typeof $.fn.select2 !== 'undefined') {
            $('.select2-basic').select2({
                minimumResultsForSearch: -1,
                width: '100%'
            });
        }

        // Table view dropdown trigger listener
        document.querySelectorAll('#anggotaTableContainer .btn-aksi-trigger').forEach(button => {
            button.addEventListener('click', function (e) {
                e.stopPropagation();
                const targetId = this.getAttribute('data-target');
                const dropdown = document.getElementById(targetId);
                if (!dropdown) return;

                if (activeDropdown && activeDropdown !== dropdown) {
                    closeActiveDropdown();
                }

                if (dropdown.classList.contains('is-open')) {
                    closeActiveDropdown();
                } else {
                    dropdown.classList.add('is-open');
                    positionDropdown(this, dropdown);
                    this.setAttribute('aria-expanded', 'true');
                    activeDropdown = dropdown;
                    activeTrigger = this;
                }
            });
        });

        document.addEventListener('click', closeActiveDropdown);
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeActiveDropdown();
        });
        window.addEventListener('scroll', closeActiveDropdown, true);
        window.addEventListener('resize', closeActiveDropdown);
    });

    // Bulk Select Logic
    const selectAllBtn = document.getElementById('select-all');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    const bulkActionContainer = document.getElementById('bulk-action-container');
    const selectedCountSpan = document.getElementById('selected-count');

    function updateBulkActionUI() {
        if (!bulkActionContainer) return;
        const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
        selectedCountSpan.textContent = checkedCount;
        bulkActionContainer.style.display = checkedCount > 0 ? 'block' : 'none';
    }

    if (selectAllBtn) {
        selectAllBtn.addEventListener('change', function() {
            rowCheckboxes.forEach(cb => { cb.checked = this.checked; });
            updateBulkActionUI();
        });

        rowCheckboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                const allChecked = document.querySelectorAll('.row-checkbox:checked').length === rowCheckboxes.length;
                selectAllBtn.checked = allChecked;
                updateBulkActionUI();
            });
        });
    }

    function confirmDelete(id, name) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Hapus Data Anggota?',
                text: `Apakah Anda yakin ingin menghapus data anggota ${name}?`,
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
        } else if (confirm(`Apakah Anda yakin ingin menghapus data anggota ${name}?`)) {
            document.getElementById('delete-form-' + id).submit();
        }
    }

    function bulkDestroy() {
        const checked = document.querySelectorAll('.row-checkbox:checked');
        if (checked.length === 0) return;

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Hapus Massal?',
                text: `Yakin ingin memindahkan ${checked.length} data anggota ini ke Data Terhapus?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#f3f4f6',
                confirmButtonText: 'Ya, Hapus Semua',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const ids = Array.from(checked).map(cb => cb.value);

                    fetch("{{ route('admin.anggota.bulk-destroy') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({ ids: ids })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'deleted') {
                            Toast.fire({ icon: 'success', title: data.message });
                            setTimeout(() => window.location.reload(), 1500);
                        } else {
                            Toast.fire({ icon: 'error', title: 'Terjadi kesalahan.' });
                        }
                    })
                    .catch(err => Toast.fire({ icon: 'error', title: 'Terjadi kesalahan sistem.' }));
                }
            });
        }
    }
</script>
@endpush