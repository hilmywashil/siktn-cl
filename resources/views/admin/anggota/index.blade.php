@extends('admin.layouts.admin-layout')

@section('title', 'Kelola Anggota')
@section('page-title', 'Kelola Anggota')

@php
    $activeMenu = 'anggota';
    $admin = auth()->guard('admin')->user();
@endphp

@push('styles')
    <style>
        .btn-bulk {
            font-family: 'Montserrat', sans-serif;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #ef4444;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            color: white;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn-bulk:hover {
            background: #dc2626;
            transform: translateY(-1px);
        }

        .page-header {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .page-header h1 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .page-header p {
            color: #6b7280;
            font-size: 0.875rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .stat-title {
            font-size: 0.875rem;
            color: #6b7280;
            font-weight: 500;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-icon svg {
            width: 20px;
            height: 20px;
            stroke-width: 2;
        }

        .stat-icon.total {
            background: rgba(11, 19, 84, 0.1);
            color: #022648;
        }

        .stat-icon.pending {
            background: #fef3c7;
            color: #d97706;
        }

        .stat-icon.approved {
            background: #d1fae5;
            color: #059669;
        }

        .stat-icon.rejected {
            background: #fee2e2;
            color: #dc2626;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #0a2540;
        }

        .filter-tabs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .filter-tab {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            background: white;
            color: #6b7280;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .filter-tab:hover {
            background: #f9fafb;
            border-color: #d1d5db;
        }

        .filter-tab.active {
            background: #0a2540;
            color: white;
            border-color: #0a2540;
        }

        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1000px;
        }

        .table thead {
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
        }

        .table th {
            padding: 1rem;
            text-align: left;
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            white-space: nowrap;
        }

        .table td {
            padding: 1rem;
            border-bottom: 1px solid #f3f4f6;
            font-size: 0.875rem;
        }

        .table tbody tr:hover {
            background: #f9fafb;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .status-badge.pending_verification {
            background: #fef3c7;
            color: #d97706;
        }

        .status-badge.pending_profile {
            background: #f3f4f6;
            color: #4b5563;
        }

        .status-badge.approved {
            background: #d1fae5;
            color: #059669;
        }

        .status-badge.rejected {
            background: #fee2e2;
            color: #dc2626;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: nowrap;
        }

        .btn-icon {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            flex-shrink: 0;
        }

        .btn-icon:hover {
            background: #f9fafb;
            border-color: #d1d5db;
        }

        .btn-icon svg {
            width: 16px;
            height: 16px;
            stroke: #6b7280;
            fill: none;
            stroke-width: 2;
        }

        .btn-icon.view {
            border-color: #022648;
        }

        .btn-icon.view:hover {
            background: rgba(11, 19, 84, 0.05);
        }

        .btn-icon.view svg {
            stroke: #022648;
        }

        .btn-icon.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* Pagination Style */
        .pagination-wrapper {
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
            align-items: center;
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
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
        }

        .pagination-btn:hover:not(.disabled) {
            background: #f3f4f6;
        }

        .pagination-btn.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        .pagination-btn.active {
            background: #0a2540;
            color: white;
            border-color: #0a2540;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #6b7280;
        }

        .empty-state svg {
            width: 64px;
            height: 64px;
            margin: 0 auto 1rem;
            stroke: #d1d5db;
        }

        .alert {
            padding: 1rem 1.25rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #6ee7b7;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header {
                padding: 1rem;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .stat-card {
                padding: 1rem;
            }

            .stat-value {
                font-size: 1.5rem;
            }

            .table {
                min-width: 900px;
            }

            .pagination-wrapper {
                flex-direction: column;
                gap: 1rem;
                padding: 1.25rem 1rem;
                align-items: flex-start;
            }

            .pagination-info {
                font-size: 0.8125rem;
                width: 100%;
            }

            .pagination-buttons {
                width: 100%;
                flex-wrap: wrap;
                justify-content: center;
            }

            .pagination-btn {
                padding: 0.5rem 0.75rem;
                font-size: 0.8125rem;
                min-width: 36px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1>Kelola Anggota</h1>
            <p>Kelola dan verifikasi pendaftaran anggota Karang Taruna</p>
        </div>
        <div style="display: flex; gap: 0.75rem;">
            @if(in_array($admin->category, ['super_admin', 'pimpinan', 'pnkt']))
            <a href="{{ route('admin.anggota.export', request()->query()) }}" class="btn btn-outline" style="background: white; color: #10b981; border: 1px solid #10b981; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; text-decoration: none; transition: background 0.2s;" onmouseover="this.style.background='#ecfdf5'" onmouseout="this.style.background='white'">
                <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                Export Excel / CSV
            </a>
            @endif

            <a href="{{ route('admin.anggota.create') }}" class="btn btn-primary" style="background: #0a2540; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; text-decoration: none;">
                <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" fill="none" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah Anggota
            </a>
        </div>
    </div>



    {{-- Statistics --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-title">Total Pendaftar</span>
                <div class="stat-icon total">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                </div>
            </div>
            <div class="stat-value">{{ $stats['total'] }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-title">Belum Lengkapi Profil</span>
                <div class="stat-icon pending_profile" style="background: #f3f4f6; color: #4b5563;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                </div>
            </div>
            <div class="stat-value">{{ $stats['pending_profile'] }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-title">Menunggu Approve</span>
                <div class="stat-icon pending">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                </div>
            </div>
            <div class="stat-value">{{ $stats['pending_verification'] }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-title">Disetujui</span>
                <div class="stat-icon approved">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                        <polyline points="22 4 12 14.01 9 11.01" />
                    </svg>
                </div>
            </div>
            <div class="stat-value">{{ $stats['approved'] }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-title">Ditolak</span>
                <div class="stat-icon rejected">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="15" y1="9" x2="9" y2="15" />
                        <line x1="9" y1="9" x2="15" y2="15" />
                    </svg>
                </div>
            </div>
            <div class="stat-value">{{ $stats['rejected'] }}</div>
        </div>
    </div>

    {{-- Filter Tabs --}}
    <div class="filter-tabs">
        <a href="{{ route('admin.anggota.index', ['status' => 'all']) }}"
            class="filter-tab {{ $status === 'all' ? 'active' : '' }}">
            Semua ({{ $stats['total'] }})
        </a>
        <a href="{{ route('admin.anggota.index', ['status' => 'pending_profile']) }}"
            class="filter-tab {{ $status === 'pending_profile' ? 'active' : '' }}">
            Belum Lengkapi Profil ({{ $stats['pending_profile'] }})
        </a>
        <a href="{{ route('admin.anggota.index', ['status' => 'pending_verification']) }}"
            class="filter-tab {{ $status === 'pending_verification' ? 'active' : '' }}">
            Menunggu Approve ({{ $stats['pending_verification'] }})
        </a>
        <a href="{{ route('admin.anggota.index', ['status' => 'approved']) }}"
            class="filter-tab {{ $status === 'approved' ? 'active' : '' }}">
            Disetujui ({{ $stats['approved'] }})
        </a>
        <a href="{{ route('admin.anggota.index', ['status' => 'rejected']) }}"
            class="filter-tab {{ $status === 'rejected' ? 'active' : '' }}">
            Ditolak ({{ $stats['rejected'] }})
        </a>
    </div>

    {{-- Bulk Action & Table --}}
    <div class="table-container">
        @if($anggota->count() > 0)
            <div style="padding: 1rem 1.5rem; border-bottom: 1px solid #e5e7eb; display: none;" id="bulk-action-container">
                <button type="button" onclick="bulkDestroy()" class="btn-bulk">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                        <polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                    Hapus Terpilih (<span id="selected-count">0</span>)
                </button>
            </div>
            <div class="table-wrapper">
                <table class="table" id="anggota-table">
                    <thead>
                        <tr>
                            <th style="width: 40px; text-align: center;">
                                <input type="checkbox" id="select-all" style="cursor: pointer;">
                            </th>
                            <th>No</th>
                            <th>Nama Lengkap</th>
                            <th>Username</th>
                            <th>Jabatan</th>
                            <th>Domisili</th>
                            <th>Status</th>
                            <th>Tanggal Daftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($anggota as $index => $item)
                            <tr>
                                <td style="text-align: center;">
                                    <input type="checkbox" class="row-checkbox" value="{{ $item->id }}" style="cursor: pointer;">
                                </td>
                                <td>{{ $anggota->firstItem() + $index }}</td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        <div style="width: 38px; height: 38px; border-radius: 50%; background: #C59217; display: flex; align-items: center; justify-content: center; color: #022648; font-weight: 700; font-size: 0.875rem; flex-shrink: 0; overflow: hidden; border: 2px solid #e5e7eb;">
                                            @if($item->foto_diri)
                                                <img src="{{ Storage::url($item->foto_diri) }}" alt="{{ $item->nama_lengkap }}" style="width: 100%; height: 100%; object-fit: cover;">
                                            @else
                                                @php
                                                    $sourceName = $item->nama_lengkap ?? $item->username;
                                                    $words = explode(' ', $sourceName);
                                                    $initials = '';
                                                    foreach (array_slice($words, 0, 2) as $word) {
                                                        $initials .= strtoupper(substr($word, 0, 1));
                                                    }
                                                @endphp
                                                {{ $initials }}
                                            @endif
                                        </div>
                                        <strong>{{ $item->nama_lengkap ?? '-' }}</strong>
                                    </div>
                                </td>
                                <td>{{ $item->username }}</td>
                                <td>{{ $item->jabatan ?? '-' }}</td>
                                <td>{{ $item->domisili ?? '-' }}</td>
                                <td>
                                    <span class="status-badge {{ $item->status }}">
                                        @if($item->status === 'pending_verification')
                                            <svg viewBox="0 0 8 8" width="8" height="8" fill="currentColor">
                                                <circle cx="4" cy="4" r="3" />
                                            </svg>
                                            Menunggu Approve
                                        @elseif($item->status === 'pending_profile')
                                            <svg viewBox="0 0 8 8" width="8" height="8" fill="currentColor">
                                                <circle cx="4" cy="4" r="3" />
                                            </svg>
                                            Belum Lengkapi Profil
                                        @elseif($item->status === 'approved')
                                            <svg viewBox="0 0 8 8" width="8" height="8" fill="currentColor">
                                                <circle cx="4" cy="4" r="3" />
                                            </svg>
                                            Disetujui
                                        @else
                                            <svg viewBox="0 0 8 8" width="8" height="8" fill="currentColor">
                                                <circle cx="4" cy="4" r="3" />
                                            </svg>
                                            Ditolak
                                        @endif
                                    </span>
                                </td>
                                <td>{{ $item->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.anggota.show', $item) }}" 
                                           class="btn-icon view" 
                                           title="Lihat Detail & Verifikasi">
                                            <svg viewBox="0 0 24 24">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                        </a>
                                        <button type="button" onclick="confirmDelete({{ $item->id }})" class="btn-icon" style="color: #ef4444; background: #fef2f2;" title="Hapus Data">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            </svg>
                                        </button>
                                        <form id="delete-form-{{ $item->id }}" action="{{ route('admin.anggota.destroy', $item) }}" method="POST" style="display:none;">
                                            @csrf
                                            @method('DELETE')
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
                <div class="pagination-info">
                    Menampilkan {{ $anggota->firstItem() }} - {{ $anggota->lastItem() }} dari {{ $anggota->total() }} anggota
                </div>
                <div class="pagination-buttons">
                    @if ($anggota->onFirstPage())
                        <span class="pagination-btn disabled">Previous</span>
                    @else
                        <a href="{{ $anggota->appends(['status' => $status])->previousPageUrl() }}" class="pagination-btn">
                            Previous
                        </a>
                    @endif
                    
                    @foreach($anggota->getUrlRange(1, $anggota->lastPage()) as $page => $url)
                        @if ($page == $anggota->currentPage())
                            <span class="pagination-btn active">{{ $page }}</span>
                        @else
                            <a href="{{ $anggota->appends(['status' => $status])->url($page) }}" class="pagination-btn">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                    
                    @if ($anggota->hasMorePages())
                        <a href="{{ $anggota->appends(['status' => $status])->nextPageUrl() }}" class="pagination-btn">
                            Next
                        </a>
                    @else
                        <span class="pagination-btn disabled">Next</span>
                    @endif
                </div>
            </div>
        @else
            <div class="empty-state">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                </svg>
                <h3>Tidak ada data</h3>
                <p>Belum ada pendaftar untuk kategori ini.</p>
            </div>
        @endif
    </div>


@endsection

@push('scripts')
<script>
    // Bulk Select Logic
    const selectAllBtn = document.getElementById('select-all');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    const bulkActionContainer = document.getElementById('bulk-action-container');
    const selectedCountSpan = document.getElementById('selected-count');

    function updateBulkActionUI() {
        if(!bulkActionContainer) return;
        const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
        selectedCountSpan.textContent = checkedCount;
        if (checkedCount > 0) {
            bulkActionContainer.style.display = 'block';
        } else {
            bulkActionContainer.style.display = 'none';
        }
    }

    if (selectAllBtn) {
        selectAllBtn.addEventListener('change', function() {
            rowCheckboxes.forEach(cb => {
                cb.checked = this.checked;
            });
            updateBulkActionUI();
        });

        rowCheckboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                const allChecked = document.querySelectorAll('.row-checkbox:checked').length === rowCheckboxes.length;
                selectAllBtn.checked = allChecked;
                updateBulkActionUI();
            });
        });
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Data Anggota?',
            text: "Data akan dipindahkan ke daftar Data Terhapus.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#f3f4f6',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    function bulkDestroy() {
        const checked = document.querySelectorAll('.row-checkbox:checked');
        if (checked.length === 0) return;

        Swal.fire({
            title: 'Hapus Massal?',
            text: `Yakin ingin memindahkan ${checked.length} data anggota ini ke Data Terhapus?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#f3f4f6',
            confirmButtonText: 'Ya, Hapus Semua',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const ids = Array.from(checked).map(cb => cb.value);
                
                fetch("{{ route('admin.anggota.bulk-destroy') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ ids: ids })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'deleted') {
                        Toast.fire({ 
                            icon: 'success', 
                            iconColor: '#dc2626',
                            title: data.message,
                            customClass: { popup: 'swal2-toast-deleted' }
                        });
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        Toast.fire({ icon: 'error', title: 'Terjadi kesalahan.' });
                    }
                })
                .catch(err => Toast.fire({ icon: 'error', title: 'Terjadi kesalahan sistem.' }));
            }
        });
    }
</script>
@endpush