{{-- resources/views/admin/organisasi/index.blade.php --}}
@extends('admin.layouts.admin-layout')

@section('title', 'Kelola Organisasi')

@section('page-title', 'Kelola Organisasi')

@push('styles')
<style>
    .stats-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-item {
        background: linear-gradient(135deg, #0a2540 0%, #164e63 100%);
        padding: 1.5rem;
        border-radius: 8px;
        color: white;
    }

    .stat-label {
        font-size: 0.875rem;
        opacity: 0.9;
        margin-bottom: 0.5rem;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
    }

    .content-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f3f4f6;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0a2540;
    }

    .btn-primary {
        background: #0a2540;
        color: white;
        padding: 0.625rem 1.5rem;
        border-radius: 8px;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-primary:hover {
        background: #164e63;
        transform: translateY(-1px);
    }

    .table-container {
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        background: #f9fafb;
        padding: 0.875rem;
        text-align: left;
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        border-bottom: 2px solid #e5e7eb;
    }

    .data-table td {
        padding: 1rem 0.875rem;
        border-bottom: 1px solid #e5e7eb;
        font-size: 0.875rem;
    }

    .data-table tr:hover {
        background: #f9fafb;
    }

    .avatar-cell {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .avatar {
        width: 64px;
        height: 64px;
        border-radius: 8px;
        object-fit: cover;
        border: 2px solid #e5e7eb;
    }

    .avatar-info h4 {
        font-size: 0.875rem;
        font-weight: 600;
        color: #0a2540;
        margin-bottom: 0.25rem;
    }

    .avatar-info p {
        font-size: 0.75rem;
        color: #6b7280;
    }

    .badge {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }

    .badge-active {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .kategori-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
        background: #dbeafe;
        color: #1e40af;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-edit, .btn-delete {
        padding: 0.5rem 0.875rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-edit {
        background: #dbeafe;
        color: #1e40af;
    }

    .btn-edit:hover {
        background: #bfdbfe;
    }

    .btn-delete {
        background: #fee2e2;
        color: #991b1b;
    }

    .btn-delete:hover {
        background: #fecaca;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #6b7280;
    }

    .empty-state svg {
        width: 64px;
        height: 64px;
        margin: 0 auto 1rem;
        opacity: 0.5;
    }

    .alert {
        padding: 1rem 1.25rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #6ee7b7;
    }

    /* Org Tree - sama persis kayak Master Jabatan */
    .org-tree {
        text-align: center;
        display: block;
        margin: 0 auto;
        width: max-content;
        min-width: max-content;
        padding: 1.5rem 3rem 1rem 3rem;
    }
    .org-tree ul {
        padding-top: 20px;
        position: relative;
        transition: all 0.5s;
        padding-left: 0;
        display: flex;
        justify-content: center;
        margin: 0;
    }
    .org-tree li {
        text-align: center;
        list-style-type: none;
        position: relative;
        padding: 30px 20px 0 20px;
        transition: all 0.5s;
    }
    .org-tree li::before, .org-tree li::after {
        content: '';
        position: absolute; top: 0; right: 50%;
        border-top: 2px solid #cbd5e1;
        width: 50%; height: 30px;
    }
    .org-tree li::after { right: auto; left: 50%; border-left: 2px solid #cbd5e1; }
    .org-tree li:only-child::after, .org-tree li:only-child::before { display: none; }
    .org-tree li:only-child { padding-top: 0; }
    .org-tree li:first-child::before, .org-tree li:last-child::after { border: 0 none; }
    .org-tree li:last-child::before { border-right: 2px solid #cbd5e1; border-radius: 0 5px 0 0; }
    .org-tree li:first-child::after { border-radius: 5px 0 0 0; }
    .org-tree ul ul::before {
        content: '';
        position: absolute; top: 0; left: 50%;
        border-left: 2px solid #cbd5e1;
        width: 0; height: 30px;
    }

    .org-tree-node {
        border: 2px solid #e2e8f0;
        border-top: 4px solid #0a2540;
        padding: 0.75rem 1rem 0.75rem 1rem;
        display: inline-block;
        border-radius: 8px;
        background: white;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        min-width: 160px;
        max-width: 220px;
        position: relative;
        z-index: 1;
        margin-bottom: 20px;
        text-align: center;
        vertical-align: top;
    }
    .org-tree-node.empty-node {
        border-top-color: #cbd5e1;
        opacity: 0.7;
    }
    .org-node-urutan {
        position: absolute;
        top: -10px;
        left: 50%;
        transform: translateX(-50%);
        background: #0a2540;
        color: white;
        font-size: 0.65rem;
        padding: 2px 8px;
        border-radius: 12px;
        white-space: nowrap;
        z-index: 2;
    }
    .org-node-jabatan {
        font-size: 0.8rem;
        font-weight: 700;
        color: #0a2540;
        margin-top: 0.5rem;
        margin-bottom: 0.2rem;
    }
    .org-member-mini-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.3rem;
        padding-top: 0.2rem;
    }
    .org-avatar-mini {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e2e8f0;
    }
    .org-avatar-placeholder-mini {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #0a2540;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
    }
    .org-name-mini {
        font-size: 0.85rem;
        font-weight: 700;
        color: #0a2540;
        margin: 0;
    }
    .org-jabatan-mini {
        font-size: 0.7rem;
        color: #6b7280;
        margin: 0;
    }
    .org-add-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #475569;
        font-size: 1.1rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s;
        border: 2px solid #cbd5e1;
    }
    .org-add-btn:hover { background: #0a2540; color: white; border-color: #0a2540; }

    .sibling-btn {
        position: absolute;
        right: -12px;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .sibling-btn:hover { background: #0ea5e9; border-color: #0ea5e9; color: white; }

    .child-btn {
        position: absolute;
        bottom: -12px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 10;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .child-btn:hover { background: #10b981; border-color: #10b981; color: white; }

    .org-tree-wrapper {
        overflow: auto;
        max-height: 550px;
        width: 100%;
        display: block;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #fafbfc;
        padding-bottom: 0.5rem;
    }
</style>
@endpush

@section('content')
    <div class="stats-grid" style="margin-bottom: 2rem;">
        <div class="stat-item">
            <div class="stat-label">Total Anggota</div>
            <div class="stat-value">{{ $organisasi->count() }}</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Aktif</div>
            <div class="stat-value">{{ $organisasi->where('aktif', true)->count() }}</div>
        </div>
    </div>
    <div class="content-card" style="margin-bottom: 2rem;">
        <div class="card-header">
            <div>
                <h3 class="card-title">Struktur Organisasi</h3>
                <p style="color: #6b7280; font-size: 0.875rem; margin-top: 0.25rem;">Bagan organisasi berdasarkan jabatan. Klik <strong>+</strong> pada node untuk menambah anggota.</p>
            </div>
        </div>
        
        <div class="org-tree-wrapper">
            @if(isset($jabatanTree) && $jabatanTree->count() > 0)
                <div class="org-tree">
                    <ul>
                        @foreach($jabatanTree as $root)
                            @include('admin.organisasi.partials.tree-node', ['node' => $root])
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="empty-state" style="padding: 2rem;">
                    <p>Belum ada jabatan. <a href="{{ route('admin.jabatan.index') }}">Tambah Jabatan</a> terlebih dahulu.</p>
                </div>
            @endif
        </div>
    </div>



    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">Daftar Struktur Organisasi</h3>
            <a href="{{ route('admin.organisasi.create') }}" class="btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="16" height="16">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Tambah Anggota
            </a>
        </div>

        <div class="table-container">
            @if($organisasi->count() > 0)
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama & Jabatan</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($organisasi as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="avatar-cell">
                                        <img src="{{ $item->foto_url }}" alt="{{ $item->nama }}" class="avatar">
                                        <div class="avatar-info">
                                            <h4>{{ $item->nama }}</h4>
                                            <p>{{ $item->jabatan }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="kategori-badge">{{ $item->kategori_label }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $item->aktif ? 'badge-active' : 'badge-inactive' }}">
                                        {{ $item->aktif ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.organisasi.edit', $item) }}" class="btn-edit">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="14" height="14">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.organisasi.destroy', $item) }}" method="POST" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn-delete delete-btn">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="14" height="14">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <h3>Belum ada data organisasi</h3>
                    <p>Mulai tambahkan anggota struktur organisasi</p>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('.delete-form');
                
                Swal.fire({
                    title: 'Hapus Data?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        confirmButton: 'swal2-confirm-btn',
                        cancelButton: 'swal2-cancel-btn'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush