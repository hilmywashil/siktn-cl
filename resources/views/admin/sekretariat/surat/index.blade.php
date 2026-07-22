@extends('admin.layouts.admin-layout')

@section('title', ($tipe == 'masuk' ? 'Surat Masuk' : 'Surat Keluar') . ' - SIKTN Admin')
@section('page-title', 'Sekretariat - ' . ($tipe == 'masuk' ? 'Surat Masuk' : 'Surat Keluar'))

@push('styles')
<style>
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
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-icon.approved { background: #d1fae5; color: #059669; }
    .stat-icon.pending { background: #fef3c7; color: #d97706; }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: #0a2540;
        line-height: 1.2;
    }

    .stat-title {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 500;
    }

    /* Native SIKTN Filter Tabs */
    .filter-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .filter-tab {
        padding: 0.5rem 1.25rem;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        background: white;
        color: #6b7280;
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-tab:hover {
        background: #f9fafb;
        border-color: #d1d5db;
        color: #111827;
    }

    .filter-tab.active {
        background: #0a2540;
        color: white;
        border-color: #0a2540;
    }

    .tab-badge {
        background: rgba(0, 0, 0, 0.08);
        padding: 0.125rem 0.5rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .filter-tab.active .tab-badge {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }

    .tab-badge-danger {
        background: #ef4444;
        color: white;
        padding: 0.125rem 0.5rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .card-box {
        background: white;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
    }

    .action-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .search-filter-group {
        display: flex;
        gap: 0.75rem;
        align-items: center;
        flex-wrap: wrap;
    }

    .form-control {
        padding: 0.5rem 0.875rem;
        border-radius: 8px;
        border: 1px solid #d1d5db;
        font-size: 0.875rem;
        outline: none;
        background: white;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s;
        border: 1px solid transparent;
        text-decoration: none;
    }

    .btn-primary {
        background: #0a2540;
        color: white;
    }

    .btn-primary:hover {
        background: #0d3154;
    }

    .btn-secondary {
        background: #f3f4f6;
        color: #374151;
        border-color: #e5e7eb;
    }

    .btn-secondary:hover {
        background: #e5e7eb;
    }

    .btn-danger {
        background: #fef2f2;
        color: #dc2626;
        border-color: #fecaca;
    }

    .btn-danger:hover {
        background: #fee2e2;
    }

    .btn-info {
        background: #e0f2fe;
        color: #0369a1;
        border-color: #bae6fd;
    }

    .btn-info:hover {
        background: #bae6fd;
    }

    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.8125rem;
    }

    .table-container {
        background: white;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th {
        background: #f9fafb;
        padding: 0.875rem 1rem;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 700;
        color: #4b5563;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid #e5e7eb;
    }

    .table td {
        padding: 1rem;
        border-bottom: 1px solid #f3f4f6;
        font-size: 0.875rem;
        color: #1f2937;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .status-badge.pending { background: #fef3c7; color: #d97706; }
    .status-badge.approved { background: #d1fae5; color: #059669; }
    .status-badge.rejected { background: #fee2e2; color: #dc2626; }
    .status-badge.draft { background: #f3f4f6; color: #6b7280; }

    /* Modal Overlay & Card */
    .modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15, 23, 42, 0.6);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 999;
        padding: 1rem;
    }
    
    .modal-overlay.active {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 12px;
        max-width: 600px;
        width: 100%;
        padding: 1.5rem;
        max-height: 90vh;
        overflow-y: auto;
    }

    .timeline {
        border-left: 2px solid #e5e7eb;
        padding-left: 1.25rem;
        margin-top: 1rem;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 1.25rem;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -1.65rem;
        top: 0.25rem;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #0a2540;
    }
</style>
@endpush

@section('content')
<div style="padding: 0.5rem 0;">

    {{-- 1. Summary Stat Cards --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon approved">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            </div>
            <div>
                <div class="stat-value">{{ $totalTerbitBulanIni }}</div>
                <div class="stat-title">Surat Terbit Bulan Ini</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon pending">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            </div>
            <div>
                <div class="stat-value">{{ $totalPendingTTD }}</div>
                <div class="stat-title">Pending TTD Pimpinan</div>
            </div>
        </div>
    </div>

    {{-- 2. Clean Category Filter Tabs (Internal, Eksternal, Penting) --}}
    <div class="filter-tabs">
        <a href="{{ route('admin.sekretariat.surat.index', ['tipe' => $tipe, 'klasifikasi' => 'internal']) }}" class="filter-tab {{ $klasifikasi == 'internal' ? 'active' : '' }}">
            Surat Internal <span class="tab-badge">{{ $countInternal }}</span>
        </a>
        <a href="{{ route('admin.sekretariat.surat.index', ['tipe' => $tipe, 'klasifikasi' => 'eksternal']) }}" class="filter-tab {{ $klasifikasi == 'eksternal' ? 'active' : '' }}">
            Surat Eksternal <span class="tab-badge">{{ $countEksternal }}</span>
        </a>
        <a href="{{ route('admin.sekretariat.surat.index', ['tipe' => $tipe, 'klasifikasi' => 'penting']) }}" class="filter-tab {{ $klasifikasi == 'penting' ? 'active' : '' }}">
            Surat Penting 
            <span class="tab-badge">{{ $countPenting }}</span>
            @if($countPentingPending > 0)
                <span class="tab-badge-danger">{{ $countPentingPending }} Pending</span>
            @endif
        </a>
    </div>

    {{-- 3. Content Table Card --}}
    <div class="card-box">
        <div class="action-bar">
            <form action="{{ route('admin.sekretariat.surat.index') }}" method="GET" class="search-filter-group">
                <input type="hidden" name="tipe" value="{{ $tipe }}">
                <input type="hidden" name="klasifikasi" value="{{ $klasifikasi }}">
                
                <select name="status" class="form-control select2-basic" style="width: 160px;" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="Pending TTD" {{ request('status') == 'Pending TTD' ? 'selected' : '' }}>Pending TTD</option>
                    <option value="Terbit" {{ request('status') == 'Terbit' ? 'selected' : '' }}>Terbit</option>
                    <option value="Revisi" {{ request('status') == 'Revisi' ? 'selected' : '' }}>Revisi</option>
                    <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                </select>

                <input type="text" name="search" class="form-control" style="width: 240px;" placeholder="Cari no surat / perihal..." value="{{ request('search') }}">
                
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                @if(request()->anyFilled(['status', 'search']))
                    <a href="{{ route('admin.sekretariat.surat.index', ['tipe' => $tipe, 'klasifikasi' => $klasifikasi]) }}" class="btn btn-secondary btn-sm">Reset</a>
                @endif
            </form>

            <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah {{ $tipe == 'masuk' ? 'Surat Masuk' : 'Surat Keluar' }} Baru
            </button>
        </div>

        {{-- Table List --}}
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>NO. SURAT</th>
                        <th>KLASIFIKASI</th>
                        <th>PERIHAL & {{ $tipe == 'masuk' ? 'PENGIRIM' : 'TUJUAN' }}</th>
                        <th>TANGGAL</th>
                        <th>STATUS</th>
                        <th>LAMPIRAN (PDF/WORD)</th>
                        <th style="text-align: right;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($surats as $item)
                    <tr>
                        <td>
                            <strong style="color: #0a2540;">{{ $item->nomor_surat }}</strong>
                        </td>
                        <td>
                            <span style="font-size: 0.75rem; text-transform: uppercase; padding: 3px 8px; background: #e5e7eb; border-radius: 4px; font-weight: 600;">{{ ucfirst($item->klasifikasi) }}</span>
                        </td>
                        <td>
                            <div style="font-weight: 600;">{{ $item->perihal }}</div>
                            <div style="font-size: 0.8rem; color: #6b7280;">{{ $tipe == 'masuk' ? 'Pengirim' : 'Tujuan' }}: {{ $item->pengirim_tujuan }}</div>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                        <td>
                            @if($item->status == 'Pending TTD')
                                <span class="status-badge pending">Pending TTD</span>
                            @elseif($item->status == 'Terbit')
                                <span class="status-badge approved">Terbit</span>
                            @elseif($item->status == 'Revisi')
                                <span class="status-badge rejected">Revisi</span>
                            @else
                                <span class="status-badge draft">Draft</span>
                            @endif
                        </td>
                        <td>
                            @if($item->file_lampiran)
                                <a href="{{ Storage::url($item->file_lampiran) }}" target="_blank" class="btn btn-secondary btn-sm" style="background:#ecfdf5; color:#047857; border-color:#a7f3d0;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                                    Unduh File PDF/Word
                                </a>
                            @elseif($item->link_drive)
                                <a href="{{ $item->link_drive }}" target="_blank" style="color: #2563eb; text-decoration: none; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 4px;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                                    Drive Link
                                </a>
                            @else
                                <span style="color: #9ca3af; font-size: 0.8rem;">-</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                {{-- Audit Trail Button (12b) --}}
                                <button type="button" class="btn btn-secondary btn-sm" onclick="viewAuditTrail({{ $item->id }})" title="Log Audit Trail">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                </button>

                                {{-- Update Status Modal Button --}}
                                <button type="button" class="btn btn-info btn-sm" onclick="openStatusModal({{ $item->id }}, '{{ $item->status }}')" title="Ubah Status Surat">
                                    Edit Status
                                </button>

                                {{-- Delete --}}
                                <form action="{{ route('admin.sekretariat.surat.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus surat ini?')" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem; color: #6b7280;">Belum ada data {{ $tipe == 'masuk' ? 'Surat Masuk' : 'Surat Keluar' }} pada kategori ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1rem;">
            {{ $surats->links() }}
        </div>
    </div>
</div>

{{-- Create Modal --}}
<div class="modal-overlay" id="createModal">
    <div class="modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 style="color: #0a2540; margin: 0; font-weight: 700;">Tambah {{ $tipe == 'masuk' ? 'Surat Masuk' : 'Surat Keluar' }} Baru</h3>
            <button type="button" onclick="closeCreateModal()" style="background: none; border: none; font-size: 1.25rem; cursor: pointer; color: #6b7280;">&times;</button>
        </div>

        <form action="{{ route('admin.sekretariat.surat.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="tipe" value="{{ $tipe }}">

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.375rem; color: #374151;">Klasifikasi Surat</label>
                <select name="klasifikasi" class="form-control select2-basic" style="width: 100%;" required>
                    <option value="internal" {{ $klasifikasi == 'internal' ? 'selected' : '' }}>Surat Internal</option>
                    <option value="eksternal" {{ $klasifikasi == 'eksternal' ? 'selected' : '' }}>Surat Eksternal</option>
                    <option value="penting" {{ $klasifikasi == 'penting' ? 'selected' : '' }}>Surat Penting</option>
                </select>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.375rem; color: #374151;">Nomor Surat (Manual)</label>
                <input type="text" name="nomor_surat" class="form-control" style="width: 100%;" placeholder="Contoh: 001/SKR/KTN/VII/2026" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.375rem; color: #374151;">Tanggal Surat</label>
                    <input type="text" name="tanggal" class="form-control datepicker" style="width: 100%; background: white;" value="{{ date('Y-m-d') }}" placeholder="Pilih tanggal..." required>
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.375rem; color: #374151;">Status Awal</label>
                    <select name="status" class="form-control select2-basic" style="width: 100%;">
                        <option value="Pending TTD" selected>Pending TTD (Default Upload)</option>
                        <option value="Draft">Draft</option>
                        <option value="Terbit">Terbit</option>
                        <option value="Revisi">Revisi</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.375rem; color: #374151;">Perihal Surat</label>
                <input type="text" name="perihal" class="form-control" style="width: 100%;" placeholder="Ringkasan perihal surat..." required>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.375rem; color: #374151;">{{ $tipe == 'masuk' ? 'Pengirim Surat' : 'Tujuan Surat' }}</label>
                <input type="text" name="pengirim_tujuan" class="form-control" style="width: 100%;" placeholder="Nama instansi/pengurus..." required>
            </div>

            <div style="margin-bottom: 1rem; background: #f9fafb; padding: 1rem; border-radius: 8px; border: 1px dashed #d1d5db;">
                <label style="display: block; font-size: 0.875rem; font-weight: 700; margin-bottom: 0.25rem; color: #0a2540;">Upload File Lampiran (PDF / Word)</label>
                <input type="file" name="file_lampiran" class="form-control" style="width: 100%; background: white;" accept=".pdf,.doc,.docx">
                <span style="font-size: 0.75rem; color: #6b7280; margin-top: 4px; display: block;">Format yang didukung: PDF, DOC, DOCX (Maksimal 10MB).</span>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.375rem; color: #374151;">Atau Link Tautan Google Drive (Opsional)</label>
                <input type="url" name="link_drive" class="form-control" style="width: 100%;" placeholder="https://drive.google.com/file/d/...">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                <button type="button" onclick="closeCreateModal()" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary" onclick="Toast.fire({ icon: 'success', title: 'Surat sedang disimpan...' })">Simpan Surat</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Status Modal --}}
<div class="modal-overlay" id="statusModal">
    <div class="modal-content" style="max-width: 450px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 style="color: #0a2540; margin: 0; font-weight: 700;">Ubah Status Surat</h3>
            <button type="button" onclick="closeStatusModal()" style="background: none; border: none; font-size: 1.25rem; cursor: pointer; color: #6b7280;">&times;</button>
        </div>

        <form id="statusForm" method="POST">
            @csrf
            @method('PATCH')
            
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.375rem; color: #374151;">Pilih Status Baru</label>
                <select name="status" id="modalStatusSelect" class="form-control select2-basic" style="width: 100%;" required>
                    <option value="Pending TTD">Pending TTD</option>
                    <option value="Terbit">Terbit (Approve/TTD)</option>
                    <option value="Revisi">Revisi</option>
                    <option value="Draft">Draft</option>
                </select>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.375rem; color: #374151;">Catatan Revisi / Keterangan</label>
                <textarea name="notes" class="form-control" style="width: 100%; height: 80px;" placeholder="Catatan opsional untuk histori audit log..."></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                <button type="button" onclick="closeStatusModal()" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary" onclick="Toast.fire({ icon: 'success', title: 'Status surat berhasil diperbarui...' })">Update Status</button>
            </div>
        </form>
    </div>
</div>

{{-- Audit Trail Modal (12b) --}}
<div class="modal-overlay" id="auditModal">
    <div class="modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 style="color: #0a2540; margin: 0; font-weight: 700;">Log Aktivitas / Audit Trail Surat</h3>
            <button type="button" onclick="closeAuditModal()" style="background: none; border: none; font-size: 1.25rem; cursor: pointer; color: #6b7280;">&times;</button>
        </div>

        <div id="auditContent">
            <div style="text-align: center; color: #6b7280; padding: 2rem;">Memuat log riwayat...</div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        if (typeof flatpickr !== 'undefined') {
            flatpickr(".datepicker", {
                dateFormat: "Y-m-d",
                locale: "id",
                allowInput: true
            });
        }

        if (typeof $.fn.select2 !== 'undefined') {
            $('.select2-basic').select2({
                width: '100%'
            });
        }
    });

    function openCreateModal() {
        document.getElementById('createModal').classList.add('active');
        if (typeof $.fn.select2 !== 'undefined') {
            $('#createModal .select2-basic').select2({
                width: '100%',
                dropdownParent: $('#createModal')
            });
        }
    }
    function closeCreateModal() {
        document.getElementById('createModal').classList.remove('active');
    }

    function openStatusModal(id, currentStatus) {
        document.getElementById('statusForm').action = "/admin/sekretariat/surat/" + id + "/status";
        document.getElementById('modalStatusSelect').value = currentStatus;
        document.getElementById('statusModal').classList.add('active');
        if (typeof $.fn.select2 !== 'undefined') {
            $('#statusModal .select2-basic').select2({
                width: '100%',
                dropdownParent: $('#statusModal')
            });
        }
    }
    function closeStatusModal() {
        document.getElementById('statusModal').classList.remove('active');
    }

    function openAuditModal() {
        document.getElementById('auditModal').classList.add('active');
    }
    function closeAuditModal() {
        document.getElementById('auditModal').classList.remove('active');
    }

    function viewAuditTrail(id) {
        openAuditModal();
        var content = document.getElementById('auditContent');
        content.innerHTML = '<div style="text-align: center; color: #6b7280; padding: 2rem;">Memuat log riwayat...</div>';

        fetch('/admin/sekretariat/surat/' + id + '/audit-trail')
            .then(res => res.json())
            .then(data => {
                var html = '<div style="margin-bottom: 1rem;"><strong>Nomor Surat:</strong> ' + data.surat.nomor_surat + '<br><span style="color:#6b7280; font-size:0.85rem;">Perihal: ' + data.surat.perihal + '</span></div>';
                
                if (data.audit_logs.length === 0) {
                    html += '<p style="color:#9ca3af;">Belum ada catatan log aktivitas.</p>';
                } else {
                    html += '<div class="timeline">';
                    data.audit_logs.forEach(log => {
                        var dateStr = new Date(log.created_at).toLocaleString('id-ID');
                        html += '<div class="timeline-item">';
                        html += '<div style="font-weight: 600; font-size: 0.9rem;">' + log.action + '</div>';
                        html += '<div style="font-size: 0.8rem; color: #6b7280;">Oleh: <strong>' + (log.admin_name || 'Admin') + '</strong> &bull; ' + dateStr + '</div>';
                        if (log.old_status || log.new_status) {
                            html += '<div style="font-size: 0.8rem; color: #0a2540; margin-top: 4px;">Status: ' + (log.old_status ? log.old_status + ' &rarr; ' : '') + '<strong>' + log.new_status + '</strong></div>';
                        }
                        if (log.notes) {
                            html += '<div style="font-size: 0.8rem; background: #f9fafb; padding: 6px 10px; border-radius: 6px; margin-top: 4px; border: 1px solid #e5e7eb;">' + log.notes + '</div>';
                        }
                        html += '</div>';
                    });
                    html += '</div>';
                }

                content.innerHTML = html;
            });
    }
</script>
@endpush
@endsection
