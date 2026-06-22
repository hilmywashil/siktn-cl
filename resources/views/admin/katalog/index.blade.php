@extends('admin.layouts.admin-layout')

@section('title', 'Kelola E-Katalog')
@section('page-title', 'E-Katalog')

@php
    $activeMenu = 'katalog';
@endphp

@push('styles')
<style>
    .katalog-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border-left: 4px solid;
    }

    .stat-card.total { border-left-color: #0B1354; }
    .stat-card.pending { border-left-color: #C59217; }
    .stat-card.approved { border-left-color: #10B981; }
    .stat-card.rejected { border-left-color: #D60B1C; }

    .stat-label {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 800;
        color: #111827;
        margin-top: 0.5rem;
    }

    .katalog-table-container {
        background: white;
        border-radius: 6px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .table-header h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0a2540;
    }

    .filter-group {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .filter-select, .search-input {
        padding: 0.5rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        font-size: 0.875rem;
        background: white;
    }

    .filter-select:focus, .search-input:focus {
        outline: none;
        border-color: #0B1354;
        box-shadow: 0 0 0 3px rgba(11, 19, 84, 0.1);
    }

    .search-input {
        min-width: 250px;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        background: #f9fafb;
        padding: 0.875rem 1rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.875rem;
        color: #374151;
        border-bottom: 2px solid #e5e7eb;
    }

    .data-table td {
        padding: 1rem;
        border-bottom: 1px solid #e5e7eb;
        font-size: 0.875rem;
        color: #4b5563;
        vertical-align: middle;
    }

    .data-table tr:hover {
        background: #f9fafb;
    }

    .katalog-logo {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 4px;
        border: 1px solid #e5e7eb;
    }

    .company-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .company-name {
        font-weight: 600;
        color: #111827;
    }

    .company-field {
        font-size: 0.75rem;
        color: #6b7280;
    }

    .status-badge {
        padding: 0.375rem 0.875rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        border: 1px solid transparent;
    }

    .status-pending {
        background: rgba(197, 146, 23, 0.1);
        color: #C59217;
        border-color: rgba(197, 146, 23, 0.2);
    }

    .status-approved {
        background: rgba(16, 185, 129, 0.1);
        color: #10B981;
        border-color: rgba(16, 185, 129, 0.2);
    }

    .status-rejected {
        background: rgba(214, 11, 28, 0.1);
        color: #D60B1C;
        border-color: rgba(214, 11, 28, 0.2);
    }

    .status-badge::before {
        display: none;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .btn-view, .btn-approve, .btn-reject, .btn-delete, .btn-add {
        padding: 0.5rem 0.875rem;
        border-radius: 4px;
        border: none;
        cursor: pointer;
        font-size: 0.75rem;
        font-weight: 600;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
    }

    .btn-add {
        background: #0B1354;
        color: white;
        padding: 0.625rem 1.125rem;
        font-size: 0.875rem;
    }

    .btn-add:hover {
        background: #1c2780;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .btn-view {
        background: rgba(11, 19, 84, 0.1);
        color: #0B1354;
    }

    .btn-view:hover {
        background: rgba(11, 19, 84, 0.2);
    }

    .btn-approve {
        background: rgba(16, 185, 129, 0.1);
        color: #10B981;
    }

    .btn-approve:hover {
        background: rgba(16, 185, 129, 0.2);
    }

    .btn-reject {
        background: rgba(197, 146, 23, 0.1);
        color: #C59217;
    }

    .btn-reject:hover {
        background: rgba(197, 146, 23, 0.2);
    }

    .btn-delete {
        background: rgba(214, 11, 28, 0.1);
        color: #D60B1C;
    }

    .btn-delete:hover {
        background: rgba(214, 11, 28, 0.2);
    }

    .alert {
        padding: 1rem 1.25rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border-left: 4px solid #059669;
    }

    .alert svg {
        width: 20px;
        height: 20px;
    }

    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 1.5rem;
        gap: 0.5rem;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #6b7280;
    }

    .empty-state svg {
        width: 80px;
        height: 80px;
        margin: 0 auto 1rem;
        stroke: #d1d5db;
    }

    .pending-badge {
        background: #fef3c7;
        color: #92400e;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.625rem;
        font-weight: 700;
        text-transform: uppercase;
    }
.pag-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
    padding: 0 10px;
    border-radius: 8px;
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
.pag-active {
    background: #0a2540 !important;
    color: white !important;
    border-color: #0a2540 !important;
    font-weight: 500;
    pointer-events: none;
}
.pag-disabled {
    opacity: 0.35;
    pointer-events: none;
    cursor: default;
}
    @media (max-width: 768px) {
        .table-header {
            flex-direction: column;
            align-items: stretch;
        }

        .search-input {
            min-width: 100%;
        }
    }
</style>
@endpush

@section('content')
<!-- Statistik -->
<div class="katalog-stats">
    <div class="stat-card total">
        <div class="stat-label">Total Katalog</div>
        <div class="stat-value">{{ $stats['total'] }}</div>
    </div>
    <div class="stat-card pending">
        <div class="stat-label">Menunggu Approval</div>
        <div class="stat-value">{{ $stats['pending'] }}</div>
    </div>
    <div class="stat-card approved">
        <div class="stat-label">Disetujui</div>
        <div class="stat-value">{{ $stats['approved'] }}</div>
    </div>
    <div class="stat-card rejected">
        <div class="stat-label">Ditolak</div>
        <div class="stat-value">{{ $stats['rejected'] }}</div>
    </div>
</div>

<div class="katalog-table-container">
    @if(session('success'))
        <div class="alert alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="table-header">
        <h3>Daftar E-Katalog Perusahaan</h3>
        
        <div class="filter-group">
            <a href="{{ route('admin.katalog.create') }}" class="btn-add">
                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" fill="none" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Tambah Katalog
            </a>
            
            <form method="GET" action="{{ route('admin.katalog.index') }}" style="display: contents;">
                <select name="status" class="filter-select" onchange="this.form.submit()">
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                
                <input 
                    type="text" 
                    name="search" 
                    class="search-input" 
                    placeholder="Cari nama perusahaan, bidang..."
                    value="{{ request('search') }}">
            </form>
        </div>
    </div>

    @if($katalogs->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th>Logo</th>
                    <th>Perusahaan</th>
                    <th>Kontak</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($katalogs as $katalog)
                <tr>
                    <td>
                        <img src="{{ $katalog->logo_url }}" alt="{{ $katalog->company_name }}" class="katalog-logo">
                    </td>
                    <td>
                        <div class="company-info">
                            <span class="company-name">
                                {{ $katalog->company_name }}
                                @if($katalog->isPending())
                                    <span class="pending-badge">New</span>
                                @endif
                            </span>
                            <span class="company-field">{{ $katalog->business_field }}</span>
                        </div>
                    </td>
                    <td>
                        <div>{{ $katalog->phone }}</div>
                        <div style="font-size: 0.75rem; color: #6b7280;">{{ $katalog->email }}</div>
                    </td>
                    <td>
                        @if($katalog->status === 'pending')
                            <span class="status-badge status-pending">Pending</span>
                        @elseif($katalog->status === 'approved')
                            <span class="status-badge status-approved">Approved</span>
                        @else
                            <span class="status-badge status-rejected">Rejected</span>
                        @endif
                    </td>
                    <td>
                        <div style="font-size: 0.75rem;">
                            <div>{{ $katalog->created_at->format('d M Y') }}</div>
                            @if($katalog->approved_at)
                                <div style="color: #10b981;">✓ {{ $katalog->approved_at->format('d M Y') }}</div>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.katalog.show', $katalog) }}" class="btn-view">
                                <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" fill="none" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                Lihat
                            </a>
                            
                            @if($katalog->isPending())
                                <form action="{{ route('admin.katalog.approve', $katalog) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn-approve" onclick="return confirm('Setujui katalog ini?')">
                                        <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" fill="none" stroke-width="2">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                        Approve
                                    </button>
                                </form>
                                
                                <button type="button" class="btn-reject" onclick="openRejectModal({{ $katalog->id }}, '{{ $katalog->company_name }}')">
                                    <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" fill="none" stroke-width="2">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                    Reject
                                </button>
                            @endif
                            
                            <form action="{{ route('admin.katalog.destroy', $katalog) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus katalog ini? Tindakan tidak dapat dibatalkan!')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">
                                    <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" fill="none" stroke-width="2">
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

        <div class="pagination">
            {{-- Custom Pagination --}}
@if($katalogs->hasPages())
<div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-top: 1.5rem;">
    
    {{-- Info jumlah data --}}
    <span style="font-size: 0.8125rem; color: #6b7280;">
        Menampilkan {{ $katalogs->firstItem() }}–{{ $katalogs->lastItem() }} dari {{ $katalogs->total() }} data
    </span>

    {{-- Tombol paginasi --}}
    <div style="display: flex; gap: 6px; align-items: center; flex-wrap: wrap;">

        {{-- Tombol Previous --}}
        @if($katalogs->onFirstPage())
            <span class="pag-btn pag-disabled">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
            </span>
        @else
            <a href="{{ $katalogs->previousPageUrl() }}&{{ http_build_query(request()->except('page')) }}" class="pag-btn">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
            </a>
        @endif

        {{-- Nomor halaman dengan logika ellipsis --}}
        @php
            $currentPage = $katalogs->currentPage();
            $lastPage    = $katalogs->lastPage();
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

        @foreach($pages as $page)
            @if($page === '...')
                <span style="font-size: 0.8125rem; color: #9ca3af; padding: 0 4px; line-height: 36px;">···</span>
            @elseif($page === $currentPage)
                <span class="pag-btn pag-active">{{ $page }}</span>
            @else
                <a href="{{ $katalogs->url($page) }}&{{ http_build_query(request()->except('page')) }}" class="pag-btn">{{ $page }}</a>
            @endif
        @endforeach

        {{-- Tombol Next --}}
        @if($katalogs->hasMorePages())
            <a href="{{ $katalogs->nextPageUrl() }}&{{ http_build_query(request()->except('page')) }}" class="pag-btn">
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
        </div>
    @else
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                <line x1="9" y1="9" x2="15" y2="9"/>
                <line x1="9" y1="15" x2="15" y2="15"/>
            </svg>
            <h3>Belum ada data katalog</h3>
            <p>Data katalog dari anggota akan muncul di sini</p>
        </div>
    @endif
</div>

<!-- Reject Modal -->
<div id="rejectModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 50; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 6px; padding: 2rem; max-width: 500px; width: 90%;">
        <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem;">Tolak Katalog</h3>
        <p style="color: #6b7280; margin-bottom: 1.5rem;">Berikan alasan penolakan untuk <strong id="rejectCompanyName"></strong></p>
        
        <form id="rejectForm" method="POST">
            @csrf
            <textarea 
                name="rejection_reason" 
                rows="4" 
                placeholder="Contoh: Logo kurang jelas, deskripsi terlalu singkat, informasi kontak tidak lengkap..."
                style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 4px; font-family: inherit;"
                required></textarea>
            
            <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
                <button type="submit" style="flex: 1; background: #D60B1C; color: white; padding: 0.75rem; border-radius: 4px; border: none; font-weight: 600; cursor: pointer;">
                    Tolak Katalog
                </button>
                <button type="button" onclick="closeRejectModal()" style="flex: 1; background: #f3f4f6; color: #374151; padding: 0.75rem; border-radius: 4px; border: none; font-weight: 600; cursor: pointer;">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openRejectModal(katalogId, companyName) {
    document.getElementById('rejectModal').style.display = 'flex';
    document.getElementById('rejectCompanyName').textContent = companyName;
    document.getElementById('rejectForm').action = `/admin/katalog/${katalogId}/reject`;
}

function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
    document.getElementById('rejectForm').reset();
}

// Close modal when clicking outside
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});
</script>
@endsection