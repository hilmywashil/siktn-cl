@extends('admin.layouts.admin-layout')

@section('title', 'Kelola Anggota')
@section('page-title', 'Kelola Anggota')

@php
    $activeMenu = 'anggota';
    $admin = auth()->guard('admin')->user();
@endphp

@push('styles')
    <style>
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
            color: #0B1354;
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

        .status-badge.pending {
            background: #fef3c7;
            color: #d97706;
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
            border-color: #0B1354;
        }

        .btn-icon.view:hover {
            background: rgba(11, 19, 84, 0.05);
        }

        .btn-icon.view svg {
            stroke: #0B1354;
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
    <div class="page-header">
        <h1>Kelola Anggota</h1>
        <p>Kelola dan verifikasi pendaftaran anggota CAAIP Alumni</p>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

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
                <span class="stat-title">Menunggu Verifikasi</span>
                <div class="stat-icon pending">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                </div>
            </div>
            <div class="stat-value">{{ $stats['pending'] }}</div>
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
        <a href="{{ route('admin.anggota.index', ['status' => 'pending']) }}"
            class="filter-tab {{ $status === 'pending' ? 'active' : '' }}">
            Pending ({{ $stats['pending'] }})
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

    {{-- Table --}}
    <div class="table-container">
        @if($anggota->count() > 0)
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pimpinan</th>
                            <th>Email</th>
                            <th>Perusahaan</th>
                            <th>Status</th>
                            <th>Tanggal Daftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($anggota as $index => $item)
                            <tr>
                                <td>{{ $anggota->firstItem() + $index }}</td>
                                <td>
                                    <strong>{{ $item->nama_pimpinan }}</strong>
                                </td>
                                <td>{{ $item->email_website_perusahaan }}</td>
                                <td>{{ $item->nama_perusahaan }}</td>
                                <td>
                                    <span class="status-badge {{ $item->status }}">
                                        @if($item->status === 'pending')
                                            <svg viewBox="0 0 8 8" width="8" height="8" fill="currentColor">
                                                <circle cx="4" cy="4" r="3" />
                                            </svg>
                                            Menunggu
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