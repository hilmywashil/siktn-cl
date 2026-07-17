{{-- resources/views/admin/berita/index.blade.php --}}
@extends('admin.layouts.admin-layout')

@section('title', 'Kelola Berita')
@section('page-title', 'Kelola Berita')

@php
$activeMenu = 'berita';
@endphp

@push('styles')
<style>
    .content-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .add-btn {
        background: #022648;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
    }

    .add-btn:hover {
        background: #1c2780;
        transform: translateY(-1px);
    }

    .table-container {
        background: white;
        border-radius: 6px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
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
        font-weight: 600;
        color: #374151;
        border-bottom: 2px solid #e5e7eb;
    }

    .data-table td {
        padding: 1rem;
        border-bottom: 1px solid #f3f4f6;
    }

    .data-table tbody tr:hover {
        background: #f9fafb;
    }

    .berita-image {
        width: 80px;
        height: 80px;
        border-radius: 4px;
        object-fit: cover;
    }

    .berita-title {
        font-weight: 600;
        color: #022648;
        margin-bottom: 0.25rem;
    }

    .berita-date {
        font-size: 0.875rem;
        color: #6b7280;
    }

    .badge {
        padding: 0.375rem 0.75rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        display: inline-block;
        text-align: center;
    }

    .badge-Draft {
        background: rgba(107, 114, 128, 0.1);
        color: #6B7280;
        border: 1px solid rgba(107, 114, 128, 0.2);
    }

    .badge-Published {
        background: rgba(16, 185, 129, 0.1);
        color: #10B981;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .badge-Archived {
        background: rgba(214, 11, 28, 0.1);
        color: #D60B1C;
        border: 1px solid rgba(214, 11, 28, 0.2);
    }

    .badge-populer {
        background: rgba(197, 146, 23, 0.1);
        color: #C59217;
        border: 1px solid rgba(197, 146, 23, 0.2);
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-edit,
    .btn-delete {
        padding: 0.5rem 1rem;
        border-radius: 4px;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
    }

    .btn-edit {
        background: rgba(11, 19, 84, 0.1);
        color: #022648;
        border: 1px solid rgba(11, 19, 84, 0.15);
    }

    .btn-edit:hover {
        background: #022648;
        color: white;
    }

    .btn-delete {
        background: rgba(214, 11, 28, 0.1);
        color: #D60B1C;
        border: 1px solid rgba(214, 11, 28, 0.15);
    }

    .btn-delete:hover {
        background: #D60B1C;
        color: white;
    }

    .alert {
        padding: 1rem 1.5rem;
        border-radius: 4px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .alert-success {
        background: #d1fae5;
        color: #059669;
        border: 1px solid #6ee7b7;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #9ca3af;
    }

    .empty-state svg {
        width: 64px;
        height: 64px;
        margin-bottom: 1rem;
        stroke: currentColor;
        opacity: 0.5;
    }

    /* Custom Pagination */
    .pag-wrapper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .pag-info {
        font-size: 0.8125rem;
        color: #6b7280;
    }

    .pag-buttons {
        display: flex;
        gap: 6px;
        align-items: center;
        flex-wrap: wrap;
    }

    .pag-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        height: 36px;
        padding: 0 10px;
        border-radius: 4px;
        border: 1px solid #e5e7eb;
        background: white;
        color: #374151;
        font-size: 0.8125rem;
        cursor: pointer;
        transition: all 0.15s;
        text-decoration: none;
    }

    .pag-btn:hover {
        background: #f9fafb;
        border-color: #d1d5db;
    }

    .pag-btn.pag-active {
        background: #022648;
        color: white;
        border-color: #022648;
        font-weight: 500;
        pointer-events: none;
    }

    .pag-btn.pag-disabled {
        opacity: 0.35;
        pointer-events: none;
        cursor: default;
    }

    .pag-ellipsis {
        font-size: 0.8125rem;
        color: #9ca3af;
        padding: 0 4px;
        line-height: 36px;
    }

    @media (max-width: 1024px) {
        .data-table {
            display: block;
            overflow-x: auto;
        }

        .content-header {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }

        .pag-wrapper {
            flex-direction: column;
            align-items: center;
        }
    }
</style>
@endpush

@section('content')
<div class="content-header">
    <div>
        <h3 style="font-size: 1.5rem; font-weight: 700; color: #022648; margin-bottom: 0.5rem;">Daftar Berita</h3>
        <p style="color: #6b7280;">Kelola semua berita dan artikel Karang Taruna</p>
    </div>
    @if(auth()->guard('admin')->user()->canManageContent())
    <a href="{{ route('admin.berita.create') }}" class="add-btn">
        <svg viewBox="0 0 24 24" width="20" height="20" style="stroke: currentColor; fill: none; stroke-width: 2;">
            <line x1="12" y1="5" x2="12" y2="19" />
            <line x1="5" y1="12" x2="19" y2="12" />
        </svg>
        Tambah Berita
    </a>
    @endif
</div>

<div class="table-container" style="margin-bottom: 1rem; padding: 1rem 1.5rem;">
    <form method="GET" action="{{ route('admin.berita.index') }}" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
        <div style="display: flex; flex-direction: column; gap: 0.25rem;">
            <label style="font-size: 0.875rem; font-weight: 600; color: #374151;">Status</label>
            <select name="status" style="padding: 0.5rem; border-radius: 4px; border: 1px solid #d1d5db; min-width: 150px;">
                <option value="">Semua Status</option>
                <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                <option value="Published" {{ request('status') == 'Published' ? 'selected' : '' }}>Published</option>
                <option value="Archived" {{ request('status') == 'Archived' ? 'selected' : '' }}>Archived</option>
            </select>
        </div>
        <div style="display: flex; flex-direction: column; gap: 0.25rem;">
            <label style="font-size: 0.875rem; font-weight: 600; color: #374151;">Kategori</label>
            <select name="kategori" style="padding: 0.5rem; border-radius: 4px; border: 1px solid #d1d5db; min-width: 150px;">
                <option value="">Semua Kategori</option>
                @foreach($kategoris as $kat)
                    <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn-edit" style="height: 38px;">Filter</button>
        <a href="{{ route('admin.berita.index') }}" class="btn-delete" style="height: 38px;">Reset</a>
    </form>
</div>

<div class="table-container">
    @if($beritas->count() > 0)
    <table class="data-table">
        <thead>
            <tr>
                <th>Gambar</th>
                <th>Kategori & Judul</th>
                <th>Tgl & Jam Tayang</th>
                <th>Status</th>
                <th>Views</th>
                @if(auth()->guard('admin')->user()->canManageContent())
                <th>Aksi</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($beritas as $berita)
            <tr>
                <td>
                    <img src="{{ $berita->gambar_url }}" alt="{{ $berita->judul }}" class="berita-image">
                </td>
                <td>
                    <div style="font-size: 0.75rem; font-weight: 600; color: #022648; text-transform: uppercase; margin-bottom: 0.25rem;">{{ $berita->kategori }}</div>
                    <div class="berita-title">{{ $berita->judul }}</div>
                </td>
                <td>
                    <div class="berita-date">{{ $berita->tanggal_publish ? $berita->tanggal_publish->format('d/m/Y H:i') : '-' }}</div>
                </td>
                <td>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <span class="badge badge-{{ $berita->status }}">
                            {{ $berita->status }}
                        </span>
                        @if($berita->is_populer)
                        <span class="badge badge-populer">Populer</span>
                        @endif
                    </div>
                </td>
                <td>
                    <strong>{{ number_format($berita->views) }}</strong> views
                </td>
                @if(auth()->guard('admin')->user()->canManageContent())
                <td>
                    <div class="action-buttons">
                        <a href="{{ route('admin.berita.edit', $berita->id) }}" class="btn-edit">Edit</a>
                        <form action="{{ route('admin.berita.destroy', $berita->id) }}" method="POST" class="delete-form" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn-delete btn-delete-sweetalert">Hapus</button>
                        </form>
                    </div>
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Custom Pagination --}}
    @if($beritas->hasPages())
    @php
        $currentPage = $beritas->currentPage();
        $lastPage    = $beritas->lastPage();
        $pages = [];

        if ($lastPage <= 7) {
            $pages = range(1, $lastPage);
        } else {
            $pages[] = 1;
            if ($currentPage > 3) $pages[] = '...';
            $start = max(2, $currentPage - 1);
            $end   = min($lastPage - 1, $currentPage + 1);
            foreach (range($start, $end) as $p) $pages[] = $p;
            if ($currentPage < $lastPage - 2) $pages[] = '...';
            $pages[] = $lastPage;
        }
    @endphp

    <div class="pag-wrapper">
        <span class="pag-info">
            Menampilkan {{ $beritas->firstItem() }}–{{ $beritas->lastItem() }} dari {{ $beritas->total() }} berita
        </span>

        <div class="pag-buttons">
            {{-- Tombol Previous --}}
            @if($beritas->onFirstPage())
                <span class="pag-btn pag-disabled">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                </span>
            @else
                <a href="{{ $beritas->previousPageUrl() }}" class="pag-btn">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                </a>
            @endif

            {{-- Nomor halaman --}}
            @foreach($pages as $page)
                @if($page === '...')
                    <span class="pag-ellipsis">···</span>
                @elseif($page === $currentPage)
                    <span class="pag-btn pag-active">{{ $page }}</span>
                @else
                    <a href="{{ $beritas->url($page) }}" class="pag-btn">{{ $page }}</a>
                @endif
            @endforeach

            {{-- Tombol Next --}}
            @if($beritas->hasMorePages())
                <a href="{{ $beritas->nextPageUrl() }}" class="pag-btn">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                </a>
            @else
                <span class="pag-btn pag-disabled">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                </span>
            @endif
        </div>
    </div>
    @endif

    @else
    <div class="empty-state">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z" />
            <polyline points="13 2 13 9 20 9" />
        </svg>
        <h4 style="margin-bottom: 0.5rem;">Belum Ada Berita</h4>
        <p>Mulai tambahkan berita pertama Anda</p>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
            $('select[name="status"], select[name="kategori"]').select2({
                minimumResultsForSearch: -1, // Sembunyikan search box khusus filter ini
                width: '100%'
            });
        }

        // SweetAlert Delete Confirmation
        const deleteButtons = document.querySelectorAll('.btn-delete-sweetalert');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('form');
                
                Swal.fire({
                    title: 'Hapus Berita?',
                    text: "Berita yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
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