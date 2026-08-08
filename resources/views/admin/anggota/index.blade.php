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
            content: '';
            position: absolute;
            left: 0; top: 0; bottom: 0;
            width: 4px;
            background: var(--navy);
        }
        .stat-card.pending-profile::before { background: var(--gray-500); }
        .stat-card.pending-verify::before { background: var(--amber); }
        .stat-card.approved::before { background: var(--green); }
        .stat-card.rejected::before { background: var(--red); }

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

        /* Table & Action Dropdown (⋮) */
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
            min-width: 1000px;
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

        .status-badge.pending_verification { background: #fef3c7; color: #d97706; }
        .status-badge.pending_profile { background: #f3f4f6; color: #4b5563; }
        .status-badge.approved { background: #d1fae5; color: #065f46; }
        .status-badge.rejected { background: #fee2e2; color: #991b1b; }

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
        .aksi-item.aksi-delete:hover { color: var(--red); background: #fef2f2; }

        .aksi-divider {
            height: 1px;
            background: var(--gray-200);
            margin: 4px 0;
        }

        .btn-bulk {
            font-family: 'Inter', sans-serif;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--red);
            border: none;
            padding: 0.55rem 1.15rem;
            border-radius: var(--radius-md);
            color: white;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-bulk:hover {
            background: #b91c1c;
        }
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
                <h4>Total Pendaftar</h4>
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

                <div style="display: flex; gap: 0.5rem; align-items: flex-end;">
                    <button type="submit" class="btn-solid-navy" style="white-space: nowrap;">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        Filter
                    </button>
                    @if(request()->anyFilled(['search', 'domisili', 'jabatan']))
                        <a href="{{ route('admin.anggota.index', ['status' => $status]) }}" class="btn-outline-secondary" style="white-space: nowrap;">Reset</a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- Filter Tabs & Actions --}}
    <div class="filter-tabs-header">
        <div class="filter-tabs">
            <a href="{{ route('admin.anggota.index', array_merge(request()->query(), ['status' => 'all'])) }}"
                class="filter-tab {{ $status === 'all' ? 'active' : '' }}">
                Semua ({{ $stats['total'] }})
            </a>
            <a href="{{ route('admin.anggota.index', array_merge(request()->query(), ['status' => 'pending_profile'])) }}"
                class="filter-tab {{ $status === 'pending_profile' ? 'active' : '' }}">
                Belum Lengkapi Profil ({{ $stats['pending_profile'] }})
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

        <div style="display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
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

            <div class="view-mode-toggle" style="display: inline-flex; background: #f1f5f9; border-radius: 6px; padding: 3px; border: 1px solid #cbd5e1; margin-left: 0.25rem;">
                <button type="button" class="btn-toggle-view active" id="btnAnggotaViewList" onclick="switchAnggotaView('list')" title="Tampilan Tabel List" style="padding: 6px 12px; border-radius: 4px; border: none; font-weight: 700; font-size: 0.8rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s; background: #022648; color: white;">
                    <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                    List
                </button>
                <button type="button" class="btn-toggle-view" id="btnAnggotaViewGrid" onclick="switchAnggotaView('grid')" title="Tampilan Kartu Grid" style="padding: 6px 12px; border-radius: 4px; border: none; font-weight: 700; font-size: 0.8rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s; background: transparent; color: #475569;">
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
                            <tr>
                                <td style="text-align: center;">
                                    @if($admin->isSuperAdmin() || strcasecmp($item->jabatan ?? '', 'Pimpinan') !== 0)
                                        <input type="checkbox" class="row-checkbox" value="{{ $item->id }}" style="cursor: pointer;">
                                    @else
                                        <span title="Data Pimpinan hanya dapat dikelola oleh Super Admin" style="color: var(--gray-300); cursor: not-allowed;">🔒</span>
                                    @endif
                                </td>
                                <td>{{ $anggota->firstItem() + $index }}</td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        <div style="width: 38px; height: 38px; border-radius: 50%; background: #C59217; display: flex; align-items: center; justify-content: center; color: #022648; font-weight: 700; font-size: 0.875rem; flex-shrink: 0; overflow: hidden; border: 2px solid var(--gray-200);">
                                            @if($item->foto_diri)
                                                <img src="{{ Storage::url($item->foto_diri) }}" alt="{{ $item->nama_lengkap }}" style="width: 100%; height: 100%; object-fit: cover;">
                                            @else
                                                @php
                                                    $sourceName = $item->nama_lengkap ?? $item->username;
                                                    $words = explode(' ', $sourceName);
                                                    $initials = '';
                                                    foreach (array_slice($words, 0, 2) as $word) {
                                                        $initials .= strtoupper(substr($word, 0, 1));
                                                    }
                                                @endphp
                                                {{ $initials }}
                                            @endif
                                        </div>
                                        <strong style="color: var(--navy);">{{ $item->nama_lengkap ?? '-' }}</strong>
                                    </div>
                                </td>
                                <td>{{ $item->username }}</td>
                                <td>{{ $item->jabatan ?? '-' }}</td>
                                <td>{{ $item->domisili ?? '-' }}</td>
                                <td>
                                    <span class="status-badge {{ $item->status }}">
                                        @if($item->status === 'pending_verification')
                                            ● Menunggu Approve
                                        @elseif($item->status === 'pending_profile')
                                            ● Belum Lengkapi Profil
                                        @elseif($item->status === 'approved')
                                            ● Disetujui
                                        @else
                                            ● Ditolak
                                        @endif
                                    </span>
                                </td>
                                <td>{{ $item->created_at->format('d M Y') }}</td>
                                <td style="text-align: center;">
                                    <!-- Action Dropdown Trigger (⋮) -->
                                    <div class="aksi-wrapper">
                                        <button type="button" class="btn-aksi-trigger" data-target="dropdown-anggota-{{ $item->id }}" aria-label="Menu Aksi">
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
            <div style="text-align: center; padding: 4rem 2rem; color: var(--gray-500);">
                <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom: 1rem; opacity: 0.5;">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                </svg>
                <h3 style="color: var(--navy); font-weight: 700; margin: 0 0 0.5rem 0;">Tidak ada data pendaftar</h3>
                <p style="margin: 0; font-size: 0.875rem;">Belum ada pendaftar anggota Karang Taruna untuk kriteria ini.</p>
            </div>
        @endif
    </div>

    {{-- Grid View Container --}}
    @if($anggota->count() > 0)
        <div id="anggotaGridViewContainer" style="display: none; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
            @foreach($anggota as $item)
                @php
                    $waNumber = preg_replace('/[^0-9]/', '', $item->no_hp ?? '');
                    if (str_starts_with($waNumber, '0')) {
                        $waNumber = '62' . substr($waNumber, 1);
                    }
                @endphp
                <div style="background: white; border-radius: 12px; padding: 1.5rem 1.25rem; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(2,38,72,0.05); display: flex; flex-direction: column; align-items: center; text-align: center; position: relative; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); height: 100%;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 12px 24px -4px rgba(2, 38, 72, 0.12)'; this.style.borderColor='#cbd5e1'" onmouseout="this.style.transform='none'; this.style.boxShadow='0 1px 3px rgba(2,38,72,0.05)'; this.style.borderColor='#e2e8f0'">
                    
                    {{-- Action Dropdown Trigger (Subtle Dot Button) --}}
                    <div style="position: absolute; top: 12px; right: 12px; z-index: 10;">
                        <div class="aksi-wrapper">
                            <button type="button" class="btn-aksi-trigger" data-target="dropdown-grid-{{ $item->id }}" aria-label="Menu Aksi" style="background: transparent !important; color: #64748b !important; width: 32px !important; height: 32px !important; border-radius: 50% !important; border: 1px solid transparent !important; box-shadow: none !important; display: flex !important; align-items: center !important; justify-content: center !important; cursor: pointer !important; transition: all 0.2s;" onmouseover="this.style.background='#f1f5f9'; this.style.color='#022648'" onmouseout="this.style.background='transparent'; this.style.color='#64748b'">
                                <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                                    <circle cx="12" cy="5" r="1.75"></circle>
                                    <circle cx="12" cy="12" r="1.75"></circle>
                                    <circle cx="12" cy="19" r="1.75"></circle>
                                </svg>
                            </button>

                            <div class="aksi-dropdown" id="dropdown-grid-{{ $item->id }}">
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

                    {{-- Avatar Circle --}}
                    <div style="width: 76px; height: 76px; border-radius: 50%; overflow: hidden; margin-bottom: 0.85rem; border: 3px solid #e2e8f0; background: #022648; color: white; display: flex; align-items: center; justify-content: center; font-size: 1.85rem; font-weight: 800; font-family: 'Montserrat', sans-serif; flex-shrink: 0; box-shadow: 0 4px 10px rgba(2,38,72,0.1);">
                        @if($item->foto_diri && Storage::disk('public')->exists($item->foto_diri))
                            <img src="{{ asset('storage/' . $item->foto_diri) }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            {{ strtoupper(substr($item->nama_lengkap ?? 'A', 0, 1)) }}
                        @endif
                    </div>

                    {{-- Name & Username --}}
                    <div style="font-size: 1.05rem; font-weight: 800; color: #022648; margin-bottom: 2px; line-height: 1.3;">{{ $item->nama_lengkap ?? 'Anggota' }}</div>
                    <div style="font-size: 0.775rem; color: #64748b; font-weight: 600; margin-bottom: 8px;">{{ '@' . ($item->username ?? 'user') }}</div>
                    
                    {{-- Jabatan Pill Badge --}}
                    <div style="display: inline-block; padding: 4px 12px; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; background: #eff6ff; color: #1e40af; margin-bottom: 8px; border: 1px solid #dbeafe;">
                        {{ $item->jabatan ?? 'Anggota' }}
                    </div>

                    {{-- Status Badge --}}
                    <div>
                        @if($item->status === 'approved')
                            <span class="badge-status badge-success" style="font-size: 0.75rem;">● Disetujui</span>
                        @elseif($item->status === 'pending_verification')
                            <span class="badge-status badge-warning" style="font-size: 0.75rem;">● Menunggu Verification</span>
                        @elseif($item->status === 'rejected')
                            <span class="badge-status badge-danger" style="font-size: 0.75rem;">● Ditolak</span>
                        @else
                            <span class="badge-status badge-secondary" style="font-size: 0.75rem;">● Belum Lengkapi Profil</span>
                        @endif
                    </div>

                    {{-- Info Section with Icons --}}
                    <div style="width: 100%; font-size: 0.8125rem; color: #475569; padding-top: 12px; border-top: 1px solid #f1f5f9; margin-top: 12px; display: flex; flex-direction: column; gap: 6px; align-items: center;">
                        <div style="display: flex; align-items: center; gap: 6px; justify-content: center; word-break: break-word;">
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="#64748b" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <span>{{ $item->domisili ?? 'Nasional' }}</span>
                        </div>
                        @if($item->email)
                            <div style="display: flex; align-items: center; gap: 6px; justify-content: center; word-break: break-all;">
                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="#64748b" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                <span>{{ $item->email }}</span>
                            </div>
                        @endif
                        @if($item->no_hp)
                            <div style="display: flex; align-items: center; gap: 6px; justify-content: center;">
                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="#64748b" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                <span>{{ $item->no_hp }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- Action Button (Always Pushed to Bottom of Card) --}}
                    <div style="margin-top: auto; width: 100%; padding-top: 14px;">
                        @if($waNumber)
                            <a href="https://wa.me/{{ $waNumber }}" target="_blank" style="width: 100%; background: #25d366; color: white; padding: 7px 12px; border-radius: 6px; font-size: 0.8125rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 6px; transition: background 0.2s; box-shadow: 0 2px 4px rgba(37,211,102,0.2);" onmouseover="this.style.background='#1da851'" onmouseout="this.style.background='#25d366'">
                                <svg viewBox="0 0 24 24" width="15" height="15" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-1.144 4.177 4.287-1.124z"/></svg>
                                WhatsApp
                            </a>
                        @else
                            <a href="{{ route('admin.anggota.show', $item) }}" style="width: 100%; background: #022648; color: white; padding: 7px 12px; border-radius: 6px; font-size: 0.8125rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 6px; transition: background 0.2s; box-shadow: 0 2px 4px rgba(2,38,72,0.12);" onmouseover="this.style.background='#0a3a6b'" onmouseout="this.style.background='#022648'">
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
<script>
    function switchAnggotaView(mode) {
        const gridView = document.getElementById('anggotaGridViewContainer');
        const listView = document.getElementById('anggotaTableContainer');
        const btnGrid = document.getElementById('btnAnggotaViewGrid');
        const btnList = document.getElementById('btnAnggotaViewList');

        if (mode === 'grid') {
            if (listView) listView.style.display = 'none';
            if (gridView) gridView.style.display = 'grid';
            if (btnGrid) {
                btnGrid.style.background = '#022648';
                btnGrid.style.color = '#ffffff';
            }
            if (btnList) {
                btnList.style.background = 'transparent';
                btnList.style.color = '#475569';
            }
            localStorage.setItem('siktn_view_mode_anggota', 'grid');
        } else {
            if (gridView) gridView.style.display = 'none';
            if (listView) listView.style.display = 'block';
            if (btnList) {
                btnList.style.background = '#022648';
                btnList.style.color = '#ffffff';
            }
            if (btnGrid) {
                btnGrid.style.background = 'transparent';
                btnGrid.style.color = '#475569';
            }
            localStorage.setItem('siktn_view_mode_anggota', 'list');
        }
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

    // Bulk Select Logic
    const selectAllBtn = document.getElementById('select-all');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    const bulkActionContainer = document.getElementById('bulk-action-container');
    const selectedCountSpan = document.getElementById('selected-count');

    function updateBulkActionUI() {
        if(!bulkActionContainer) return;
        const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
        selectedCountSpan.textContent = checkedCount;
        if (checkedCount > 0) {
            bulkActionContainer.style.display = 'block';
        } else {
            bulkActionContainer.style.display = 'none';
        }
    }

    if (selectAllBtn) {
        selectAllBtn.addEventListener('change', function() {
            rowCheckboxes.forEach(cb => {
                cb.checked = this.checked;
            });
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
                            Toast.fire({ 
                                icon: 'success', 
                                title: data.message
                            });
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