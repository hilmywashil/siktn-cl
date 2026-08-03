@extends('admin.layouts.admin-layout')

@section('title', request()->get('jenis') === 'caretaker' ? 'Temu Karya Caretaker' : 'Temu Karya Organisasi')
@section('page-title', request()->get('jenis') === 'caretaker' ? 'Manajemen Temu Karya Caretaker' : 'Manajemen Temu Karya Karang Taruna')

@push('styles')
<style>
    /* SIKTN BENCHMARK FLOATING ACTION DROPDOWN (⋮) */
    .aksi-wrapper {
        position: relative;
        display: inline-block;
    }
    .btn-aksi-trigger {
        width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center;
        background: #022648; color: #fff; border: none; border-radius: 6px; cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 1px 3px rgba(2, 38, 72, 0.12);
    }
    .btn-aksi-trigger:hover {
        background: #0a3a6b; transform: scale(1.08) translateY(-1px); box-shadow: 0 4px 12px rgba(2, 38, 72, 0.25);
    }
    .aksi-dropdown {
        display: block; position: fixed; min-width: 190px; background: #fff; border: 1px solid #e5e7eb;
        border-radius: 6px; box-shadow: 0 14px 32px rgba(2, 38, 72, 0.18); padding: 6px; z-index: 9999;
        opacity: 0; visibility: hidden; transform: translateY(-8px) scale(0.96);
        transition: opacity 0.18s cubic-bezier(0.16, 1, 0.3, 1), transform 0.18s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.18s;
        pointer-events: none;
    }
    .aksi-dropdown.is-open {
        opacity: 1; visibility: visible; transform: translateY(0) scale(1); pointer-events: auto;
    }
    .aksi-item {
        display: flex; align-items: center; gap: 9px; width: 100%; padding: 0.55rem 0.65rem; font-size: 0.8125rem; font-weight: 600;
        border-radius: 4px; color: #111827; text-decoration: none !important; border: none; background: transparent;
        text-align: left; cursor: pointer; transition: all 0.18s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .aksi-item:hover { background: #f3f4f6; transform: translateX(4px); }
    .aksi-item.text-danger { color: #dc2626; }
    .aksi-item.text-danger:hover { background: #fee2e2; }

    /* SLIDE-OVER DRAWER MODAL BENCHMARK */
    .slide-drawer-overlay {
        position: fixed; inset: 0; background: rgba(1, 22, 47, 0.45); backdrop-filter: blur(3px); z-index: 10000;
        opacity: 0; visibility: hidden; transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .slide-drawer-overlay.is-active { opacity: 1; visibility: visible; }
    .slide-drawer {
        position: fixed; top: 0; right: 0; width: 520px; max-width: 90vw; height: 100vh; background: #ffffff;
        box-shadow: -10px 0 40px rgba(2, 38, 72, 0.2); z-index: 10001; transform: translateX(100%);
        transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1); display: flex; flex-direction: column;
    }
    .slide-drawer-overlay.is-active .slide-drawer { transform: translateX(0); }
    .swal-high-zindex { z-index: 100050 !important; }

    .btn-bulk-delete {
        height: 40px;
        padding: 0 16px;
        font-weight: 700;
        font-size: 0.8125rem;
        border-radius: 6px;
        background: #dc2626;
        color: #ffffff;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        white-space: nowrap;
        box-shadow: 0 1px 3px rgba(220, 38, 38, 0.2);
    }
    .btn-bulk-delete:hover {
        background: #b91c1c;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .summary-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid #e5e7eb;
        border-left: 4px solid #022648;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }
    .summary-card.active { border-left-color: #059669; }
    .summary-card.pending { border-left-color: #b7830f; }
    .summary-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .summary-icon svg {
        width: 24px;
        height: 24px;
    }
    .summary-info h4 {
        margin: 0;
        font-size: 0.85rem;
        color: #6b7280;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .summary-info .value {
        font-size: 1.75rem;
        font-weight: 800;
        color: #022648;
        margin-top: 0.25rem;
        font-family: 'Montserrat', sans-serif;
    }

    .main-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    .card-header-flex {
        padding: 1.5rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .card-title-text {
        font-size: 1.15rem;
        font-weight: 700;
        color: #022648;
        margin: 0;
    }
    .filter-bar {
        padding: 1rem 1.5rem;
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: center;
    }
    .filter-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .filter-select, .search-input {
        padding: 0.5rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.875rem;
        outline: none;
        background: white;
    }
    .filter-select:focus, .search-input:focus {
        border-color: #022648;
    }

    .table-responsive {
        overflow-x: auto;
    }
    .custom-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }
    .custom-table th {
        background: #f9fafb;
        padding: 1rem 1.25rem;
        font-size: 0.8rem;
        font-weight: 700;
        color: #374151;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #e5e7eb;
    }
    .custom-table td {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f3f4f6;
        font-size: 0.875rem;
        color: #1f2937;
        vertical-align: middle;
    }
    .custom-table tbody tr:hover {
        background: #f9fafb;
    }

    .badge-status {
        padding: 0.35rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }
    .badge-selesai { background: #d1fae5; color: #065f46; }
    .badge-pending { background: #fef3c7; color: #92400e; }
    .badge-caretaker { background: #fee2e2; color: #991b1b; }

    .btn-solid-navy {
        background: #022648;
        color: white;
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-solid-navy:hover {
        background: #1c2780;
        color: white;
    }
    .btn-action {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        border: 1px solid #e5e7eb;
        background: white;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #374151;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }
    .btn-action:hover {
        background: #f3f4f6;
    }
    .btn-action.delete:hover {
        color: #ef4444;
        border-color: #fca5a5;
        background: #fef2f2;
    }

    /* Modal Overlay */
    .modal-overlay {
        position: fixed;
        position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(2, 38, 72, 0.48); backdrop-filter: blur(4px);
        display: flex; align-items: center; justify-content: center; z-index: 9999; padding: 1.5rem;
        opacity: 0; visibility: hidden; transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .modal-overlay.show { opacity: 1; visibility: visible; }
    .modal-content-lg {
        background: #ffffff; border-radius: 12px; max-width: 680px; width: 100%;
        box-shadow: 0 24px 48px rgba(2, 38, 72, 0.25); border: 1px solid rgba(2, 38, 72, 0.1);
        overflow: hidden; transform: scale(0.94) translateY(12px);
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1); max-height: 85vh;
        display: flex; flex-direction: column;
    }
    .modal-overlay.show .modal-content-lg { transform: scale(1) translateY(0); }
    .modal-header-prof {
        padding: 1.2rem 1.5rem; background: linear-gradient(135deg, #022648 0%, #01162f 100%);
        color: white; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;
    }
    #temuKaryaForm {
        display: flex; flex-direction: column; flex: 1; overflow: hidden; margin: 0; width: 100% !important;
    }
    .modal-body-prof { padding: 1.5rem; overflow-y: auto; flex: 1; width: 100% !important; }
    .modal-footer-prof {
        padding: 1rem 1.5rem; background: #f8f9fc; border-top: 1px solid #e5e7eb;
        display: flex; justify-content: flex-end; gap: 0.75rem; flex-shrink: 0;
    }
    .modal-overlay .form-control {
        border: 1px solid #cbd5e1 !important; border-radius: 6px !important;
        padding: 0.55rem 0.875rem !important; font-size: 0.875rem !important;
        background-color: #ffffff !important; color: #0f172a !important; outline: none !important;
        transition: border-color 0.2s, box-shadow 0.2s !important; width: 100% !important;
    }
    .modal-overlay .form-control:focus {
        border-color: #022648 !important; box-shadow: 0 0 0 3px rgba(2, 38, 72, 0.12) !important;
    }
    .modal-overlay .btn-solid-navy {
        background-color: #022648 !important; color: #ffffff !important; border: none !important;
        padding: 0.55rem 1.25rem !important; border-radius: 6px !important; font-weight: 700 !important;
        cursor: pointer !important; display: inline-flex !important; align-items: center !important; gap: 0.5rem !important;
    }
    .modal-overlay .btn-solid-navy:hover { background-color: #01162f !important; }
    .modal-overlay .btn-outline-secondary {
        background-color: #ffffff !important; color: #374151 !important; border: 1px solid #d1d5db !important;
        padding: 0.55rem 1.25rem !important; border-radius: 6px !important; font-weight: 600 !important;
        cursor: pointer !important; display: inline-flex !important; align-items: center !important; gap: 0.5rem !important;
    }
    .modal-overlay .btn-outline-secondary:hover { background-color: #f3f4f6 !important; color: #022648 !important; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div style="padding: 1rem; background: #d1fae5; color: #065f46; border-radius: 8px; margin-bottom: 1.5rem; font-weight: 600;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="padding: 1rem; background: #fee2e2; color: #991b1b; border-radius: 8px; margin-bottom: 1.5rem; font-weight: 600;">
            <ul style="margin: 0; padding-left: 1.5rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Summary Cards -->
    <div class="summary-grid">
        <div class="summary-card">
            <div class="summary-icon" style="background: #eff6ff; color: #2563eb;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
            </div>
            <div class="summary-info">
                <h4>Temu Karya Selesai</h4>
                <div class="value">{{ $totalSelesai }}</div>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-icon" style="background: #fef2f2; color: #dc2626;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            </div>
            <div class="summary-info">
                <h4>Wilayah Belum Temu Karya</h4>
                <div class="value">{{ $totalBelumTemuKarya }}</div>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-icon" style="background: #fef3c7; color: #d97706;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </div>
            <div class="summary-info">
                <h4>Wilayah Caretaker</h4>
                <div class="value">{{ $totalCaretaker }}</div>
            </div>
        </div>
    </div>

    <!-- Main Card & Data Table -->
    <div class="main-card">
        <div class="card-header-flex">
            <h3 class="card-title-text">
                {{ request()->get('jenis') === 'caretaker' ? 'Daftar Wilayah Caretaker' : 'Daftar Pelaporan Temu Karya Wilayah' }}
            </h3>
            <div style="display: flex; gap: 0.5rem; align-items: center;">
                <button type="button" id="btnBulkDelete" onclick="confirmBulkDelete()" class="btn-bulk-delete" style="display: none;" disabled>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                    Hapus Terpilih (<span id="selectedCount">0</span>)
                </button>
                <button class="btn-solid-navy" onclick="openModal()">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Tambah {{ request()->get('jenis') === 'caretaker' ? 'Data Caretaker' : 'Temu Karya' }}
                </button>
            </div>
        </div>

        <!-- Filter Bar -->
        <form method="GET" action="{{ route('admin.temu-karya.index') }}" class="filter-bar">
            <input type="hidden" name="jenis" value="{{ request()->get('jenis', 'temu_karya') }}">
            <div class="filter-item">
                <label style="font-size: 0.85rem; font-weight: 600; color: #4b5563;">Level:</label>
                <select name="level" class="filter-select" onchange="this.form.submit()">
                    <option value="">Semua Level</option>
                    <option value="provinsi" {{ request()->level === 'provinsi' ? 'selected' : '' }}>Provinsi</option>
                    <option value="kab_kota" {{ request()->level === 'kab_kota' ? 'selected' : '' }}>Kabupaten / Kota</option>
                </select>
            </div>
            <div class="filter-item" style="margin-left: auto;">
                <input type="text" name="search" class="search-input" placeholder="Cari wilayah, lokasi..." value="{{ request()->search }}">
                <button type="submit" class="btn-solid-navy" style="padding: 0.5rem 1rem;">Cari</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">
                            <input type="checkbox" id="checkAll" onclick="toggleSelectAll(this)" style="cursor: pointer; width: 16px; height: 16px;">
                        </th>
                        <th>No</th>
                        <th>Wilayah</th>
                        <th>Level</th>
                        <th>Tanggal</th>
                        <th>Lokasi</th>
                        <th>Peserta</th>
                        <th>Status / SK</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($temuKaryas as $index => $item)
                        <tr>
                            <td style="text-align: center;">
                                <input type="checkbox" class="row-checkbox" value="{{ $item->id }}" onchange="updateBulkDeleteBtn()" style="cursor: pointer; width: 16px; height: 16px;">
                            </td>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div style="font-weight: 700; color: #111827;">{{ $item->wilayah }}</div>
                                @if($item->catatan)
                                    <div style="font-size: 0.75rem; color: #6b7280;">{{ Str::limit($item->catatan, 50) }}</div>
                                @endif
                            </td>
                            <td>
                                <span style="text-transform: capitalize; font-weight: 600;">{{ str_replace('_', ' ', $item->level) }}</span>
                            </td>
                            <td>{{ $item->tanggal_pelaksanaan ? $item->tanggal_pelaksanaan->format('d M Y') : '-' }}</td>
                            <td>{{ $item->lokasi ?? '-' }}</td>
                            <td>{{ $item->jumlah_peserta ? number_format($item->jumlah_peserta) . ' Orang' : '-' }}</td>
                            <td>
                                @if($item->status === 'selesai')
                                    <span class="badge-status badge-selesai">Selesai</span>
                                @elseif($item->status === 'caretaker')
                                    <span class="badge-status badge-caretaker">Caretaker</span>
                                @else
                                    <span class="badge-status badge-pending">Pending</span>
                                @endif                                @if($item->suratKeputusan)
                                    <div style="margin-top: 4px; padding: 4px 8px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 6px; font-size: 0.75rem; font-weight: 700; color: #065f46; display: inline-flex; align-items: center; gap: 4px;">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                                        SK: {{ $item->suratKeputusan->nomor_sk }}
                                    </div>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <!-- Action Dropdown Trigger (⋮) -->
                                <div class="aksi-wrapper">
                                    <button type="button" class="btn-aksi-trigger" data-target="dropdown-tk-{{ $item->id }}" aria-label="Menu Aksi">
                                        <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                            <circle cx="12" cy="5" r="1.75"></circle>
                                            <circle cx="12" cy="12" r="1.75"></circle>
                                            <circle cx="12" cy="19" r="1.75"></circle>
                                        </svg>
                                    </button>
                                    <div class="aksi-dropdown" id="dropdown-tk-{{ $item->id }}">
                                        <button type="button" class="aksi-item" onclick="openDetailDrawer({{ json_encode($item) }}, {{ json_encode($item->foto_dokumentasi_list) }})">
                                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                            Lihat Detail & Dokumen
                                        </button>
                                        <button type="button" class="aksi-item" onclick="editData({{ json_encode($item) }})">
                                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                            Edit Data
                                        </button>
                                        <div style="height: 1px; background: #e5e7eb; margin: 4px 0;"></div>
                                        <button type="button" class="aksi-item text-danger" onclick="confirmDelete(this, '{{ route('admin.temu-karya.destroy', $item->id) }}')">
                                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                            Hapus Data
                                        </button>
                                    </div>
                                    <form id="delete-form-{{ $item->id }}" action="{{ route('admin.temu-karya.destroy', $item->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 2rem; color: #9ca3af;">
                                Belum ada data pelaporan Temu Karya / Caretaker.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Form (Standard & Professional) -->
<div class="modal-overlay" id="temuKaryaModal" onclick="if(event.target===this) closeModal()">
    <div class="modal-content-lg">
        <div class="modal-header-prof">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(255,255,255,0.15); display: flex; align-items: center; justify-content: center;">
                    <svg viewBox="0 0 24 24" width="20" height="20" stroke="white" fill="none" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                </div>
                <div>
                    <h3 id="modalTitle" style="font-size: 1.05rem; font-weight: 800; color: white; margin: 0;">Tambah Data Temu Karya</h3>
                    <span style="font-size: 0.725rem; color: #94a3b8;">Kelola pelaporan Temu Karya dan Caretaker Wilayah</span>
                </div>
            </div>
            <button type="button" onclick="closeModal()" style="background: rgba(255,255,255,0.1); border: none; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer;">&times;</button>
        </div>

        <form id="temuKaryaForm" action="{{ route('admin.temu-karya.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <input type="hidden" name="jenis" value="{{ request()->get('jenis', 'temu_karya') }}">

            <div class="modal-body-prof">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label style="font-size: 0.8rem; font-weight: 700; color: #022648;">Nama Wilayah (Provinsi / Kab / Kota) <span style="color: red;">*</span></label>
                        <select name="wilayah" id="wilayah" class="form-control select2-basic-tags" required style="width: 100%;">
                            <option value="">-- Pilih atau Ketik Nama Wilayah --</option>
                            <optgroup label="Tingkat Provinsi">
                                @if(isset($daftarProvinsi))
                                    @foreach($daftarProvinsi as $key => $name)
                                        @if($key !== 'Semua' && $key !== 'Nasional')
                                            <option value="{{ $key }}">{{ $name }}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </optgroup>
                            <optgroup label="Tingkat Kabupaten / Kota">
                                @if(isset($daftarKabupaten))
                                    @foreach($daftarKabupaten as $kabKey => $kabName)
                                        <option value="{{ $kabKey }}">{{ $kabName }}</option>
                                    @endforeach
                                @endif
                            </optgroup>
                        </select>
                    </div>

                    <div class="form-group">
                        <label style="font-size: 0.8rem; font-weight: 700; color: #022648;">Tingkat Level Wilayah <span style="color: red;">*</span></label>
                        <select name="level" id="level" class="form-control select2-basic" required style="width: 100%;">
                            <option value="provinsi">Provinsi</option>
                            <option value="kab_kota">Kabupaten / Kota</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label style="font-size: 0.8rem; font-weight: 700; color: #022648;">Status Organisasi <span style="color: red;">*</span></label>
                        <select name="status" id="status" class="form-control select2-basic" required style="width: 100%;">
                            <option value="selesai">Temu Karya Selesai</option>
                            <option value="pending">Pending Temu Karya</option>
                            <option value="caretaker">Caretaker</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label style="font-size: 0.8rem; font-weight: 700; color: #022648;">Tanggal Pelaksanaan</label>
                        <input type="date" name="tanggal_pelaksanaan" id="tanggal_pelaksanaan" class="form-control" style="font-size: 0.85rem;">
                    </div>

                    <div class="form-group">
                        <label style="font-size: 0.8rem; font-weight: 700; color: #022648;">Jumlah Peserta (Orang)</label>
                        <input type="number" name="jumlah_peserta" id="jumlah_peserta" class="form-control" placeholder="0" style="font-size: 0.85rem;">
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label style="font-size: 0.8rem; font-weight: 700; color: #022648;">Lokasi Pelaksanaan</label>
                        <input type="text" name="lokasi" id="lokasi" class="form-control" placeholder="Lokasi gedung / kota" style="font-size: 0.85rem;">
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label style="font-size: 0.8rem; font-weight: 700; color: #022648;">Pilihan Surat Keputusan (SK) Master Sekretariat (Opsional)</label>
                        <select name="surat_keputusan_id" id="surat_keputusan_id" class="form-control select2-basic" style="width: 100%;">
                            <option value="">-- Tanpa SK / Pilih Dari Master SK --</option>
                            @if(isset($masterSks))
                                @foreach($masterSks as $sk)
                                    @php
                                        $now = \Carbon\Carbon::now()->startOfDay();
                                        $endDate = \Carbon\Carbon::parse($sk->tanggal_berakhir)->startOfDay();
                                        $daysLeft = (int) $now->diffInDays($endDate, false);
                                        $statusLabel = $daysLeft < 0 ? "[Expired {$daysLeft}d]" : ($daysLeft <= 30 ? "[Sisa {$daysLeft}d]" : '[Aktif]');
                                    @endphp
                                    <option value="{{ $sk->id }}">
                                        {{ $sk->nomor_sk }} - {{ $sk->judul_sk }} {{ $statusLabel }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="form-group">
                        <label style="font-size: 0.8rem; font-weight: 700; color: #022648;">Upload File SK Manual (PDF / Word)</label>
                        <input type="file" name="file_sk" id="file_sk" class="form-control" accept=".pdf,.doc,.docx,.jpg,.png" style="font-size: 0.8rem;">
                    </div>

                    <div class="form-group">
                        <label style="font-size: 0.8rem; font-weight: 700; color: #022648;">Dokumentasi Foto (Bisa Lebih dari 1 Foto)</label>
                        <input type="file" name="foto_dokumentasi[]" id="foto_dokumentasi" class="form-control" accept="image/*" multiple style="font-size: 0.8rem;">
                        <small style="font-size: 0.725rem; color: #64748b;">Tahan Ctrl / Shift untuk memilih beberapa foto sekaligus</small>
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label style="font-size: 0.8rem; font-weight: 700; color: #022648;">Link Tautan Dokumen (Google Drive)</label>
                        <input type="url" name="link_drive" id="link_drive" class="form-control" placeholder="https://drive.google.com/file/d/..." style="font-size: 0.85rem;">
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label style="font-size: 0.8rem; font-weight: 700; color: #022648;">Catatan / Keterangan Tambahan</label>
                        <textarea name="catatan" id="catatan" class="form-control" rows="3" placeholder="Catatan hasil temu karya / penunjukan caretaker..." style="font-size: 0.85rem;"></textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer-prof">
                <button type="button" class="btn-outline-secondary" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn-solid-navy" style="font-weight: 700;">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<!-- SLIDE-OVER DRAWER MODAL BENCHMARK -->
<div class="slide-drawer-overlay" id="drawerOverlay" onclick="closeDrawer()">
    <div class="slide-drawer" onclick="event.stopPropagation()">
        <div style="background: #022648; padding: 1.25rem 1.5rem; color: white; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <h3 id="drawerTitle" style="margin: 0; font-size: 1.05rem; font-weight: 800; color: #ffffff;">Detail Pelaporan Wilayah</h3>
                <span id="drawerSubtitle" style="font-size: 0.75rem; color: #94a3b8; font-weight: 600;">Temu Karya / Caretaker Karang Taruna</span>
            </div>
            <button type="button" onclick="closeDrawer()" style="background: rgba(255,255,255,0.15); border: none; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 1.2rem;">&times;</button>
        </div>

        <div style="padding: 1.5rem; flex: 1; overflow-y: auto;">
            {{-- Detail Fields Grid --}}
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1rem; margin-bottom: 1.25rem;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.85rem; font-size: 0.825rem;">
                    <div>
                        <div style="color: #64748b; font-weight: 600; font-size: 0.725rem; text-transform: uppercase;">Tingkat Level</div>
                        <div style="font-weight: 700; color: #022648;" id="drawerLevel">-</div>
                    </div>
                    <div>
                        <div style="color: #64748b; font-weight: 600; font-size: 0.725rem; text-transform: uppercase;">Tanggal Pelaksanaan</div>
                        <div style="font-weight: 700; color: #022648;" id="drawerTanggal">-</div>
                    </div>
                    <div>
                        <div style="color: #64748b; font-weight: 600; font-size: 0.725rem; text-transform: uppercase;">Lokasi</div>
                        <div style="font-weight: 700; color: #022648;" id="drawerLokasi">-</div>
                    </div>
                    <div>
                        <div style="color: #64748b; font-weight: 600; font-size: 0.725rem; text-transform: uppercase;">Jumlah Peserta</div>
                        <div style="font-weight: 700; color: #022648;" id="drawerPeserta">-</div>
                    </div>
                </div>
                <div style="margin-top: 0.85rem; padding-top: 0.75rem; border-top: 1px solid #cbd5e1; font-size: 0.825rem;">
                    <div style="color: #64748b; font-weight: 600; font-size: 0.725rem; text-transform: uppercase;">Catatan Pelaksanaan</div>
                    <div style="font-weight: 600; color: #374151; margin-top: 2px;" id="drawerCatatan">-</div>
                </div>
            </div>

            {{-- Dokumen SK Section --}}
            <div style="margin-bottom: 1.25rem;">
                <h4 style="font-size: 0.875rem; font-weight: 800; color: #022648; margin: 0 0 0.6rem 0; display: flex; align-items: center; gap: 6px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                    Surat Keputusan (SK) & File Dokumen
                </h4>
                <div id="drawerDocContainer">
                </div>
            </div>

            {{-- Galeri Foto Dokumentasi Section --}}
            <div>
                <h4 style="font-size: 0.875rem; font-weight: 800; color: #022648; margin: 0 0 0.6rem 0; display: flex; align-items: center; gap: 6px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                    Galeri Foto Dokumentasi
                </h4>
                <div id="drawerPhotosContainer" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); gap: 0.75rem;">
                </div>
            </div>
        </div>

        <div style="padding: 1rem 1.5rem; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end;">
            <button type="button" onclick="closeDrawer()" class="btn-solid-navy" style="font-size: 0.8125rem; font-weight: 700; padding: 7px 18px;">Tutup</button>
        </div>
    </div>
</div>

<!-- PHOTO LIGHTBOX MODAL WITH FULLSCREEN BUTTON BENCHMARK -->
<div id="photoLightboxModal" style="position: fixed; inset: 0; background: rgba(1, 22, 47, 0.92); z-index: 100000; display: none; align-items: center; justify-content: center; backdrop-filter: blur(8px);" onclick="closePhotoLightbox()">
    <div style="position: relative; max-width: 90vw; max-height: 90vh; display: flex; flex-direction: column; align-items: center;" onclick="event.stopPropagation()">
        {{-- Header Bar --}}
        <div style="width: 100%; display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem; color: white;">
            <div style="font-weight: 700; font-size: 0.95rem; color: #ffffff;" id="lightboxCaption">Foto Dokumentasi</div>
            <div style="display: flex; gap: 0.5rem; align-items: center;">
                <button type="button" onclick="toggleFullscreenLightboxImage()" style="background: rgba(255,255,255,0.2); border: none; color: white; padding: 6px 14px; border-radius: 6px; font-weight: 700; font-size: 0.8rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 3 21 3 21 9"></polyline><polyline points="9 21 3 21 3 15"></polyline><line x1="21" y1="3" x2="14" y2="10"></line><line x1="3" y1="21" x2="10" y2="14"></line></svg>
                    Fullscreen
                </button>
                <button type="button" onclick="closePhotoLightbox()" style="background: rgba(255,255,255,0.2); border: none; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 1.2rem;">&times;</button>
            </div>
        </div>

        {{-- Image Display --}}
        <div style="position: relative; display: flex; align-items: center; justify-content: center; max-height: 80vh;" id="lightboxContainer">
            <img id="lightboxImage" src="" style="max-width: 90vw; max-height: 80vh; object-fit: contain; border-radius: 8px; box-shadow: 0 20px 50px rgba(0,0,0,0.5);">
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let activeDropdown = null;

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-aksi-trigger').forEach(button => {
            button.addEventListener('click', function (e) {
                e.stopPropagation();
                const targetId = this.getAttribute('data-target');
                const dropdown = document.getElementById(targetId);

                if (activeDropdown && activeDropdown !== dropdown) {
                    activeDropdown.classList.remove('is-open');
                }

                if (dropdown.classList.contains('is-open')) {
                    dropdown.classList.remove('is-open');
                    activeDropdown = null;
                } else {
                    const rect = this.getBoundingClientRect();
                    dropdown.classList.add('is-open');
                    const dropdownHeight = dropdown.offsetHeight || 220;
                    const spaceBelow = window.innerHeight - rect.bottom;

                    if (spaceBelow < dropdownHeight && rect.top > dropdownHeight) {
                        dropdown.style.top = (rect.top - dropdownHeight - 4) + 'px';
                    } else {
                        dropdown.style.top = (rect.bottom + 4) + 'px';
                    }
                    dropdown.style.left = Math.min(window.innerWidth - 190, rect.right - 180) + 'px';
                    activeDropdown = dropdown;
                }
            });
        });

        if (typeof $.fn.select2 !== 'undefined') {
            $('.select2-basic').select2({
                dropdownParent: $('#temuKaryaModal'),
                width: '100%'
            });
            $('.select2-basic-tags').select2({
                tags: true,
                dropdownParent: $('#temuKaryaModal'),
                width: '100%',
                placeholder: '-- Pilih atau Ketik Nama Wilayah --'
            });

            const provinceList = @json(array_keys($daftarProvinsi ?? [])).filter(p => p !== 'Semua' && p !== 'Nasional').map(p => p.toLowerCase());
            
            $('#wilayah').on('change', function() {
                const val = $(this).val();
                if (!val) return;

                if (provinceList.includes(val.trim().toLowerCase())) {
                    $('#level').val('provinsi').trigger('change');
                } else {
                    $('#level').val('kab_kota').trigger('change');
                }
            });
        }

        document.addEventListener('click', function () {
            if (activeDropdown) {
                activeDropdown.classList.remove('is-open');
                activeDropdown = null;
            }
        });
    });

    function previewDoc(url, title) {
        if (!url) return;

        const isDrive = url.includes('drive.google.com');
        const isDriveFolder = isDrive && (url.includes('/folders/') || !url.includes('/file/d/'));
        const isImage = /\.(jpg|jpeg|png|gif|webp|bmp)(\?|$)/i.test(url);
        const isPdf = url.endsWith('.pdf');

        let viewerContent = '';

        if (isDriveFolder) {
            // Drive folder — iframe will always 403, show a card with open button
            viewerContent = `
                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 2.5rem 1rem; background: #f8fafc; border-radius: 10px; border: 2px dashed #cbd5e1; gap: 1rem;">
                    <svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="#022648" stroke-width="1.5">
                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                    </svg>
                    <div style="font-weight: 700; color: #022648; font-size: 1rem;">Folder Google Drive</div>
                    <div style="color: #64748b; font-size: 0.85rem; text-align: center;">Folder Google Drive tidak dapat di-embed langsung.<br>Klik tombol di bawah untuk membuka folder dokumentasi.</div>
                    <a href="${url}" target="_blank" style="display: inline-flex; align-items: center; gap: 8px; background: #022648; color: white; padding: 9px 20px; border-radius: 6px; font-weight: 700; font-size: 0.875rem; text-decoration: none;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                        Buka Folder Drive
                    </a>
                </div>`;
        } else if (isDrive && url.includes('/file/d/')) {
            // Drive file — try embed with /preview
            const matches = url.match(/\/file\/d\/([^\/]+)/);
            const embedUrl = matches ? `https://drive.google.com/file/d/${matches[1]}/preview` : url;
            viewerContent = `<iframe src="${embedUrl}" style="width: 100%; height: 68vh; border: none; border-radius: 8px;" allow="autoplay"></iframe>`;
        } else if (isImage) {
            viewerContent = `<img src="${url}" style="max-width: 100%; max-height: 70vh; object-fit: contain; border-radius: 8px;">`;
        } else if (isPdf) {
            viewerContent = `<iframe src="${url}" style="width: 100%; height: 70vh; border: none; border-radius: 8px;"></iframe>`;
        } else {
            viewerContent = `
                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 2.5rem 1rem; background: #f8fafc; border-radius: 10px; border: 2px dashed #cbd5e1; gap: 1rem;">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#022648" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    <div style="font-weight: 700; color: #022648;">Pratinjau tidak tersedia</div>
                    <a href="${url}" target="_blank" style="display: inline-flex; align-items: center; gap: 8px; background: #022648; color: white; padding: 9px 20px; border-radius: 6px; font-weight: 700; font-size: 0.875rem; text-decoration: none;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                        Buka / Download File
                    </a>
                </div>`;
        }

        Swal.fire({
            title: `<div style="color: #022648; font-weight: 800; font-size: 1.1rem;">${title}</div>`,
            html: `
                ${!isDriveFolder ? `<div style="margin-bottom: 0.75rem; display: flex; justify-content: flex-end; gap: 8px;">
                    <a href="${url}" target="_blank" style="font-size: 0.75rem; padding: 5px 12px; text-decoration: none; border-radius: 4px; display: inline-flex; align-items: center; gap: 6px; font-weight: 700; background: #022648; color: white;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                        Buka Sumber Asli / Download
                    </a>
                </div>` : ''}
                ${viewerContent}
            `,
            width: '900px',
            showCloseButton: true,
            confirmButtonText: 'Tutup Pratinjau',
            confirmButtonColor: '#022648',
            customClass: { container: 'swal-high-zindex' }
        });
    }

    function previewImageModal(url, title) {
        Swal.fire({
            title: `<div style="color: #022648; font-weight: 800; font-size: 1.1rem;">${title}</div>`,
            imageUrl: url,
            imageAlt: title,
            width: '700px',
            showCloseButton: true,
            confirmButtonText: 'Tutup Gambar',
            confirmButtonColor: '#022648'
        });
    }

    function openDetailDrawer(data, photos) {
        document.getElementById('drawerTitle').innerText = data.wilayah;
        document.getElementById('drawerSubtitle').innerText = (data.jenis === 'caretaker' ? 'Caretaker' : 'Temu Karya') + ' - Status: ' + data.status.toUpperCase();
        
        document.getElementById('drawerLevel').innerText = data.level ? data.level.toUpperCase() : '-';
        document.getElementById('drawerTanggal').innerText = data.tanggal_pelaksanaan ? data.tanggal_pelaksanaan.substring(0, 10) : '-';
        document.getElementById('drawerLokasi').innerText = data.lokasi || '-';
        document.getElementById('drawerPeserta').innerText = (data.jumlah_peserta || 0) + ' Orang';
        document.getElementById('drawerCatatan').innerText = data.catatan || 'Tidak ada catatan tambahan.';

        // SK Docs container
        let docHtml = '';
        if (data.surat_keputusan) {
            docHtml += `
                <div style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 0.75rem; border-radius: 6px; margin-bottom: 0.5rem;">
                    <div style="font-weight: 700; color: #065f46; display: flex; align-items: center; gap: 6px;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                        SK Master: ${data.surat_keputusan.nomor_sk}
                    </div>
                    <div style="font-size: 0.8rem; color: #374151; margin-top: 2px;">${data.surat_keputusan.judul_sk}</div>
                    ${data.surat_keputusan.link_drive ? `<button type="button" onclick="previewDoc('${data.surat_keputusan.link_drive}', 'SK Master - ${data.surat_keputusan.nomor_sk}')" style="margin-top: 6px; font-size: 0.75rem; color: #2563eb; font-weight: 700; background: none; border: none; cursor: pointer; text-decoration: underline; display: inline-flex; align-items: center; gap: 4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg> Pratinjau Google Drive SK</button>` : ''}
                </div>
            `;
        }
        if (data.file_sk) {
            let fileUrl = '/storage/' + data.file_sk;
            docHtml += `
                <div style="background: #eff6ff; border: 1px solid #bfdbfe; padding: 0.75rem; border-radius: 6px; margin-bottom: 0.5rem; display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <div style="font-weight: 700; color: #1e40af; display: flex; align-items: center; gap: 6px;">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
                            File SK Upload
                        </div>
                        <div style="font-size: 0.75rem; color: #60a5fa;">Dokumen Pelaporan Terlampir</div>
                    </div>
                    <button type="button" onclick="previewDoc('${fileUrl}', 'File SK - ${data.wilayah}')" class="btn-solid-navy" style="font-size: 0.75rem; padding: 5px 12px; border-radius: 4px; font-weight: 700;">Pratinjau File</button>
                </div>
            `;
        }
        if (data.link_drive) {
            docHtml += `
                <div style="background: #f8fafc; border: 1px solid #cbd5e1; padding: 0.75rem; border-radius: 6px; display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <div style="font-weight: 700; color: #022648; display: flex; align-items: center; gap: 6px;">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
                            Drive Manual
                        </div>
                        <div style="font-size: 0.75rem; color: #64748b;">Google Drive Tautan Manual</div>
                    </div>
                    <button type="button" onclick="previewDoc('${data.link_drive}', 'Drive Dokumentasi - ${data.wilayah}')" class="btn-solid-navy" style="font-size: 0.75rem; padding: 5px 12px; border-radius: 4px; font-weight: 700;">Pratinjau Drive</button>
                </div>
            `;
        }

        if (!docHtml) {
            docHtml = `<div style="font-size: 0.8rem; color: #94a3b8;">Belum ada dokumen SK terlampir.</div>`;
        }
        document.getElementById('drawerDocContainer').innerHTML = docHtml;

        // Photos gallery container
        let photoHtml = '';
        if (photos && photos.length > 0) {
            photos.forEach((fUrl, pIdx) => {
                photoHtml += `
                    <div class="photo-card" style="position: relative; border-radius: 6px; overflow: hidden; border: 1.5px solid #022648; height: 100px; cursor: pointer;" onclick="previewImageModal('${fUrl}', 'Foto #${pIdx+1} - ${data.wilayah}')">
                        <img src="${fUrl}" onerror="this.closest('.photo-card').style.display='none'" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    </div>
                `;
            });
        } else {
            photoHtml = `<div style="grid-column: 1 / -1; font-size: 0.8rem; color: #94a3b8;">Belum ada foto dokumentasi terlampir.</div>`;
        }
        document.getElementById('drawerPhotosContainer').innerHTML = photoHtml;

        document.getElementById('drawerOverlay').classList.add('is-active');
    }

    function closeDrawer() {
        document.getElementById('drawerOverlay').classList.remove('is-active');
    }

    function previewImageModal(url, title) {
        document.getElementById('lightboxCaption').innerText = title || 'Foto Dokumentasi';
        const img = document.getElementById('lightboxImage');
        img.src = url;
        document.getElementById('photoLightboxModal').style.display = 'flex';
    }

    function closePhotoLightbox() {
        document.getElementById('photoLightboxModal').style.display = 'none';
        if (document.fullscreenElement) {
            document.exitFullscreen();
        }
    }

    function toggleFullscreenLightboxImage() {
        const container = document.getElementById('lightboxContainer');
        if (!document.fullscreenElement) {
            if (container.requestFullscreen) {
                container.requestFullscreen();
            } else if (container.webkitRequestFullscreen) {
                container.webkitRequestFullscreen();
            }
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            }
        }
    }

    function openModal() {
        document.getElementById('temuKaryaForm').reset();
        document.getElementById('temuKaryaForm').action = "{{ route('admin.temu-karya.store') }}";
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('surat_keputusan_id').value = '';
        document.getElementById('modalTitle').innerText = 'Tambah Data {{ request()->get('jenis') === 'caretaker' ? 'Caretaker' : 'Temu Karya' }}';
        
        if (typeof $.fn.select2 !== 'undefined') {
            $('#wilayah, #level, #status, #surat_keputusan_id').val('').trigger('change');
        }

        document.getElementById('temuKaryaModal').classList.add('show');
    }

    function closeModal() {
        document.getElementById('temuKaryaModal').classList.remove('show');
    }

    function editData(data) {
        document.getElementById('temuKaryaForm').action = "/admin/temu-karya/" + data.id;
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('modalTitle').innerText = 'Edit Data ' + data.wilayah;

        document.getElementById('level').value = data.level;
        document.getElementById('status').value = data.status;
        document.getElementById('tanggal_pelaksanaan').value = data.tanggal_pelaksanaan ? data.tanggal_pelaksanaan.substring(0, 10) : '';
        document.getElementById('jumlah_peserta').value = data.jumlah_peserta || 0;
        document.getElementById('lokasi').value = data.lokasi || '';
        document.getElementById('surat_keputusan_id').value = data.surat_keputusan_id || '';
        document.getElementById('link_drive').value = data.link_drive || '';
        document.getElementById('catatan').value = data.catatan || '';

        if (typeof $.fn.select2 !== 'undefined') {
            if ($('#wilayah option[value="' + data.wilayah + '"]').length === 0) {
                var newOption = new Option(data.wilayah, data.wilayah, true, true);
                $('#wilayah').append(newOption).trigger('change');
            } else {
                $('#wilayah').val(data.wilayah).trigger('change');
            }
            $('#level').val(data.level).trigger('change');
            $('#status').val(data.status).trigger('change');
            $('#surat_keputusan_id').val(data.surat_keputusan_id || '').trigger('change');
        }

        document.getElementById('temuKaryaModal').classList.add('show');
    }

    function confirmDelete(button, url) {
        Swal.fire({
            title: 'Hapus Data?',
            text: "Data Temu Karya / Caretaker ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#cbd5e1',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                let formId = button.nextElementSibling.id;
                document.getElementById(formId).submit();
            }
        });
    }

    function toggleSelectAll(master) {
        document.querySelectorAll('.row-checkbox').forEach(cb => {
            cb.checked = master.checked;
        });
        updateBulkDeleteBtn();
    }

    function updateBulkDeleteBtn() {
        const checked = document.querySelectorAll('.row-checkbox:checked');
        const count = checked.length;
        const btn = document.getElementById('btnBulkDelete');
        const countSpan = document.getElementById('selectedCount');
        const masterCb = document.getElementById('checkAll');

        if (countSpan) countSpan.textContent = count;
        
        if (btn) {
            if (count > 0) {
                btn.style.display = 'inline-flex';
                btn.disabled = false;
            } else {
                btn.style.display = 'none';
                btn.disabled = true;
            }
        }

        const totalRows = document.querySelectorAll('.row-checkbox').length;
        if (masterCb) {
            masterCb.checked = (totalRows > 0 && count === totalRows);
        }
    }

    function confirmBulkDelete() {
        const checked = document.querySelectorAll('.row-checkbox:checked');
        const ids = Array.from(checked).map(cb => cb.value);

        if (ids.length === 0) return;

        Swal.fire({
            title: 'Hapus Data Terpilih?',
            text: `Sebanyak ${ids.length} data Temu Karya / Caretaker yang dipilih akan dihapus permanen!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#cbd5e1',
            confirmButtonText: 'Ya, Hapus Masal!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('admin.temu-karya.bulk-delete') }}";

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = "{{ csrf_token() }}";
                form.appendChild(csrfToken);

                ids.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = id;
                    form.appendChild(input);
                });

                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endpush
