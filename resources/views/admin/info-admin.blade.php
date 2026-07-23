@extends('admin.layouts.admin-layout')

@section('title', 'Manajemen User & Role - SIKTN Admin')
@section('page-title', 'Manajemen User & Role')

@php
    $activeMenu = 'info-admin';
@endphp

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); min-width: 220px;
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
        grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
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
    .stat-card.active::before { background: var(--green); }
    .stat-card.super::before { background: var(--gold); }
    .stat-card.regional::before { background: var(--blue); }

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

    /* Main Container Card */
    .card-box {
        background: white;
        border-radius: var(--radius-lg);
        border: 1px solid var(--gray-200);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
    }

    .action-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .search-filter-group {
        display: flex;
        gap: 0.75rem;
        align-items: center;
        flex-wrap: wrap;
    }

    .form-control {
        padding: 0.55rem 0.875rem;
        border-radius: var(--radius-md);
        border: 1px solid var(--gray-300);
        font-size: 0.875rem;
        outline: none;
        background: white;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        border-color: var(--navy);
        box-shadow: 0 0 0 3px rgba(2, 38, 72, 0.1);
    }

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

    /* Table Styling */
    .table-container {
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--gray-200);
        overflow: visible;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th {
        background: var(--gray-50);
        padding: 0.875rem 1rem;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--gray-700);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid var(--gray-200);
    }

    .table td {
        padding: 1rem;
        border-bottom: 1px solid var(--gray-100);
        font-size: 0.875rem;
        color: var(--gray-900);
        vertical-align: middle;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.35rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 700;
    }

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
        min-width: 190px;
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
    .aksi-item.aksi-reset:hover { color: var(--gold); }
    .aksi-item.aksi-delete:hover { color: var(--red); background: #fef2f2; }

    .aksi-divider {
        height: 1px;
        background: var(--gray-200);
        margin: 4px 0;
    }

    /* Modal Overlay & Form */
    .modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15, 23, 42, 0.6);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 10000;
        padding: 1rem;
    }
    
    .modal-overlay.active {
        display: flex;
    }

    .modal-content-card {
        background: white;
        border-radius: var(--radius-lg);
        max-width: 460px;
        width: 100%;
        padding: 1.75rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush

@section('content')
<div class="admin-ui-scope">

    {{-- Alert Credential Baru --}}
    @if(session('created_credentials'))
    <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: var(--radius-lg); padding: 1.25rem; margin-bottom: 1.5rem;">
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 0.5rem;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#047857" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            <h4 style="margin: 0; color: #047857; font-weight: 700;">Akun Admin Baru Berhasil Dibuat</h4>
        </div>
        <p style="margin: 0 0 0.75rem 0; color: #065f46; font-size: 0.875rem;">Silakan catat informasi kredensial login di bawah ini untuk diserahkan ke pengguna:</p>
        <div style="background: white; padding: 0.875rem 1rem; border-radius: 8px; border: 1px solid #a7f3d0; font-family: monospace; font-size: 0.9rem;">
            <div><strong>Nama:</strong> {{ session('created_credentials')['name'] }}</div>
            <div><strong>Role:</strong> {{ session('created_credentials')['role'] }}</div>
            <div><strong>Username:</strong> {{ session('created_credentials')['username'] }}</div>
            <div><strong>Password:</strong> {{ session('created_credentials')['password'] }}</div>
        </div>
    </div>
    @endif

    <!-- Summary Stat Cards Benchmark -->
    <div class="stat-cards-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </div>
            <div class="stat-info">
                <h4>Total Administrator</h4>
                <div class="value">{{ number_format($totalAdmins ?? count($admins)) }}</div>
            </div>
        </div>

        <div class="stat-card active">
            <div class="stat-icon" style="background: #ecfdf5; color: #059669;">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            </div>
            <div class="stat-info">
                <h4>Admin Aktif</h4>
                <div class="value">{{ number_format($activeAdmins ?? 0) }}</div>
            </div>
        </div>

        <div class="stat-card super">
            <div class="stat-icon" style="background: #fffbeb; color: #b7830f;">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
            </div>
            <div class="stat-info">
                <h4>Super Admin</h4>
                <div class="value">{{ number_format($superAdminCount ?? 0) }}</div>
            </div>
        </div>

        <div class="stat-card regional">
            <div class="stat-icon" style="background: #eff6ff; color: #2563eb;">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"></path><path d="M2 12h20"></path></svg>
            </div>
            <div class="stat-info">
                <h4>Admin Regional (PPKT/PKKT)</h4>
                <div class="value">{{ number_format($regionalAdmins ?? 0) }}</div>
            </div>
        </div>
    </div>

    <!-- Main Container Card -->
    <div class="card-box">
        <div class="action-bar">
            <form action="{{ route('admin.info-admin') }}" method="GET" class="search-filter-group">
                <select name="role" class="form-control select2-basic" style="min-width: 220px;" onchange="this.form.submit()">
                    <option value="">Semua Role / Kategori</option>
                    <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    <option value="pimpinan" {{ request('role') == 'pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                    <option value="pnkt" {{ request('role') == 'pnkt' ? 'selected' : '' }}>PNKT (Sekretariat Nasional)</option>
                    <option value="ppkt" {{ request('role') == 'ppkt' ? 'selected' : '' }}>PPKT (Sekretariat Provinsi)</option>
                    <option value="pkkt" {{ request('role') == 'pkkt' ? 'selected' : '' }}>PKKT (Sekretariat Kab/Kota)</option>
                </select>

                <input type="text" name="search" class="form-control" style="min-width: 240px;" placeholder="Cari nama, email, username..." value="{{ request('search') }}">
                
                <button type="submit" class="btn-solid-navy" style="padding: 0.55rem 1rem;">Filter</button>
                @if(request()->anyFilled(['role', 'search']))
                    <a href="{{ route('admin.info-admin') }}" class="btn-outline-secondary" style="padding: 0.55rem 1rem;">Reset</a>
                @endif
            </form>

            <div style="display: flex; gap: 0.75rem;">
                <a href="{{ route('admin.export-admin', request()->query()) }}" class="btn-outline-secondary" onclick="Toast.fire({ icon: 'success', title: 'File Excel Data Administrator sedang diunduh...' })">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                    Export Excel
                </a>

                <a href="{{ route('admin.create-admin') }}" class="btn-solid-navy">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Tambah Admin Baru
                </a>
            </div>
        </div>

        {{-- Table List Admin --}}
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>NAMA ADMINISTRATOR</th>
                        <th>USERNAME & EMAIL</th>
                        <th>ROLE / KATEGORI</th>
                        <th>DOMISILI WILAYAH</th>
                        <th>STATUS AKUN</th>
                        <th style="text-align: center; width: 80px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($admins as $item)
                    <tr>
                        <td>
                            <div style="font-weight: 700; color: var(--navy);">{{ $item->name }}</div>
                            @if($item->no_hp)
                                <div style="font-size: 0.8rem; color: var(--gray-500); display: inline-flex; align-items: center; gap: 4px; margin-top: 2px;">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                    {{ $item->no_hp }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <div style="font-weight: 600;">{{ $item->username }}</div>
                            <div style="font-size: 0.8rem; color: var(--gray-500);">{{ $item->email }}</div>
                        </td>
                        <td>
                            <span style="font-size: 0.75rem; font-weight: 700; padding: 4px 10px; border-radius: 6px; background: var(--gray-100); color: var(--navy);">
                                {{ $item->role_display_name }}
                            </span>
                        </td>
                        <td>{{ $item->domisili ?? 'Nasional' }}</td>
                        <td>
                            <form action="{{ route('admin.toggle-active-admin', $item->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                @if($item->is_active ?? true)
                                    <button type="button" class="status-badge approved" style="border:none; cursor:pointer;" title="Klik untuk Nonaktifkan Akun" onclick="confirmToggleActive(this.form, true, '{{ addslashes($item->name) }}')">
                                        ● Aktif
                                    </button>
                                @else
                                    <button type="button" class="status-badge rejected" style="border:none; cursor:pointer;" title="Klik untuk Aktifkan Akun" onclick="confirmToggleActive(this.form, false, '{{ addslashes($item->name) }}')">
                                        ● Nonaktif
                                    </button>
                                @endif
                            </form>
                        </td>
                        <td style="text-align: center;">
                            <!-- Floating Action Dropdown Trigger (⋮) -->
                            <div class="aksi-wrapper">
                                <button type="button" class="btn-aksi-trigger" data-target="dropdown-admin-{{ $item->id }}" aria-label="Menu Aksi">
                                    <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                        <circle cx="12" cy="5" r="1.75"></circle>
                                        <circle cx="12" cy="12" r="1.75"></circle>
                                        <circle cx="12" cy="19" r="1.75"></circle>
                                    </svg>
                                </button>

                                <div class="aksi-dropdown" id="dropdown-admin-{{ $item->id }}">
                                    <button type="button" class="aksi-item aksi-reset" onclick="openResetPasswordModal({{ $item->id }}, '{{ addslashes($item->name) }}')">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                        Reset Password
                                    </button>

                                    <a href="{{ route('admin.edit-admin', $item->id) }}" class="aksi-item aksi-edit">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        Edit Admin
                                    </a>

                                    @if($item->id !== auth()->guard('admin')->user()->id)
                                        <div class="aksi-divider"></div>
                                        <button type="button" class="aksi-item aksi-delete" onclick="triggerDeleteAdmin({{ $item->id }}, '{{ addslashes($item->name) }}')">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                            Hapus Admin
                                        </button>
                                        <form id="delete-form-{{ $item->id }}" action="{{ route('admin.delete-admin', $item->id) }}" method="POST" style="display:none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 2.5rem; color: var(--gray-500);">Belum ada data admin terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1.25rem;">
            {{ $admins->links() }}
        </div>
    </div>
</div>

{{-- Modal Reset Password --}}
<div class="modal-overlay" id="resetPasswordModal">
    <div class="modal-content-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
            <h3 style="color: var(--navy); margin: 0; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; font-size: 1.1rem;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                Reset Password Admin
            </h3>
            <button type="button" onclick="closeResetPasswordModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--gray-500);">&times;</button>
        </div>

        <form id="resetPasswordForm" method="POST">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.4rem; color: var(--gray-700);">Nama Administrator</label>
                <input type="text" id="modalAdminName" class="form-control" style="width: 100%; background: var(--gray-100);" readonly>
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.4rem; color: var(--gray-700);">Password Baru (Minimal 8 Karakter)</label>
                <input type="password" name="password" class="form-control" style="width: 100%;" placeholder="Masukkan password baru..." required minlength="8">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" class="btn-outline-secondary" onclick="closeResetPasswordModal()">Batal</button>
                <button type="submit" class="btn-solid-navy">Simpan Password</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Init Select2 Basic
        if (typeof $ !== 'undefined' && $.fn.select2) {
            $('.select2-basic').select2({
                minimumResultsForSearch: -1,
                width: 'resolve'
            }).on('change', function () {
                this.form.submit();
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
                    dropdown.style.left = (rect.right - 190) + 'px';
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

    function openResetPasswordModal(id, name) {
        document.getElementById('modalAdminName').value = name;
        document.getElementById('resetPasswordForm').action = "/admin/reset-password-admin/" + id;
        document.getElementById('resetPasswordModal').classList.add('active');
    }

    function closeResetPasswordModal() {
        document.getElementById('resetPasswordModal').classList.remove('active');
    }

    function confirmToggleActive(form, isActive, name) {
        const actionText = isActive ? 'Nonaktifkan' : 'Aktifkan';
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: `${actionText} Akun Admin?`,
                text: `Apakah Anda yakin ingin me-${actionText.toLowerCase()} akun admin ${name}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: isActive ? '#dc2626' : '#059669',
                cancelButtonColor: '#f3f4f6',
                confirmButtonText: `Ya, ${actionText}`,
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        } else if (confirm(`Apakah Anda yakin ingin me-${actionText.toLowerCase()} akun admin ${name}?`)) {
            form.submit();
        }
    }

    function triggerDeleteAdmin(id, name) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Hapus Akun Admin?',
                text: `Apakah Anda yakin ingin menghapus permanen akun admin ${name}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#f3f4f6',
                confirmButtonText: 'Ya, Hapus Permanen',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        } else if (confirm(`Yakin ingin menghapus permanen akun admin ${name}?`)) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endpush