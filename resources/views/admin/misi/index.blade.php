@extends('admin.layouts.admin-layout', ['activeMenu' => 'misi'])

@section('title', 'Kelola Misi')
@section('page-title', 'Kelola Misi')

@push('styles')
<style>
    .misi-table-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .table-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .table-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0a2540;
    }

    .btn-primary {
        background: #ffd700;
        color: #0a2540;
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
    }

    .btn-primary:hover {
        background: #ffed4e;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(255, 215, 0, 0.3);
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table thead {
        background: #f9fafb;
    }

    .data-table th {
        padding: 1rem;
        text-align: left;
        font-size: 0.875rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .data-table td {
        padding: 1rem;
        border-top: 1px solid #e5e7eb;
        color: #374151;
    }

    .data-table tbody tr:hover {
        background: #f9fafb;
    }

    .misi-image {
        width: 80px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
    }

    .badge {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-success {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-danger {
        background: #fee2e2;
        color: #991b1b;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-sm {
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        border: none;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
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

    .alert {
        padding: 1rem 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        font-weight: 500;
    }

    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1.5rem;
        color: #6b7280;
    }

    .empty-state svg {
        width: 64px;
        height: 64px;
        margin: 0 auto 1rem;
        stroke: #d1d5db;
    }
</style>
@endpush

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="misi-table-container">
        <div class="table-header">
            <h2 class="table-title">Daftar Misi</h2>
            <a href="{{ route('admin.misi.create') }}" class="btn-primary">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Tambah Misi
            </a>
        </div>

        @if($misi->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Urutan</th>
                        <th>Gambar</th>
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($misi as $item)
                        <tr>
                            <td>{{ $item->order }}</td>
                            <td>
                                <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="misi-image">
                            </td>
                            <td>{{ Str::limit($item->title, 40) }}</td>
                            <td>{{ Str::limit($item->description, 60) }}</td>
                            <td>
                                <span class="badge {{ $item->is_active ? 'badge-success' : 'badge-danger' }}">
                                    {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.misi.edit', $item) }}" class="btn-sm btn-edit">Edit</a>
                                    <form action="{{ route('admin.misi.destroy', $item) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus misi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-sm btn-delete">Hapus</button>
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
                    <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem;">Belum ada data misi</h3>
                <p>Klik tombol "Tambah Misi" untuk menambahkan misi pertama Anda.</p>
            </div>
        @endif
    </div>
</div>
@endsection