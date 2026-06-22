@extends('admin.layouts.admin-layout')

@section('title', 'Kelola UMKM')
@section('page-title', 'Kelola Data UMKM')

@section('content')
<div class="admin-container">
    {{-- Statistics Cards --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: #EFF6FF;">
                <svg viewBox="0 0 24 24" style="stroke: #3B82F6;">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['total'] }}</div>
                <div class="stat-label">Total UMKM</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: #FEF3C7;">
                <svg viewBox="0 0 24 24" style="stroke: #F59E0B;">
                    <circle cx="12" cy="12" r="10" />
                    <polyline points="12 6 12 12 16 14" />
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['pending'] }}</div>
                <div class="stat-label">Menunggu Verifikasi</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: #D1FAE5;">
                <svg viewBox="0 0 24 24" style="stroke: #10B981;">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                    <polyline points="22 4 12 14.01 9 11.01" />
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['approved'] }}</div>
                <div class="stat-label">Disetujui</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: #FEE2E2;">
                <svg viewBox="0 0 24 24" style="stroke: #EF4444;">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="15" y1="9" x2="9" y2="15" />
                    <line x1="9" y1="9" x2="15" y2="15" />
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['rejected'] }}</div>
                <div class="stat-label">Ditolak</div>
            </div>
        </div>
    </div>

    {{-- Filters & Search --}}
    <div class="content-card">
        <form method="GET" action="{{ route('admin.umkm.index') }}" class="filter-form">
            <div class="filter-group">
                <div class="search-box">
                    <svg viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.35-4.35" />
                    </svg>
                    <input type="text" name="search" placeholder="Cari nama usaha, pemilik, email, atau HP..."
                        value="{{ request('search') }}">
                </div>

                <select name="status" class="filter-select">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>

                <button type="submit" class="btn-primary">
                    <svg viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.35-4.35" />
                    </svg>
                    Cari
                </button>

                @if(request('search') || request('status'))
                <a href="{{ route('admin.umkm.index') }}" class="btn-secondary">
                    <svg viewBox="0 0 24 24">
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- UMKM Table --}}
    <div class="content-card">
        <div class="card-header">
            <h3>Daftar UMKM</h3>
            <div class="card-actions">
                <a href="{{ route('admin.umkm.export', request()->query()) }}" class="btn-secondary">
                    <svg viewBox="0 0 24 24">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <polyline points="7 10 12 15 17 10" />
                        <line x1="12" y1="15" x2="12" y2="3" />
                    </svg>
                    Export Data
                </a>
            </div>
        </div>

        @if($umkms->count() > 0)
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Nama Usaha</th>
                        <th>Pemilik</th>
                        <th>Bidang Usaha</th>
                        <th>Kontak</th>
                        <th width="120">Tanggal Daftar</th>
                        <th width="100">Status</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($umkms as $index => $umkm)
                    <tr>
                        <td class="text-center">{{ $umkms->firstItem() + $index }}</td>
                        <td>
                            <div class="business-info">
                                <div class="business-name">{{ $umkm->nama_usaha }}</div>
                                <div class="business-year">Sejak {{ $umkm->tahun_berdiri }}</div>
                            </div>
                        </td>
                        <td>
                            <div class="owner-info">
                                <div class="owner-name">{{ $umkm->nama_lengkap }}</div>
                                <div class="owner-gender">{{ $umkm->jenis_kelamin }}</div>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-info">{{ $umkm->bidang_usaha }}</span>
                        </td>
                        <td>
                            <div class="contact-info">
                                <div class="contact-phone">{{ $umkm->nomor_hp }}</div>
                                <div class="contact-email">{{ $umkm->email }}</div>
                            </div>
                        </td>
                        <td>{{ $umkm->created_at->format('d M Y') }}</td>
                        <td>
                            @if($umkm->status === 'pending')
                            <span class="badge badge-warning">Menunggu</span>
                            @elseif($umkm->status === 'approved')
                            <span class="badge badge-success">Disetujui</span>
                            @else
                            <span class="badge badge-danger">Ditolak</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.umkm.show', $umkm->id) }}" class="btn-icon btn-view" title="Lihat Detail">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                </a>

                                @if($umkm->status === 'pending')
                                <form action="{{ route('admin.umkm.approve', $umkm->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn-icon btn-approve" title="Setujui"
                                        onclick="return confirm('Setujui UMKM ini?')">
                                        <svg viewBox="0 0 24 24">
                                            <polyline points="20 6 9 17 4 12" />
                                        </svg>
                                    </button>
                                </form>

                                <button type="button" class="btn-icon btn-reject" title="Tolak"
                                    onclick="showRejectModal({{ $umkm->id }})">
                                    <svg viewBox="0 0 24 24">
                                        <line x1="18" y1="6" x2="6" y2="18" />
                                        <line x1="6" y1="6" x2="18" y2="18" />
                                    </svg>
                                </button>
                                @endif

                                <form action="{{ route('admin.umkm.destroy', $umkm->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon btn-delete" title="Hapus"
                                        onclick="return confirm('Yakin ingin menghapus data UMKM ini?')">
                                        <svg viewBox="0 0 24 24">
                                            <polyline points="3 6 5 6 21 6" />
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="pagination-wrapper">
            {{ $umkms->links() }}
        </div>
        @else
        <div class="empty-state">
            <svg viewBox="0 0 24 24">
                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
            </svg>
            <h3>Tidak ada data UMKM</h3>
            <p>Belum ada UMKM yang terdaftar atau sesuai dengan filter yang dipilih.</p>
        </div>
        @endif
    </div>
</div>

{{-- Reject Modal --}}
<div class="modal" id="rejectModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Tolak UMKM</h3>
            <button type="button" class="modal-close" onclick="hideRejectModal()">
                <svg viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>
        </div>
        <form id="rejectForm" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label>Alasan Penolakan <span class="required">*</span></label>
                    <textarea name="rejection_reason" class="form-control" rows="4"
                        placeholder="Masukkan alasan penolakan..." required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="hideRejectModal()">Batal</button>
                <button type="submit" class="btn-danger">Tolak UMKM</button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
    .admin-container {
        padding: 2rem;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-icon svg {
        width: 28px;
        height: 28px;
        stroke-width: 2;
        fill: none;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #0a2540;
    }

    .stat-label {
        font-size: 0.875rem;
        color: #6b7280;
    }

    /* Content Card */
    .content-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .card-header h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0a2540;
    }

    /* Filter */
    .filter-form {
        display: flex;
        gap: 1rem;
    }

    .filter-group {
        display: flex;
        gap: 1rem;
        flex: 1;
        flex-wrap: wrap;
    }

    .search-box {
        flex: 1;
        min-width: 300px;
        position: relative;
    }

    .search-box svg {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        width: 20px;
        height: 20px;
        stroke: #9ca3af;
        fill: none;
        stroke-width: 2;
    }

    .search-box input {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 3rem;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.875rem;
    }

    .filter-select {
        padding: 0.75rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.875rem;
        min-width: 180px;
    }

    /* Buttons */
    .btn-primary,
    .btn-secondary,
    .btn-danger {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-primary {
        background: #0a2540;
        color: white;
    }

    .btn-secondary {
        background: #f3f4f6;
        color: #374151;
    }

    .btn-danger {
        background: #ef4444;
        color: white;
    }

    .btn-primary:hover {
        background: #051628;
    }

    .btn-secondary:hover {
        background: #e5e7eb;
    }

    .btn-danger:hover {
        background: #dc2626;
    }

    .btn-primary svg,
    .btn-secondary svg,
    .btn-danger svg {
        width: 18px;
        height: 18px;
        stroke: currentColor;
        fill: none;
        stroke-width: 2;
    }

    /* Table */
    .table-responsive {
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        background: #f9fafb;
        padding: 1rem;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        color: #6b7280;
        border-bottom: 2px solid #e5e7eb;
        white-space: nowrap;
    }

    .data-table td {
        padding: 1rem;
        border-bottom: 1px solid #e5e7eb;
        vertical-align: top;
    }

    .data-table tr:hover {
        background: #f9fafb;
    }

    .text-center {
        text-align: center;
    }

    /* Business Info - Clean & Simple */
    .business-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .business-name {
        font-weight: 600;
        color: #0a2540;
        font-size: 0.9375rem;
    }

    .business-year {
        font-size: 0.75rem;
        color: #6b7280;
    }

    /* Owner Info */
    .owner-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .owner-name {
        font-weight: 500;
        color: #0a2540;
    }

    .owner-gender {
        font-size: 0.75rem;
        color: #6b7280;
    }

    /* Contact Info */
    .contact-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .contact-phone,
    .contact-email {
        font-size: 0.8125rem;
        color: #374151;
    }

    /* Badge */
    .badge {
        display: inline-block;
        padding: 0.375rem 0.875rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .badge-warning {
        background: #fef3c7;
        color: #92400e;
    }

    .badge-success {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-danger {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge-info {
        background: #dbeafe;
        color: #1e40af;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: nowrap;
    }

    .btn-icon {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        flex-shrink: 0;
    }

    .btn-icon svg {
        width: 16px;
        height: 16px;
        stroke-width: 2;
        fill: none;
    }

    .btn-view {
        background: #e0e7ff;
    }

    .btn-view svg {
        stroke: #4f46e5;
    }

    .btn-view:hover {
        background: #c7d2fe;
    }

    .btn-approve {
        background: #d1fae5;
    }

    .btn-approve svg {
        stroke: #10b981;
    }

    .btn-approve:hover {
        background: #a7f3d0;
    }

    .btn-reject {
        background: #fef3c7;
    }

    .btn-reject svg {
        stroke: #f59e0b;
    }

    .btn-reject:hover {
        background: #fde68a;
    }

    .btn-delete {
        background: #fee2e2;
    }

    .btn-delete svg {
        stroke: #ef4444;
    }

    .btn-delete:hover {
        background: #fecaca;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state svg {
        width: 64px;
        height: 64px;
        margin: 0 auto 1rem;
        stroke: #d1d5db;
        fill: none;
        stroke-width: 2;
    }

    .empty-state h3 {
        font-size: 1.125rem;
        font-weight: 600;
        color: #0a2540;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #6b7280;
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    .modal.active {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 12px;
        max-width: 500px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
    }

    .modal-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        font-size: 1.125rem;
        font-weight: 700;
        color: #0a2540;
    }

    .modal-close {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        background: #f3f4f6;
        border-radius: 6px;
        cursor: pointer;
    }

    .modal-close svg {
        width: 18px;
        height: 18px;
        stroke: #374151;
        fill: none;
        stroke-width: 2;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        padding: 1.5rem;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-group label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: #0a2540;
        margin-bottom: 0.5rem;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.875rem;
    }

    .required {
        color: #ef4444;
    }

    /* Pagination */
    .pagination-wrapper {
        margin-top: 1.5rem;
        display: flex;
        justify-content: center;
    }
</style>
@endpush

@push('scripts')
<script>
    function showRejectModal(umkmId) {
        const modal = document.getElementById('rejectModal');
        const form = document.getElementById('rejectForm');
        form.action = `/admin/umkm/${umkmId}/reject`;
        modal.classList.add('active');
    }

    function hideRejectModal() {
        const modal = document.getElementById('rejectModal');
        modal.classList.remove('active');
    }

    // Close modal on outside click
    document.getElementById('rejectModal')?.addEventListener('click', function (e) {
        if (e.target === this) {
            hideRejectModal();
        }
    });
</script>
@endpush
@endsection