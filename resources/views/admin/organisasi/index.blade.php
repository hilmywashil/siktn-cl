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
</style>
@endpush

@section('content')
    @if(session('success'))
        <div class="alert alert-success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="20" height="20">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="stats-grid">
        <div class="stat-item">
            <div class="stat-label">Total Anggota</div>
            <div class="stat-value">{{ $organisasi->count() }}</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Aktif</div>
            <div class="stat-value">{{ $organisasi->where('aktif', true)->count() }}</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Ketua Bidang</div>
            <div class="stat-value">{{ $organisasi->where('kategori', 'ketua_bidang')->count() }}</div>
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
                                        <form action="{{ route('admin.organisasi.destroy', $item) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete">
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