{{-- resources/views/admin/info-admin.blade.php --}}
@extends('admin.layouts.admin-layout')

@section('title', 'Info Admin')
@section('page-title', 'Info Admin')

@php
    $activeMenu = 'info-admin';
@endphp

@push('styles')
<style>
    .page-header {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #0a2540;
        margin-bottom: 0.5rem;
    }

    .page-desc {
        color: #6b7280;
        font-size: 0.9375rem;
    }

    .btn-primary {
        background: #0a2540;
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.2s;
        font-family: 'Montserrat', sans-serif;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-primary:hover {
        background: #ffd700;
        color: #0a2540;
    }

    .btn-primary svg {
        width: 18px;
        height: 18px;
        stroke: currentColor;
        fill: none;
        stroke-width: 2;
    }

    .admin-table-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .table-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .table-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #0a2540;
    }

    .search-form {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .search-box {
        position: relative;
    }

    .search-input {
        padding: 0.625rem 1rem 0.625rem 2.5rem;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.875rem;
        width: 250px;
        font-family: 'Montserrat', sans-serif;
    }

    .search-input:focus {
        outline: none;
        border-color: #ffd700;
    }

    .filter-select {
        padding: 0.625rem 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.875rem;
        font-family: 'Montserrat', sans-serif;
        background-color: white;
        color: #374151;
        cursor: pointer;
    }
    
    .filter-select:focus {
        outline: none;
        border-color: #ffd700;
    }

    .search-btn {
        padding: 0.625rem 1.25rem;
        background-color: #0a2540;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        font-family: 'Montserrat', sans-serif;
        transition: background 0.2s;
    }
    .search-btn:hover {
        background-color: #ffd700;
        color: #0a2540;
    }

    .search-icon {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        width: 18px;
        height: 18px;
        stroke: #6b7280;
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
    }

    .admin-table thead {
        background: #f9fafb;
    }

    .admin-table th {
        padding: 1rem 2rem;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 700;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .admin-table td {
        padding: 1.25rem 2rem;
        border-top: 1px solid #e5e7eb;
    }

    .admin-table tbody tr {
        transition: background 0.2s;
    }

    .admin-table tbody tr:hover {
        background: #f9fafb;
    }

    .admin-cell {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .admin-avatar-table {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: #ffd700;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0a2540;
        font-weight: 700;
        font-size: 1rem;
        flex-shrink: 0;
        overflow: hidden;
    }

    .admin-details {
        flex: 1;
        min-width: 0;
    }

    .admin-name-table {
        font-weight: 600;
        color: #0a2540;
        font-size: 0.9375rem;
        margin-bottom: 0.25rem;
    }

    .admin-email-table {
        font-size: 0.8125rem;
        color: #6b7280;
    }

    .badge {
        padding: 0.375rem 0.875rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .badge-bpc {
        background: #ffd700;
        color: #0a2540;
    }

    .badge-bpd {
        background: #3b82f6;
        color: white;
    }

    .badge-super_admin {
    background: linear-gradient(135deg, #0a2540 0%, #1a3a5a 100%);
    color: #ffd700;
    border: 2px solid #ffd700;
    font-weight: 800;
    letter-spacing: 1px;
    box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
}

    .domisili-text {
        font-size: 0.875rem;
        color: #6b7280;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-edit {
        padding: 0.5rem 0.875rem;
        background: #f3f4f6;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.8125rem;
        font-weight: 600;
        color: #374151;
        transition: all 0.2s;
        font-family: 'Montserrat', sans-serif;
        text-decoration: none;
        display: inline-block;
    }

    .btn-edit:hover {
        background: #e5e7eb;
    }

    .btn-delete {
        padding: 0.5rem 0.875rem;
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.8125rem;
        font-weight: 600;
        color: #dc2626;
        transition: all 0.2s;
        font-family: 'Montserrat', sans-serif;
    }

    .btn-delete:hover {
        background: #fee2e2;
    }

    .pagination {
        padding: 1.5rem 2rem;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .pagination-info {
        font-size: 0.875rem;
        color: #6b7280;
    }

    .pagination-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .pagination-btn {
        padding: 0.5rem 1rem;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        transition: all 0.2s;
        font-family: 'Montserrat', sans-serif;
        text-decoration: none;
    }

    .pagination-btn:hover:not(:disabled) {
        background: #f3f4f6;
    }

    .pagination-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .pagination-btn.active {
        background: #0a2540;
        color: white;
        border-color: #0a2540;
    }

    /* Modal Styles */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 12px;
        max-width: 500px;
        width: 90%;
        overflow: hidden;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .modal-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #dc2626;
    }

    .modal-body {
        padding: 2rem;
    }

    .modal-text {
        color: #6b7280;
        line-height: 1.6;
        margin-bottom: 1rem;
    }

    .admin-info-modal {
        background: #f9fafb;
        padding: 1rem;
        border-radius: 8px;
        margin-top: 1rem;
    }

    .admin-info-modal strong {
        color: #0a2540;
    }

    .modal-footer {
        padding: 1.5rem 2rem;
        border-top: 1px solid #e5e7eb;
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
    }

    .btn-cancel-modal {
        padding: 0.75rem 1.5rem;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        transition: all 0.2s;
        font-family: 'Montserrat', sans-serif;
    }

    .btn-cancel-modal:hover {
        background: #f3f4f6;
    }

    .btn-confirm-delete {
        padding: 0.75rem 1.5rem;
        background: #dc2626;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.875rem;
        font-weight: 600;
        color: white;
        transition: all 0.2s;
        font-family: 'Montserrat', sans-serif;
    }

    .btn-confirm-delete:hover {
        background: #b91c1c;
    }

    @media (max-width: 1024px) {
        .page-header {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
            padding: 1.5rem;
        }

        .page-title {
            font-size: 1.5rem;
        }

        .page-desc {
            font-size: 0.875rem;
        }

        .btn-primary {
            width: 100%;
            justify-content: center;
        }

        .table-header {
            flex-direction: column;
            gap: 1rem;
            padding: 1.25rem;
            align-items: flex-start;
        }

        .table-title {
            font-size: 1rem;
        }

        .search-box {
            width: 100%;
        }

        .search-input {
            width: 100%;
        }

        .admin-table-container {
            overflow-x: auto;
        }

        .admin-table {
            min-width: 900px;
        }

        .admin-table th,
        .admin-table td {
            padding: 0.875rem 1rem;
        }

        .admin-table th {
            font-size: 0.6875rem;
        }

        .admin-avatar-table {
            width: 40px;
            height: 40px;
            font-size: 0.875rem;
        }

        .admin-name-table {
            font-size: 0.875rem;
        }

        .admin-email-table {
            font-size: 0.75rem;
        }

        .badge {
            padding: 0.3rem 0.65rem;
            font-size: 0.6875rem;
        }

        .action-buttons {
            flex-direction: column;
            gap: 0.375rem;
        }

        .btn-edit,
        .btn-delete {
            padding: 0.4rem 0.75rem;
            font-size: 0.75rem;
            width: 100%;
            text-align: center;
        }

        .pagination {
            flex-direction: column;
            gap: 1rem;
            padding: 1.25rem;
            align-items: flex-start;
        }

        .pagination-info {
            font-size: 0.8125rem;
        }

        .pagination-buttons {
            width: 100%;
            flex-wrap: wrap;
            justify-content: center;
        }

        .pagination-btn {
            padding: 0.5rem 0.75rem;
            font-size: 0.8125rem;
        }
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Manajemen Admin</h1>
        <p class="page-desc">Kelola seluruh akun administrator Karang Taruna</p>
    </div>
    <a href="{{ route('admin.create-admin') }}" class="btn-primary">
        <svg viewBox="0 0 24 24">
            <line x1="12" y1="5" x2="12" y2="19"/>
            <line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Tambah Admin Baru
    </a>
</div>

@if(session('success'))
<div style="background: #d1fae5; color: #059669; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #86efac;">
    {{ session('success') }}
</div>
@endif

@if(session('created_credentials'))
<div style="margin-bottom: 2rem; padding: 1.5rem; background: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <h3 style="color: #0a2540; margin-bottom: 0.5rem; font-weight: 700; font-size: 1.125rem;">
        Akun Admin Berhasil Dibuat
    </h3>
    <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 1rem;">Silakan salin detail akun di bawah ini untuk diberikan kepada pengurus yang bersangkutan.</p>
    
    <textarea id="credentialText" readonly style="width: 100%; height: 110px; padding: 1rem; border-radius: 8px; border: 1px solid #d1d5db; margin-bottom: 1rem; font-family: monospace; font-size: 0.875rem; background: #f9fafb; color: #111827; resize: none; line-height: 1.5;">
Nama     : {{ session('created_credentials')['name'] }}
Jabatan  : {{ session('created_credentials')['role'] }}
Username : {{ session('created_credentials')['username'] }}
Password : {{ session('created_credentials')['password'] }}</textarea>
    
    <button type="button" onclick="copyCredentials()" class="btn-primary" style="background: #0a2540; border: none; padding: 0.625rem 1.25rem; border-radius: 8px; color: white; cursor: pointer; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; font-size: 0.875rem; transition: background 0.2s;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
        Salin Kredensial
    </button>
</div>
@endif

<div class="admin-table-container">
    <div class="table-header">
        <h3 class="table-title">Daftar Admin ({{ $admins->total() }})</h3>
        <form action="{{ route('admin.info-admin') }}" method="GET" class="search-form">
            <select name="role" class="filter-select">
                <option value="">Semua Kategori</option>
                <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                <option value="pimpinan" {{ request('role') == 'pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                <option value="pnkt" {{ request('role') == 'pnkt' ? 'selected' : '' }}>PNKT (Pusat)</option>
                <option value="ppkt" {{ request('role') == 'ppkt' ? 'selected' : '' }}>PPKT (Provinsi)</option>
                <option value="pkkt" {{ request('role') == 'pkkt' ? 'selected' : '' }}>PKKT (Kab/Kota)</option>
            </select>
            <div class="search-box">
                <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/>
                    <path d="m21 21-4.35-4.35"/>
                </svg>
                <input type="text" name="search" class="search-input" placeholder="Cari admin..." value="{{ request('search') }}">
            </div>
            <button type="submit" class="search-btn">Filter</button>
            @if(request()->has('search') || request()->has('role'))
                <a href="{{ route('admin.info-admin') }}" class="search-btn" style="background:#fef2f2; color:#dc2626; border: 1px solid #fecaca; text-decoration:none;">Reset</a>
            @endif
        </form>
    </div>

<table class="admin-table">
    <thead>
        <tr>
            <th>Admin</th>
            <th>Username</th>
            <th>Email</th>
            <th>Terdaftar</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($admins as $adminItem)
        <tr>
            <td>
                <div class="admin-cell">
                    <div class="admin-avatar-table">
                        @if($adminItem->photo)
                            <img src="{{ $adminItem->photo_url }}" alt="{{ $adminItem->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            {{ strtoupper(substr($adminItem->name, 0, 2)) }}
                        @endif
                    </div>
                    <div class="admin-details">
                        <div class="admin-name-table">{{ $adminItem->name }}</div>
                        <div class="admin-email-table">{{ $adminItem->role_display_name }}</div>
                    </div>
                </div>
            </td>
            <td>{{ $adminItem->username }}</td>
            <td>{{ $adminItem->email }}</td>
            <td>{{ $adminItem->created_at->format('d M Y') }}</td>
            <td>
                <div class="action-buttons">
                    <a href="{{ route('admin.edit-admin', ['admin' => $adminItem->id]) }}" class="btn-edit">Edit</a>
                    <button class="btn-delete" onclick="openDeleteModal({{ $adminItem->id }}, '{{ $adminItem->name }}')">Hapus</button>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
    <div class="pagination">
        <div class="pagination-info">
            Menampilkan {{ $admins->firstItem() }} - {{ $admins->lastItem() }} dari {{ $admins->total() }} admin
        </div>
        <div class="pagination-buttons">
            @if($admins->onFirstPage())
                <button class="pagination-btn" disabled>Previous</button>
            @else
                <a href="{{ $admins->previousPageUrl() }}" class="pagination-btn">Previous</a>
            @endif
            
            @foreach($admins->getUrlRange(1, $admins->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="pagination-btn {{ $page == $admins->currentPage() ? 'active' : '' }}">
                    {{ $page }}
                </a>
            @endforeach
            
            @if($admins->hasMorePages())
                <a href="{{ $admins->nextPageUrl() }}" class="pagination-btn">Next</a>
            @else
                <button class="pagination-btn" disabled>Next</button>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Konfirmasi Hapus Admin</h3>
        </div>
        <div class="modal-body">
            <p class="modal-text">Apakah Anda yakin ingin menghapus admin ini?</p>
            <div class="admin-info-modal">
                <strong id="adminNameModal"></strong>
            </div>
            <p class="modal-text" style="margin-top: 1rem; font-size: 0.875rem;">
                <strong>Perhatian:</strong> Data admin yang dihapus tidak dapat dikembalikan.
            </p>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel-modal" onclick="closeDeleteModal()">Batal</button>
            <form id="deleteForm" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-confirm-delete">Ya, Hapus Admin</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openDeleteModal(adminId, adminName) {
    document.getElementById('adminNameModal').textContent = adminName;
    document.getElementById('deleteForm').action = `/admin/delete-admin/${adminId}`;
    document.getElementById('deleteModal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

function copyCredentials() {
    var copyText = document.getElementById("credentialText");
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value).then(function() {
        Swal.fire({
            title: 'Tersalin!',
            text: 'Kredensial admin berhasil disalin ke clipboard.',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        });
    }).catch(function() {
        document.execCommand("copy");
        Swal.fire({
            title: 'Tersalin!',
            text: 'Kredensial admin berhasil disalin.',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        });
    });
}
</script>
@endpush