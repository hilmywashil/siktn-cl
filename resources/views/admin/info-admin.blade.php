{{-- resources/views/admin/info-admin.blade.php --}}
@extends('admin.layouts.admin-layout')

@section('title', 'Manajemen User & Role - SIKTN Admin')
@section('page-title', 'Manajemen User & Role')

@php
    $activeMenu = 'info-admin';
@endphp

@push('styles')
<style>
    .card-box {
        background: white;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
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
        padding: 0.5rem 0.875rem;
        border-radius: 8px;
        border: 1px solid #d1d5db;
        font-size: 0.875rem;
        outline: none;
        background: white;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s;
        border: 1px solid transparent;
        text-decoration: none;
    }

    .btn-primary {
        background: #0a2540;
        color: white;
    }

    .btn-primary:hover {
        background: #0d3154;
    }

    .btn-secondary {
        background: #f3f4f6;
        color: #374151;
        border-color: #e5e7eb;
    }

    .btn-secondary:hover {
        background: #e5e7eb;
    }

    .btn-danger {
        background: #fef2f2;
        color: #dc2626;
        border-color: #fecaca;
    }

    .btn-danger:hover {
        background: #fee2e2;
    }

    .btn-warning {
        background: #fffbeb;
        color: #d97706;
        border-color: #fde68a;
    }

    .btn-warning:hover {
        background: #fef3c7;
    }

    .btn-info {
        background: #e0f2fe;
        color: #0369a1;
        border-color: #bae6fd;
    }

    .btn-info:hover {
        background: #bae6fd;
    }

    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.8125rem;
    }

    .table-container {
        background: white;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th {
        background: #f9fafb;
        padding: 0.875rem 1rem;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 700;
        color: #4b5563;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid #e5e7eb;
    }

    .table td {
        padding: 1rem;
        border-bottom: 1px solid #f3f4f6;
        font-size: 0.875rem;
        color: #1f2937;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .status-badge.approved { background: #d1fae5; color: #059669; }
    .status-badge.rejected { background: #fee2e2; color: #dc2626; }
    .status-badge.pending { background: #fef3c7; color: #d97706; }

    /* Modal Overlay & Card */
    .modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15, 23, 42, 0.6);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 999;
        padding: 1rem;
    }
    
    .modal-overlay.active {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 12px;
        max-width: 450px;
        width: 100%;
        padding: 1.5rem;
        max-height: 90vh;
        overflow-y: auto;
    }
</style>
@endpush

@section('content')
<div style="padding: 0.5rem 0;">

    {{-- Kredensial Baru Dibuat Flash Banner --}}
    @if(session('created_credentials'))
    <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 12px; padding: 1.25rem; margin-bottom: 1.5rem;">
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

    <div class="card-box">
        <div class="action-bar">
            <form action="{{ route('admin.info-admin') }}" method="GET" class="search-filter-group">
                <select name="role" class="form-control select2-basic" style="width: 220px;" onchange="this.form.submit()">
                    <option value="">Semua Role / Kategori</option>
                    <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    <option value="pimpinan" {{ request('role') == 'pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                    <option value="pnkt" {{ request('role') == 'pnkt' ? 'selected' : '' }}>PNKT (Sekretariat Nasional)</option>
                    <option value="ppkt" {{ request('role') == 'ppkt' ? 'selected' : '' }}>PPKT (Sekretariat Provinsi)</option>
                    <option value="pkkt" {{ request('role') == 'pkkt' ? 'selected' : '' }}>PKKT (Sekretariat Kab/Kota)</option>
                </select>

                <input type="text" name="search" class="form-control" style="width: 220px;" placeholder="Cari nama / email / username..." value="{{ request('search') }}">
                
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                @if(request()->anyFilled(['role', 'search']))
                    <a href="{{ route('admin.info-admin') }}" class="btn btn-secondary btn-sm">Reset</a>
                @endif
            </form>

            <div style="display: flex; gap: 0.5rem;">
                <a href="{{ route('admin.export-admin', request()->query()) }}" class="btn btn-secondary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                    Export Excel
                </a>

                <a href="{{ route('admin.create-admin') }}" class="btn btn-primary">
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
                        <th style="text-align: right;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($admins as $item)
                    <tr>
                        <td>
                            <div style="font-weight: 700; color: #0a2540;">{{ $item->name }}</div>
                            @if($item->no_hp)
                                <div style="font-size: 0.8rem; color: #6b7280; display: inline-flex; align-items: center; gap: 4px; margin-top: 2px;">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                    {{ $item->no_hp }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <div style="font-weight: 600;">{{ $item->username }}</div>
                            <div style="font-size: 0.8rem; color: #6b7280;">{{ $item->email }}</div>
                        </td>
                        <td>
                            <span style="font-size: 0.75rem; font-weight: 700; padding: 4px 10px; border-radius: 6px; background: #f3f4f6; color: #0a2540;">
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
                        <td style="text-align: right;">
                            <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                {{-- Reset Password Modal Trigger (12c) --}}
                                <button type="button" class="btn btn-warning btn-sm" onclick="openResetPasswordModal({{ $item->id }}, '{{ addslashes($item->name) }}')" title="Reset Password Admin">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                    Reset Password
                                </button>

                                {{-- Edit Admin --}}
                                <a href="{{ route('admin.edit-admin', $item->id) }}" class="btn btn-info btn-sm">Edit</a>

                                {{-- Delete Admin --}}
                                @if($item->id !== auth()->guard('admin')->user()->id)
                                    <form action="{{ route('admin.delete-admin', $item->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDeleteAdmin(this.form, '{{ addslashes($item->name) }}')">Hapus</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 2rem; color: #6b7280;">Belum ada data admin terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1rem;">
            {{ $admins->links() }}
        </div>
    </div>
</div>

{{-- Reset Password Modal (12c) --}}
<div class="modal-overlay" id="resetPasswordModal">
    <div class="modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 style="color: #0a2540; margin: 0; font-weight: 700; display: inline-flex; align-items: center; gap: 8px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                Reset Password Admin
            </h3>
            <button type="button" onclick="closeResetPasswordModal()" style="background: none; border: none; font-size: 1.25rem; cursor: pointer; color: #6b7280;">&times;</button>
        </div>

        <form id="resetPasswordForm" method="POST">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.25rem; color: #374151;">Nama Administrator</label>
                <input type="text" id="modalAdminName" class="form-control" style="width: 100%; background: #f3f4f6;" readonly>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.375rem; color: #374151;">Password Baru (Minimal 8 Karakter)</label>
                <input type="password" name="password" class="form-control" style="width: 100%;" placeholder="Masukkan password baru..." required minlength="8">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.375rem; color: #374151;">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" class="form-control" style="width: 100%;" placeholder="Ulangi password baru..." required minlength="8">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                <button type="button" onclick="closeResetPasswordModal()" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary" onclick="Toast.fire({ icon: 'success', title: 'Password berhasil diperbarui...' })">Reset Password</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        if (typeof $.fn.select2 !== 'undefined') {
            $('.select2-basic').select2({
                width: '100%'
            });
        }
    });

    function confirmToggleActive(form, isCurrentlyActive, adminName) {
        const actionText = isCurrentlyActive ? 'menonaktifkan' : 'mengaktifkan kembali';
        const confirmColor = isCurrentlyActive ? '#dc2626' : '#059669';
        
        Swal.fire({
            title: isCurrentlyActive ? 'Nonaktifkan Akun Admin?' : 'Aktifkan Akun Admin?',
            text: 'Apakah Anda yakin ingin ' + actionText + ' akun admin ' + adminName + '?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: confirmColor,
            cancelButtonColor: '#6b7280',
            confirmButtonText: isCurrentlyActive ? 'Ya, Nonaktifkan' : 'Ya, Aktifkan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }

    function confirmDeleteAdmin(form, adminName) {
        Swal.fire({
            title: 'Hapus Akun Admin?',
            text: 'Apakah Anda yakin ingin menghapus akun admin ' + adminName + '? Data yang dihapus tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus Akun',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }

    function openResetPasswordModal(adminId, adminName) {
        document.getElementById('resetPasswordForm').action = "/admin/reset-password-admin/" + adminId;
        document.getElementById('modalAdminName').value = adminName;
        document.getElementById('resetPasswordModal').classList.add('active');
    }

    function closeResetPasswordModal() {
        document.getElementById('resetPasswordModal').classList.remove('active');
    }
</script>
@endpush
@endsection