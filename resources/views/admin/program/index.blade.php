@extends('admin.layouts.admin-layout')

@section('title', 'Kelola Program (CSR & Bidang)')
@section('page-title', 'Kelola Program')

@push('styles')
<style>
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
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
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
    .stat-card.perencanaan::before { background: var(--amber); }
    .stat-card.berjalan::before { background: var(--blue); }
    .stat-card.selesai::before { background: var(--green); }

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
        padding: 0.6rem 1.25rem;
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

    /* Table Container */
    .table-container {
        background: white;
        border-radius: var(--radius-lg);
        border: 1px solid var(--gray-200);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .table-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--gray-200);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .table-title {
        font-size: 1.125rem;
        font-weight: 800;
        color: var(--navy);
        margin: 0;
        font-family: 'Montserrat', sans-serif;
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

    .badge {
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .badge-csr {
        background: #e0f2fe;
        color: #0369a1;
        border: 1px solid #bae6fd;
    }

    .badge-bidang {
        background: #fef3c7;
        color: #b45309;
        border: 1px solid #fde68a;
    }

    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.35rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-perencanaan { background: #fef3c7; color: #d97706; }
    .status-berjalan { background: #dbeafe; color: #1d4ed8; }
    .status-selesai { background: #d1fae5; color: #065f46; }

    .program-title {
        font-weight: 700;
        color: var(--navy);
        font-size: 0.95rem;
        margin-bottom: 4px;
        display: block;
        text-decoration: none;
    }

    .program-title:hover {
        color: var(--navy-light);
        text-decoration: underline;
    }

    .program-mitra {
        font-size: 0.8rem;
        color: var(--gray-500);
    }

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

    .aksi-item.aksi-edit:hover { color: var(--navy); }
    .aksi-item.aksi-status:hover { color: var(--green); }
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
</style>
@endpush

@section('content')
<div class="admin-ui-scope" style="padding-top: 0.5rem;">

    <!-- Stat Cards Top Benchmark -->
    <div class="stat-cards-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
            </div>
            <div class="stat-info">
                <h4>Total Program</h4>
                <div class="value">{{ number_format($stats['total'] ?? $programs->count()) }}</div>
            </div>
        </div>

        <div class="stat-card perencanaan">
            <div class="stat-icon" style="background: #fffbeb; color: #d97706;">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            </div>
            <div class="stat-info">
                <h4>Perencanaan</h4>
                <div class="value">{{ number_format($stats['perencanaan'] ?? $programs->where('status', 'Perencanaan')->count()) }}</div>
            </div>
        </div>

        <div class="stat-card berjalan">
            <div class="stat-icon" style="background: #eff6ff; color: #2563eb;">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
            </div>
            <div class="stat-info">
                <h4>Berjalan</h4>
                <div class="value">{{ number_format($stats['berjalan'] ?? $programs->where('status', 'Berjalan')->count()) }}</div>
            </div>
        </div>

        <div class="stat-card selesai">
            <div class="stat-icon" style="background: #ecfdf5; color: #059669;">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            </div>
            <div class="stat-info">
                <h4>Selesai</h4>
                <div class="value">{{ number_format($stats['selesai'] ?? $programs->where('status', 'Selesai')->count()) }}</div>
            </div>
        </div>
    </div>

    {{-- Filter Box --}}
    <div class="filter-card">
        <form method="GET" action="{{ route('admin.program.index') }}">
            <div class="filter-grid">
                <div class="form-group">
                    <label for="search">Cari Nama / Mitra / PIC</label>
                    <input type="text" name="search" id="search" class="form-control" placeholder="Ketik kata kunci..." value="{{ request('search') }}">
                </div>

                <div class="form-group">
                    <label for="kategori">Kategori</label>
                    <select name="kategori" id="kategori" class="form-control select2-basic">
                        <option value="">-- Semua Kategori --</option>
                        <option value="CSR" {{ request('kategori') == 'CSR' ? 'selected' : '' }}>CSR</option>
                        <option value="Bidang" {{ request('kategori') == 'Bidang' ? 'selected' : '' }}>Bidang</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control select2-basic">
                        <option value="">-- Semua Status --</option>
                        <option value="Perencanaan" {{ request('status') == 'Perencanaan' ? 'selected' : '' }}>Perencanaan</option>
                        <option value="Berjalan" {{ request('status') == 'Berjalan' ? 'selected' : '' }}>Berjalan</option>
                        <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>

                <div style="display: flex; gap: 0.5rem; align-items: flex-end;">
                    <button type="submit" class="btn-solid-navy" style="white-space: nowrap;">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        Filter
                    </button>
                    @if(request()->anyFilled(['search', 'kategori', 'status']))
                        <a href="{{ route('admin.program.index') }}" class="btn-outline-secondary" style="white-space: nowrap;">Reset</a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- Main Table Container --}}
    <div class="table-container">
        <div class="table-header">
            <h3 class="table-title">Daftar Program Kerja ({{ $programs->count() }})</h3>
            
            @if(auth()->guard('admin')->user()->isSuperAdmin() || auth()->guard('admin')->user()->isPNKT())
            <a href="{{ route('admin.program.create') }}" class="btn-solid-navy">
                <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" fill="none" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Tambah Program Baru
            </a>
            @endif
        </div>

        @if(auth()->guard('admin')->user()->isSuperAdmin() || auth()->guard('admin')->user()->isPNKT())
            @if($programs->count() > 0)
                <div style="padding: 1rem 1.5rem; border-bottom: 1px solid var(--gray-200); display: none;" id="bulk-action-container">
                    <form id="bulk-delete-form" action="{{ route('admin.program.bulk-delete') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" id="btn-bulk-delete" class="btn-bulk">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                                <path d="M3 6h18m-2 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                            Hapus Terpilih (<span id="selected-count">0</span>)
                        </button>
                    </form>
                </div>
            @endif
        @endif

        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        @if(auth()->guard('admin')->user()->isSuperAdmin() || auth()->guard('admin')->user()->isPNKT())
                        <th width="40px" style="text-align: center;"><input type="checkbox" id="check-all" style="cursor: pointer;"></th>
                        @endif
                        <th>Nama Program</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Periode</th>
                        <th>PIC</th>
                        @if(auth()->guard('admin')->user()->isSuperAdmin() || auth()->guard('admin')->user()->isPNKT())
                        <th style="text-align: center; width: 80px;">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($programs as $program)
                    <tr>
                        @if(auth()->guard('admin')->user()->isSuperAdmin() || auth()->guard('admin')->user()->isPNKT())
                        <td style="text-align: center;">
                            <input type="checkbox" name="ids[]" value="{{ $program->id }}" class="check-item" form="bulk-delete-form" style="cursor: pointer;">
                        </td>
                        @endif
                        <td>
                            <a href="{{ route('admin.program.show', $program->id) }}" class="program-title">
                                {{ $program->nama_program }}
                            </a>
                            @if($program->kategori == 'CSR' && $program->mitra)
                                <div class="program-mitra">Mitra: {{ $program->mitra }}</div>
                            @elseif($program->kategori == 'Bidang' && $program->jabatan)
                                <div class="program-mitra">Bidang: {{ $program->jabatan->nama_jabatan }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $program->kategori == 'CSR' ? 'badge-csr' : 'badge-bidang' }}">
                                {{ $program->kategori }}
                            </span>
                        </td>
                        <td>
                            <span class="badge-status status-{{ strtolower($program->status) }}">
                                ● {{ $program->status }}
                            </span>
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($program->periode_mulai)->format('d M Y') }} - <br>
                            {{ \Carbon\Carbon::parse($program->periode_selesai)->format('d M Y') }}
                        </td>
                        <td><strong>{{ $program->pic }}</strong></td>
                        @if(auth()->guard('admin')->user()->isSuperAdmin() || auth()->guard('admin')->user()->isPNKT())
                        <td style="text-align: center;">
                            <!-- Action Dropdown Trigger (⋮) -->
                            <div class="aksi-wrapper">
                                <button type="button" class="btn-aksi-trigger" data-target="dropdown-program-{{ $program->id }}" aria-label="Menu Aksi">
                                    <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                        <circle cx="12" cy="5" r="1.75"></circle>
                                        <circle cx="12" cy="12" r="1.75"></circle>
                                        <circle cx="12" cy="19" r="1.75"></circle>
                                    </svg>
                                </button>

                                <div class="aksi-dropdown" id="dropdown-program-{{ $program->id }}">
                                    @if($program->kategori == 'Bidang')
                                        @if($program->status == 'Perencanaan')
                                            <form action="{{ route('admin.program.update-status', $program->id) }}" method="POST" id="status-form-{{ $program->id }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="Berjalan">
                                                <button type="submit" class="aksi-item aksi-status">
                                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                                                    Mulai Program
                                                </button>
                                            </form>
                                            <div class="aksi-divider"></div>
                                        @elseif($program->status == 'Berjalan')
                                            <button type="button" class="aksi-item aksi-status" onclick="confirmSelesai({{ $program->id }})">
                                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                                Selesaikan Program
                                            </button>
                                            <form action="{{ route('admin.program.update-status', $program->id) }}" method="POST" id="selesai-form-{{ $program->id }}" style="display:none;">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="Selesai">
                                            </form>
                                            <div class="aksi-divider"></div>
                                        @endif
                                    @endif
                                    
                                    <a href="{{ route('admin.program.edit', $program->id) }}" class="aksi-item aksi-edit">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        Edit Program
                                    </a>
                                    
                                    <div class="aksi-divider"></div>
                                    
                                    <button type="button" class="aksi-item aksi-delete" onclick="confirmDelete({{ $program->id }}, '{{ addslashes($program->nama_program) }}')">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        Hapus Program
                                    </button>
                                    <form id="delete-form-{{ $program->id }}" action="{{ route('admin.program.destroy', $program->id) }}" method="POST" style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 3rem; color: var(--gray-500);">
                            Belum ada data program yang ditambahkan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
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

        // Bulk Delete Logic
        const checkAll = document.getElementById('check-all');
        const checkItems = document.querySelectorAll('.check-item');
        const bulkContainer = document.getElementById('bulk-action-container');
        const btnBulkDelete = document.getElementById('btn-bulk-delete');
        const selectedCountSpan = document.getElementById('selected-count');
        const bulkDeleteForm = document.getElementById('bulk-delete-form');

        function updateBulkDeleteButton() {
            const checkedCount = document.querySelectorAll('.check-item:checked').length;
            if(selectedCountSpan) selectedCountSpan.textContent = checkedCount;
            if (checkedCount > 0) {
                if(bulkContainer) bulkContainer.style.display = 'block';
            } else {
                if(bulkContainer) bulkContainer.style.display = 'none';
                if(checkAll) checkAll.checked = false;
            }
        }

        if (checkAll) {
            checkAll.addEventListener('change', function() {
                checkItems.forEach(item => {
                    item.checked = this.checked;
                });
                updateBulkDeleteButton();
            });
        }

        checkItems.forEach(item => {
            item.addEventListener('change', updateBulkDeleteButton);
        });

        if (btnBulkDelete) {
            btnBulkDelete.addEventListener('click', function() {
                const checkedCount = document.querySelectorAll('.check-item:checked').length;
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Hapus ' + checkedCount + ' Program Terpilih?',
                        text: "Data program yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#f3f4f6',
                        confirmButtonText: 'Ya, Hapus Semua!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            bulkDeleteForm.submit();
                        }
                    });
                } else if(confirm('Yakin hapus ' + checkedCount + ' program terpilih?')) {
                    bulkDeleteForm.submit();
                }
            });
        }
    });

    function confirmDelete(id, name) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Hapus Program?',
                text: `Apakah Anda yakin ingin menghapus program "${name}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#f3f4f6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        } else if (confirm(`Apakah Anda yakin ingin menghapus program "${name}"?`)) {
            document.getElementById('delete-form-' + id).submit();
        }
    }

    function confirmSelesai(id) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Selesaikan Program?',
                text: "Apakah Anda yakin program ini sudah benar-benar selesai?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#059669',
                cancelButtonColor: '#f3f4f6',
                confirmButtonText: 'Ya, Selesaikan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('selesai-form-' + id).submit();
                }
            });
        } else if (confirm("Apakah Anda yakin program ini sudah benar-benar selesai?")) {
            document.getElementById('selesai-form-' + id).submit();
        }
    }
</script>
@endpush
