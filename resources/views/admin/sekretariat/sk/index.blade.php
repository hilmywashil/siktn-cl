@extends('admin.layouts.admin-layout')

@section('title', 'Surat Keputusan (SK) - SIKTN Admin')
@section('page-title', 'Sekretariat - Surat Keputusan (SK)')

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

    /* Alert Banner */
    .alert-warning-custom {
        background: #fffbeb; border: 1px solid #fde68a; color: #92400e;
        padding: 1rem 1.25rem; border-radius: var(--radius-lg); margin-bottom: 1.5rem;
        display: flex; align-items: center; gap: 12px;
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
        content: ''; position: absolute; left: 0; top: 0; bottom: 0;
        width: 4px; background: var(--navy);
    }
    .stat-card.active-card::before  { background: var(--green); }
    .stat-card.warning-card::before { background: var(--amber); }
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

    /* Buttons Benchmark */
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
    .table { width: 100%; border-collapse: collapse; min-width: 860px; }
    .table thead { background: var(--gray-50); border-bottom: 1px solid var(--gray-200); }
    .table th { padding: 0.875rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: var(--gray-700); text-transform: uppercase; letter-spacing: 0.05em; }
    .table td { padding: 1rem; border-bottom: 1px solid var(--gray-100); font-size: 0.875rem; color: var(--gray-900); vertical-align: middle; }
    .table tbody tr:hover { background: var(--gray-50); }

    /* Status Badges */
    .status-badge { display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.35rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; white-space: nowrap; }
    .status-badge.approved { background: #d1fae5; color: #065f46; }
    .status-badge.rejected { background: #fee2e2; color: #991b1b; }

    /* Action Trigger (⋮) */
    .aksi-wrapper { position: relative; display: inline-block; }
    .btn-aksi-trigger {
        width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center;
        background: var(--navy); color: #ffffff; border: none; border-radius: var(--radius-md);
        cursor: pointer; transition: all 0.2s cubic-bezier(0.4,0,0.2,1);
        box-shadow: 0 1px 3px rgba(2,38,72,0.12);
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
    .aksi-item.aksi-edit:hover { color: var(--blue); }
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
    .modal-overlay.active { display: flex !important; visibility: visible !important; opacity: 1 !important; pointer-events: auto !important; }
    .modal-content-lg {
        background: #ffffff; border-radius: 12px; max-width: 820px; width: 100%;
        box-shadow: 0 24px 48px rgba(2, 38, 72, 0.25); border: 1px solid rgba(2, 38, 72, 0.1);
        overflow: hidden; transform: scale(0.94) translateY(12px);
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1); max-height: 85vh;
        display: flex; flex-direction: column; margin: auto;
    }
    .modal-content-lg form {
        display: flex; flex-direction: column; flex: 1 1 auto; min-height: 0; overflow: hidden; margin: 0;
    }
    .modal-overlay.active .modal-content-lg { transform: scale(1) translateY(0); }
    .modal-header-prof {
        padding: 1.2rem 1.5rem; background: linear-gradient(135deg, #022648 0%, #01162f 100%);
        color: white; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;
    }
    .modal-body-prof { padding: 1.5rem; overflow-y: auto; flex: 1 1 auto; min-height: 0; }
    .modal-footer-prof {
        padding: 1rem 1.5rem; background: #f8f9fc; border-top: 1px solid #e5e7eb;
        display: flex; justify-content: flex-end; gap: 0.75rem; flex-shrink: 0; margin-top: auto;
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
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
            </div>
            <div class="stat-info">
                <h4>Total SK Terbit</h4>
                <div class="value">{{ $sks->total() }}</div>
            </div>
        </div>

        <div class="stat-card active-card">
            <div class="stat-icon" style="background: #ecfdf5; color: #059669;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            </div>
            <div class="stat-info">
                <h4>SK Status Aktif</h4>
                <div class="value">{{ $sks->where('status', 'Aktif')->count() }}</div>
            </div>
        </div>

        @if(count($expiringSks) > 0)
        <div class="stat-card warning-card">
            <div class="stat-icon" style="background: #fffbeb; color: #d97706;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            </div>
            <div class="stat-info">
                <h4>Mendekati Kedaluwarsa (H-180)</h4>
                <div class="value" style="color: var(--amber);">{{ count($expiringSks) }}</div>
            </div>
        </div>
        @endif
    </div>

    <!-- Alert Banner Pengingat -->
    @if(count($expiringSks) > 0)
    <div class="alert-warning-custom">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        <div>
            <strong>Perhatian (Pemberitahuan Sistem H-180 / 6 Bulan):</strong>
            Terdapat <strong>{{ count($expiringSks) }} Surat Keputusan (SK)</strong> yang masa berlakunya akan berakhir kurang dari 6 bulan lagi. Silakan periksa daftar SK di bawah.
        </div>
    </div>
    @endif

    <!-- Filter Box -->
    <div class="filter-card">
        <form action="{{ route('admin.sekretariat.sk.index') }}" method="GET">
            <div class="filter-grid">
                <div class="form-group">
                    <label for="search">Cari No. SK / Judul</label>
                    <input type="text" name="search" id="search" class="form-control" placeholder="Ketik kata kunci..." value="{{ request('search') }}">
                </div>

                <div class="form-group">
                    <label for="status">Status SK</label>
                    <select name="status" id="status" class="form-control select2-basic">
                        <option value="">-- Semua Status --</option>
                        <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Tidak Aktif" {{ request('status') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>

                <div style="display: flex; gap: 0.5rem; align-items: flex-end;">
                    <button type="submit" class="btn-solid-navy" style="white-space: nowrap;">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        Filter
                    </button>
                    @if(request()->anyFilled(['status', 'search']))
                        <a href="{{ route('admin.sekretariat.sk.index') }}" class="btn-outline-secondary">Reset</a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Page Action -->
    <div class="page-actions-row" style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 1.25rem;">
        <button type="button" class="btn-solid-navy" onclick="openCreateModal()">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Tambah SK Baru
        </button>

        <a href="{{ route('admin.sekretariat.sk.export', request()->query()) }}" onclick="Toast.fire({ icon: 'success', title: 'Mengunduh data SK ke berkas Excel...' })" style="background: #059669; color: white; border: none; padding: 0.55rem 1.25rem; border-radius: 6px; font-weight: 700; font-size: 0.875rem; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 6px rgba(5, 150, 105, 0.2); transition: all 0.2s;" onmouseover="this.style.background='#047857'" onmouseout="this.style.background='#059669'">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Export Excel SK
        </a>

        <button type="button" onclick="openImportSkModal()" style="background: #b7830f; color: white; border: none; padding: 0.55rem 1.25rem; border-radius: 6px; font-weight: 700; font-size: 0.875rem; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 6px rgba(183, 131, 15, 0.2); cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#966a0a'" onmouseout="this.style.background='#b7830f'">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            Import / Bulk Upload SK
        </button>
    </div>

    <!-- Table Container -->
    <div class="table-container">
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">
                            <input type="checkbox" id="check-all-sk" style="cursor: pointer; width: 16px; height: 16px;">
                        </th>
                        <th>NO. SK</th>
                        <th>JUDUL SURAT KEPUTUSAN</th>
                        <th>TANGGAL BERLAKU</th>
                        <th>TANGGAL BERAKHIR</th>
                        <th>STATUS</th>
                        <th style="text-align: center; width: 80px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sks as $item)
                    @php
                        $now = \Carbon\Carbon::now()->startOfDay();
                        $endDate = \Carbon\Carbon::parse($item->tanggal_berakhir)->startOfDay();
                        $daysLeft = (int) $now->diffInDays($endDate, false);
                        $isNearExpiring = $item->status == 'Aktif' && $daysLeft >= 0 && $daysLeft <= 180;
                    @endphp
                    <tr style="{{ $isNearExpiring ? 'background: #fffbeb;' : '' }}">
                        <td style="text-align: center;">
                            <input type="checkbox" class="check-sk-item" value="{{ $item->id }}" style="cursor: pointer; width: 16px; height: 16px;">
                        </td>
                        <td>
                            <strong style="color: var(--navy);">{{ $item->nomor_sk }}</strong>
                        </td>
                        <td>
                            <div style="font-weight: 600;">{{ $item->judul_sk }}</div>
                            @if($item->keterangan)
                                <div style="font-size: 0.8rem; color: var(--gray-500);">{{ $item->keterangan }}</div>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_berlaku)->format('d M Y') }}</td>
                        <td>
                            <div style="font-weight: 600;">{{ \Carbon\Carbon::parse($item->tanggal_berakhir)->format('d M Y') }}</div>
                            @if($daysLeft < 0)
                                <span style="display: inline-block; font-size: 0.72rem; color: #dc2626; background: #fee2e2; padding: 2px 6px; border-radius: 4px; font-weight: 700; margin-top: 3px;">
                                    ● Expired {{ abs($daysLeft) }} hari lalu
                                </span>
                            @elseif($daysLeft == 0)
                                <span style="display: inline-block; font-size: 0.72rem; color: #b45309; background: #fef3c7; padding: 2px 6px; border-radius: 4px; font-weight: 700; margin-top: 3px;">
                                    ⚠️ Berakhir Hari Ini
                                </span>
                            @elseif($daysLeft <= 30)
                                <span style="display: inline-block; font-size: 0.72rem; color: #b45309; background: #fef3c7; padding: 2px 6px; border-radius: 4px; font-weight: 700; margin-top: 3px;">
                                    ⏳ Sisa {{ $daysLeft }} hari lagi
                                </span>
                            @else
                                <span style="display: inline-block; font-size: 0.72rem; color: #047857; background: #d1fae5; padding: 2px 6px; border-radius: 4px; font-weight: 600; margin-top: 3px;">
                                    ✓ Sisa {{ $daysLeft }} hari lagi
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($item->status == 'Aktif')
                                <span class="status-badge approved">● Aktif</span>
                            @else
                                <span class="status-badge rejected">● Tidak Aktif</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <!-- Action Dropdown Trigger (⋮) -->
                            <div class="aksi-wrapper">
                                <button type="button" class="btn-aksi-trigger" data-target="dropdown-sk-{{ $item->id }}" aria-label="Menu Aksi">
                                    <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                        <circle cx="12" cy="5" r="1.75"></circle>
                                        <circle cx="12" cy="12" r="1.75"></circle>
                                        <circle cx="12" cy="19" r="1.75"></circle>
                                    </svg>
                                </button>

                                <div class="aksi-dropdown" id="dropdown-sk-{{ $item->id }}">
                                    @if($item->link_drive)
                                    <a href="{{ $item->link_drive }}" target="_blank" class="aksi-item aksi-view">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                                        Buka Dokumen Drive
                                    </a>

                                    <div class="aksi-divider"></div>
                                    @endif

                                    <button type="button" class="aksi-item aksi-edit" onclick="openEditModal({{ json_encode($item) }})">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        Edit SK
                                    </button>

                                    <div class="aksi-divider"></div>

                                    <button type="button" class="aksi-item aksi-delete" onclick="confirmDeleteSk({{ $item->id }}, '{{ addslashes($item->nomor_sk) }}')">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        Hapus SK
                                    </button>
                                    <form id="delete-form-{{ $item->id }}" action="{{ route('admin.sekretariat.sk.destroy', $item->id) }}" method="POST" style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 3rem; color: var(--gray-500);">Belum ada data Surat Keputusan (SK).</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Floating / Sticky Bulk Action Bar for SK -->
    <div id="bulk-action-bar-sk" style="display: none; position: sticky; bottom: 20px; z-index: 99; background: #022648; color: white; padding: 12px 20px; border-radius: 8px; margin-top: 1.25rem; align-items: center; justify-content: space-between; box-shadow: 0 8px 24px rgba(2, 38, 72, 0.25);">
        <div style="display: flex; align-items: center; gap: 10px; font-size: 0.875rem;">
            <span style="background: #b7830f; color: white; width: 26px; height: 26px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem;" id="selected-sk-count">0</span>
            <strong>Surat Keputusan Terpilih</strong>
        </div>
        <div style="display: flex; gap: 10px;">
            <button type="button" onclick="executeBulkExportSk()" style="background: #2563eb; color: white; border: none; padding: 7px 16px; border-radius: 6px; font-weight: 700; font-size: 0.8rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: background 0.2s;" onmouseover="this.style.background='#1d4ed8'" onmouseout="this.style.background='#2563eb'">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export Excel (Terpilih)
            </button>
            <button type="button" onclick="executeBulkDownloadSk()" style="background: #059669; color: white; border: none; padding: 7px 16px; border-radius: 6px; font-weight: 700; font-size: 0.8rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: background 0.2s;" onmouseover="this.style.background='#047857'" onmouseout="this.style.background='#059669'">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Download Terpilih (ZIP)
            </button>
            <button type="button" onclick="executeBulkDeleteSk()" style="background: #dc2626; color: white; border: none; padding: 7px 16px; border-radius: 6px; font-weight: 700; font-size: 0.8rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: background 0.2s;" onmouseover="this.style.background='#b91c1c'" onmouseout="this.style.background='#dc2626'">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                Hapus Terpilih
            </button>
        </div>
    </div>

    <form id="bulk-export-sk-form" action="{{ route('admin.sekretariat.sk.export-bulk') }}" method="POST" style="display:none;">
        @csrf
    </form>

    <form id="bulk-delete-sk-form" action="{{ route('admin.sekretariat.sk.bulk-delete') }}" method="POST" style="display:none;">
        @csrf
    </form>

    <form id="bulk-download-sk-form" action="{{ route('admin.sekretariat.sk.bulk-download') }}" method="POST" style="display:none;">
        @csrf
    </form>

    <div style="margin-top: 1rem;">
        {{ $sks->links() }}
    </div>

</div>

<!-- Create Modal (Standard & Professional) -->
<div class="modal-overlay" id="createModal" onclick="if(event.target===this) closeCreateModal()">
    <div class="modal-content-lg">
        <div class="modal-header-prof">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(255,255,255,0.15); display: flex; align-items: center; justify-content: center;">
                    <svg viewBox="0 0 24 24" width="20" height="20" stroke="white" fill="none" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                </div>
                <div>
                    <h3 style="font-size: 1.05rem; font-weight: 800; color: white; margin: 0;">Tambah Surat Keputusan (SK) Baru</h3>
                    <span style="font-size: 0.725rem; color: #94a3b8;">Isi detail ketetapan Surat Keputusan secara lengkap</span>
                </div>
            </div>
            <button type="button" onclick="closeCreateModal()" style="background: rgba(255,255,255,0.1); border: none; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer;">&times;</button>
        </div>

        <form action="{{ route('admin.sekretariat.sk.store') }}" method="POST">
            @csrf
            <div class="modal-body-prof">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                    <div class="form-group">
                        <label style="font-size: 0.8rem; font-weight: 700; color: var(--navy);">Nomor SK <span style="color: red;">*</span></label>
                        <input type="text" name="nomor_sk" class="form-control" placeholder="Contoh: SK/005/PNKT/2026" required style="font-size: 0.85rem; font-weight: 600;">
                    </div>

                    <div class="form-group">
                        <label style="font-size: 0.8rem; font-weight: 700; color: var(--navy);">Status SK <span style="color: red;">*</span></label>
                        <select name="status" class="form-control select2-basic" style="width: 100%;" required>
                            <option value="Aktif" selected>Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                        </select>
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label style="font-size: 0.8rem; font-weight: 700; color: var(--navy);">Judul Surat Keputusan <span style="color: red;">*</span></label>
                        <input type="text" name="judul_sk" class="form-control" placeholder="Judul penetapan SK..." required style="font-size: 0.85rem;">
                    </div>

                    <div class="form-group">
                        <label style="font-size: 0.8rem; font-weight: 700; color: var(--navy);">Tanggal Berlaku <span style="color: red;">*</span></label>
                        <input type="text" name="tanggal_berlaku" class="form-control datepicker" style="background: white; font-size: 0.85rem;" value="{{ date('Y-m-d') }}" placeholder="Pilih tanggal..." required>
                    </div>

                    <div class="form-group">
                        <label style="font-size: 0.8rem; font-weight: 700; color: var(--navy);">Tanggal Berakhir <span style="color: red;">*</span></label>
                        <input type="text" name="tanggal_berakhir" class="form-control datepicker" style="background: white; font-size: 0.85rem;" placeholder="Pilih tanggal berakhir..." required>
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label style="font-size: 0.8rem; font-weight: 700; color: var(--navy);">Link Tautan Dokumen (Google Drive)</label>
                        <input type="url" name="link_drive" class="form-control" placeholder="https://drive.google.com/file/d/..." style="font-size: 0.85rem;">
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label style="font-size: 0.8rem; font-weight: 700; color: var(--navy);">Keterangan Tambahan</label>
                        <textarea name="keterangan" class="form-control" style="height: 75px; font-size: 0.85rem;" placeholder="Catatan tambahan..."></textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer-prof">
                <button type="button" onclick="closeCreateModal()" class="btn-outline-secondary">Batal</button>
                <button type="submit" class="btn-solid-navy" style="font-weight: 700;" onclick="if(typeof Toast !== 'undefined') Toast.fire({ icon: 'success', title: 'Surat Keputusan (SK) sedang disimpan...' })">Simpan SK</button>
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
                    <h3 style="font-size: 1.05rem; font-weight: 800; color: white; margin: 0;">Edit Surat Keputusan (SK)</h3>
                    <span style="font-size: 0.725rem; color: #94a3b8;">Perbarui data Surat Keputusan</span>
                </div>
            </div>
            <button type="button" onclick="closeEditModal()" style="background: rgba(255,255,255,0.1); border: none; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer;">&times;</button>
        </div>

        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            
            <div class="modal-body-prof">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                    <div class="form-group">
                        <label style="font-size: 0.8rem; font-weight: 700; color: var(--navy);">Nomor SK <span style="color: red;">*</span></label>
                        <input type="text" name="nomor_sk" id="editNomorSk" class="form-control" required style="font-size: 0.85rem; font-weight: 600;">
                    </div>

                    <div class="form-group">
                        <label style="font-size: 0.8rem; font-weight: 700; color: var(--navy);">Status SK <span style="color: red;">*</span></label>
                        <select name="status" id="editStatus" class="form-control select2-basic" style="width: 100%;" required>
                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                        </select>
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label style="font-size: 0.8rem; font-weight: 700; color: var(--navy);">Judul Surat Keputusan <span style="color: red;">*</span></label>
                        <input type="text" name="judul_sk" id="editJudulSk" class="form-control" required style="font-size: 0.85rem;">
                    </div>

                    <div class="form-group">
                        <label style="font-size: 0.8rem; font-weight: 700; color: var(--navy);">Tanggal Berlaku <span style="color: red;">*</span></label>
                        <input type="text" name="tanggal_berlaku" id="editTanggalBerlaku" class="form-control datepicker" style="background: white; font-size: 0.85rem;" required>
                    </div>

                    <div class="form-group">
                        <label style="font-size: 0.8rem; font-weight: 700; color: var(--navy);">Tanggal Berakhir <span style="color: red;">*</span></label>
                        <input type="text" name="tanggal_berakhir" id="editTanggalBerakhir" class="form-control datepicker" style="background: white; font-size: 0.85rem;" required>
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label style="font-size: 0.8rem; font-weight: 700; color: var(--navy);">Link Tautan Dokumen (Google Drive)</label>
                        <input type="url" name="link_drive" id="editLinkDrive" class="form-control" style="font-size: 0.85rem;">
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label style="font-size: 0.8rem; font-weight: 700; color: var(--navy);">Keterangan Tambahan</label>
                        <textarea name="keterangan" id="editKeterangan" class="form-control" style="height: 75px; font-size: 0.85rem;"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer-prof">
                <button type="button" onclick="closeEditModal()" class="btn-outline-secondary">Batal</button>
                <button type="submit" class="btn-solid-navy" style="font-weight: 700;" onclick="if(typeof Toast !== 'undefined') Toast.fire({ icon: 'success', title: 'Surat Keputusan (SK) berhasil diperbarui...' })">Update SK</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Import SK -->
<div class="modal-overlay" id="modalImportSk" onclick="if(event.target===this) closeImportSkModal()">
    <div class="modal-content-lg">
        <div class="modal-header-prof" style="background: linear-gradient(135deg, #022648 0%, #01162f 100%); padding: 1.25rem 1.5rem; color: white;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: rgba(183, 131, 15, 0.2); display: flex; align-items: center; justify-content: center; border: 1px solid rgba(183, 131, 15, 0.4);">
                    <svg viewBox="0 0 24 24" width="22" height="22" stroke="#b7830f" fill="none" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                </div>
                <div>
                    <h3 style="font-size: 1.1rem; font-weight: 800; color: white; margin: 0;">Import / Bulk Upload Surat Keputusan (SK)</h3>
                    <span style="font-size: 0.775rem; color: #94a3b8;">Unggah berkas data SK secara massal menggunakan format Excel (.xls / .csv)</span>
                </div>
            </div>
            <button type="button" onclick="closeImportSkModal()" style="background: rgba(255,255,255,0.1); border: none; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 1.2rem;">&times;</button>
        </div>

        <form action="{{ route('admin.sekretariat.sk.import') }}" method="POST" id="formImportSk">
            @csrf
            <input type="hidden" name="sk_rows" id="importSkRowsInput">

            <div class="modal-body-prof" style="padding: 1.5rem;">
                <div style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; padding: 1.25rem; margin-bottom: 1.25rem;">
                    <div style="font-weight: 700; color: #022648; margin-bottom: 0.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                        <span style="font-size: 0.9rem;"><i class="fa fa-info-circle" style="color: #b7830f;"></i> Unduh Format Contoh Import SK</span>
                        <a href="{{ route('admin.sekretariat.sk.template-import') }}" onclick="Toast.fire({ icon: 'success', title: 'Mengunduh format contoh Excel SK...' })" style="background: #059669; color: white; padding: 7px 14px; border-radius: 6px; font-size: 0.775rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: background 0.2s;" onmouseover="this.style.background='#047857'" onmouseout="this.style.background='#059669'">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            Unduh Format Contoh (.xls)
                        </a>
                    </div>
                    <p style="font-size: 0.8125rem; color: #64748b; margin: 0; line-height: 1.5;">
                        Silakan unduh berkas contoh di atas untuk melihat susunan kolom: <strong>Nomor SK, Judul Surat Keputusan, Tanggal Berlaku, Tanggal Berakhir, Link Google Drive, Status, Keterangan</strong>.
                    </p>
                </div>

                <div class="form-group-full">
                    <label class="form-label" style="font-weight: 700; color: #022648; font-size: 0.875rem; margin-bottom: 0.5rem; display: block;">Pilih Berkas Excel / CSV (.xls, .xlsx, .csv)</label>
                    <div class="file-upload-zone" onclick="document.getElementById('importSkFile').click()" style="border: 2px dashed #b7830f; background: #fffdf5; padding: 2rem 1.5rem; text-align: center; border-radius: 8px; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#fff9e6'" onmouseout="this.style.background='#fffdf5'">
                        <input type="file" id="importSkFile" accept=".xls,.xlsx,.csv" onchange="handleImportSkFile(this)" style="display: none;">
                        <div style="pointer-events: none;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#b7830f" stroke-width="2" style="margin-bottom: 0.5rem;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                            <div style="font-weight: 700; color: #022648; font-size: 0.95rem; margin-bottom: 4px;" id="importFileLabel">Klik atau Tarik Berkas Excel ke Sini</div>
                            <span style="font-size: 0.775rem; color: #64748b;">Format yang didukung: .xls, .xlsx, .csv</span>
                        </div>
                    </div>
                </div>

                <div id="importSkPreviewContainer" style="display: none; margin-top: 1.25rem;">
                    <div style="font-weight: 700; color: #022648; font-size: 0.85rem; margin-bottom: 0.5rem;">
                        Pratinjau Data Terbaca (<span id="importSkCount">0</span> baris SK)
                    </div>
                    <div style="max-height: 200px; overflow-y: auto; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.775rem;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="background: #022648; color: white;">
                                    <th style="padding: 8px 10px; text-align: left;">No SK</th>
                                    <th style="padding: 8px 10px; text-align: left;">Judul SK</th>
                                    <th style="padding: 8px 10px; text-align: center;">Berlaku</th>
                                    <th style="padding: 8px 10px; text-align: center;">Status</th>
                                </tr>
                            </thead>
                            <tbody id="importSkPreviewTableBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal-footer-prof" style="padding: 1rem 1.5rem; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 0.75rem;">
                <button type="button" class="btn-outline-secondary" onclick="closeImportSkModal()">Batal</button>
                <button type="submit" id="btnSubmitImportSk" class="btn-solid-navy" style="background: #022648 !important; color: white !important; font-weight: 700;" disabled>
                    <i class="fa fa-upload"></i> Proses Import Massal
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
            flatpickr(".datepicker", { dateFormat: "Y-m-d", allowInput: true });
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
                    dropdown.classList.add('is-open');
                    const dropdownHeight = dropdown.offsetHeight || 200;
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

        document.addEventListener('click', function () {
            if (activeDropdown) { activeDropdown.classList.remove('is-open'); activeDropdown = null; }
        });
        window.addEventListener('scroll', function () {
            if (activeDropdown) { activeDropdown.classList.remove('is-open'); activeDropdown = null; }
        }, true);

        // Bulk Action Script for SK
        const checkAllSk = document.getElementById('check-all-sk');
        const checkSkItems = document.querySelectorAll('.check-sk-item');
        const bulkBarSk = document.getElementById('bulk-action-bar-sk');
        const countSkDisplay = document.getElementById('selected-sk-count');

        function updateSkBulkBar() {
            const checked = document.querySelectorAll('.check-sk-item:checked');
            if (countSkDisplay) countSkDisplay.innerText = checked.length;
            if (bulkBarSk) {
                bulkBarSk.style.display = checked.length > 0 ? 'flex' : 'none';
            }
        }

        if (checkAllSk) {
            checkAllSk.addEventListener('change', function () {
                checkSkItems.forEach(item => item.checked = this.checked);
                updateSkBulkBar();
            });
        }

        checkSkItems.forEach(item => {
            item.addEventListener('change', function () {
                if (checkAllSk) {
                    checkAllSk.checked = Array.from(checkSkItems).every(i => i.checked);
                }
                updateSkBulkBar();
            });
        });
    });

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
        document.getElementById('editForm').action = "/admin/sekretariat/sk/" + item.id;
        document.getElementById('editNomorSk').value = item.nomor_sk;
        document.getElementById('editJudulSk').value = item.judul_sk;
        document.getElementById('editTanggalBerlaku').value = item.tanggal_berlaku;
        document.getElementById('editTanggalBerakhir').value = item.tanggal_berakhir;
        document.getElementById('editStatus').value = item.status;
        document.getElementById('editLinkDrive').value = item.link_drive || '';
        document.getElementById('editKeterangan').value = item.keterangan || '';
        openModalById('editModal');
        if (typeof $.fn.select2 !== 'undefined') {
            $('#editModal .select2-basic').select2({ width: '100%', dropdownParent: $('#editModal') });
        }
    }
    function closeEditModal() { closeModalById('editModal'); }

    window.openImportSkModal = function() {
        openModalById('modalImportSk');
    };
    window.closeImportSkModal = function() {
        closeModalById('modalImportSk');
    };

    function confirmDeleteSk(id, nomor) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Hapus SK?',
                text: `Apakah Anda yakin ingin menghapus SK no. ${nomor}?`,
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
        } else if (confirm(`Apakah Anda yakin ingin menghapus SK no. ${nomor}?`)) {
            document.getElementById('delete-form-' + id).submit();
        }
    }

    function executeBulkDeleteSk() {
        const checked = document.querySelectorAll('.check-sk-item:checked');
        if (checked.length === 0) return;

        Swal.fire({
            title: 'Konfirmasi Hapus Massal SK',
            text: `Apakah Anda yakin ingin menghapus ${checked.length} Surat Keputusan yang dipilih? Berkas dokumen juga akan terhapus secara permanen.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus Semua!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('bulk-delete-sk-form');
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

    function executeBulkDownloadSk() {
        const checked = document.querySelectorAll('.check-sk-item:checked');
        if (checked.length === 0) return;

        if (typeof Toast !== 'undefined') {
            Toast.fire({ icon: 'info', title: 'Memproses kompresi berkas ZIP SK...' });
        }

        const form = document.getElementById('bulk-download-sk-form');
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

    function executeBulkExportSk() {
        const checked = document.querySelectorAll('.check-sk-item:checked');
        if (checked.length === 0) return;

        if (typeof Toast !== 'undefined') {
            Toast.fire({ icon: 'success', title: `Mengunduh ${checked.length} SK ke berkas Excel...` });
        }

        const form = document.getElementById('bulk-export-sk-form');
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

    function handleImportSkFile(input) {
        const file = input.files[0];
        if (!file) return;

        document.getElementById('importFileLabel').innerText = file.name;

        function processRows(rows) {
            const validRows = rows.filter(r => r.nomor_sk && r.judul_sk && r.nomor_sk !== '' && r.judul_sk !== '');

            if (validRows.length > 0) {
                document.getElementById('importSkRowsInput').value = JSON.stringify(validRows);
                document.getElementById('importSkCount').innerText = validRows.length + (validRows.length > 15 ? ' data — menampilkan 15 pertama' : ' data');
                const tbody = document.getElementById('importSkPreviewTableBody');
                tbody.innerHTML = '';
                validRows.slice(0, 15).forEach(r => {
                    tbody.innerHTML += `<tr>
                        <td style="padding:6px 8px;border-bottom:1px solid #e2e8f0;"><strong>${r.nomor_sk}</strong></td>
                        <td style="padding:6px 8px;border-bottom:1px solid #e2e8f0;">${r.judul_sk}</td>
                        <td style="padding:6px 8px;border-bottom:1px solid #e2e8f0;text-align:center;">${r.tanggal_berlaku}</td>
                        <td style="padding:6px 8px;border-bottom:1px solid #e2e8f0;text-align:center;"><span style="color:#059669;font-weight:700;">${r.status}</span></td>
                    </tr>`;
                });
                document.getElementById('importSkPreviewContainer').style.display = 'block';
                document.getElementById('btnSubmitImportSk').disabled = false;
            } else {
                if (typeof Toast !== 'undefined') {
                    Toast.fire({ icon: 'error', title: 'Tidak dapat membaca baris data SK dari berkas tersebut.' });
                }
                document.getElementById('btnSubmitImportSk').disabled = true;
            }
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
                        if (rowStr.includes('template import') || rowStr.includes('petunjuk:') || rowStr.includes('nomor sk')) return;

                        let nomor = '';
                        let judul = '';
                        let tgl1 = '2026-08-01';
                        let tgl2 = '2029-08-01';
                        let link = '';
                        let status = 'Aktif';
                        let ket = '';

                        const firstCell = String(rowArray[0] || '').trim();
                        if (/^\d+$/.test(firstCell) || (rowArray.length >= 3 && String(rowArray[1] || '').trim() !== '')) {
                            nomor = String(rowArray[1] || '').trim();
                            judul = String(rowArray[2] || '').trim();
                            tgl1 = String(rowArray[3] || tgl1).trim();
                            tgl2 = String(rowArray[4] || tgl2).trim();
                            link = String(rowArray[5] || '').trim();
                            status = String(rowArray[6] || status).trim();
                            ket = String(rowArray[7] || '').trim();
                        } else {
                            nomor = String(rowArray[0] || '').trim();
                            judul = String(rowArray[1] || '').trim();
                            tgl1 = String(rowArray[2] || tgl1).trim();
                            tgl2 = String(rowArray[3] || tgl2).trim();
                            link = String(rowArray[4] || '').trim();
                            status = String(rowArray[5] || status).trim();
                            ket = String(rowArray[6] || '').trim();
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

                        if (nomor && judul && nomor.toLowerCase() !== 'nomor sk' && nomor.toLowerCase() !== 'no') {
                            rows.push({
                                nomor_sk: nomor,
                                judul_sk: judul,
                                tanggal_berlaku: normDate(tgl1, '2026-08-01'),
                                tanggal_berakhir: normDate(tgl2, '2029-08-01'),
                                link_drive: link,
                                status: (status === 'Tidak Aktif' ? 'Tidak Aktif' : 'Aktif'),
                                keterangan: ket
                            });
                        }
                    });
                }
            } catch (err) {
                console.warn('SheetJS read failed, fallback to text:', err);
            }

            if (rows.length > 0) {
                processRows(rows);
            } else {
                const textReader = new FileReader();
                textReader.onload = function(evt) {
                    const text = evt.target.result;
                    if (text.includes('<tr')) {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(text, 'text/html');
                        const trs = doc.querySelectorAll('table tr');
                        trs.forEach(tr => {
                            const tds = Array.from(tr.querySelectorAll('td, th')).map(t => t.innerText.trim());
                            if (tds.length >= 2) {
                                const c1 = tds[0] || '';
                                const c2 = tds[1] || '';
                                const c3 = tds[2] || '';
                                
                                let nomor = '', judul = '', t1 = '', t2 = '', lk = '', st = 'Aktif', kt = '';
                                if (/^\d+$/.test(c1) && c2 && c3) {
                                    nomor = c2; judul = c3; t1 = tds[3] || ''; t2 = tds[4] || ''; lk = tds[5] || ''; st = tds[6] || 'Aktif'; kt = tds[7] || '';
                                } else if (c1 && c2) {
                                    nomor = c1; judul = c2; t1 = tds[2] || ''; t2 = tds[3] || ''; lk = tds[4] || ''; st = tds[5] || 'Aktif'; kt = tds[6] || '';
                                }

                                if (nomor && judul && nomor.toLowerCase() !== 'nomor sk' && nomor.toLowerCase() !== 'no') {
                                    rows.push({
                                        nomor_sk: nomor,
                                        judul_sk: judul,
                                        tanggal_berlaku: t1 || '2026-08-01',
                                        tanggal_berakhir: t2 || '2029-08-01',
                                        link_drive: lk,
                                        status: (st === 'Tidak Aktif' ? 'Tidak Aktif' : 'Aktif'),
                                        keterangan: kt
                                    });
                                }
                            }
                        });
                    }
                    processRows(rows);
                };
                textReader.readAsText(file);
            }
        };
        reader.readAsArrayBuffer(file);
    }
</script>
@endpush