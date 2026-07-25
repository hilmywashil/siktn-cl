@extends('admin.layouts.admin-layout')

@section('title', request()->get('jenis') === 'caretaker' ? 'Temu Karya Caretaker' : 'Temu Karya Organisasi')
@section('page-title', request()->get('jenis') === 'caretaker' ? 'Manajemen Temu Karya Caretaker' : 'Manajemen Temu Karya Karang Taruna')

@push('styles')
<style>
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
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }
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
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1); max-height: 90vh;
        display: flex; flex-direction: column;
    }
    .modal-overlay.show .modal-content-lg { transform: scale(1) translateY(0); }
    .modal-header-prof {
        padding: 1.2rem 1.5rem; background: linear-gradient(135deg, #022648 0%, #01162f 100%);
        color: white; display: flex; justify-content: space-between; align-items: center;
    }
    .modal-body-prof { padding: 1.5rem; overflow-y: auto; }
    .modal-footer-prof {
        padding: 1rem 1.5rem; background: #f8f9fc; border-top: 1px solid #e5e7eb;
        display: flex; justify-content: flex-end; gap: 0.75rem;
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
            <button class="btn-solid-navy" onclick="openModal()">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah {{ request()->get('jenis') === 'caretaker' ? 'Data Caretaker' : 'Temu Karya' }}
            </button>
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
                                @endif

                                @if($item->file_sk)
                                    <div style="margin-top: 4px;">
                                        <a href="{{ Storage::url($item->file_sk) }}" target="_blank" style="font-size: 0.75rem; color: #2563eb; font-weight: 600; text-decoration: underline;">
                                            Lihat File SK
                                        </a>
                                    </div>
                                @endif
                                @if($item->link_drive)
                                    <div style="margin-top: 2px;">
                                        <a href="{{ $item->link_drive }}" target="_blank" style="font-size: 0.75rem; color: #022648; font-weight: 600; text-decoration: underline;">
                                            Link Drive
                                        </a>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem;">
                                    <button class="btn-action" onclick="editData({{ json_encode($item) }})" title="Edit">
                                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </button>
                                    <button type="button" class="btn-action delete" title="Hapus" onclick="confirmDelete(this, '{{ route('admin.temu-karya.destroy', $item->id) }}')">
                                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                    </button>
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
                        <input type="text" name="wilayah" id="wilayah" class="form-control" placeholder="Contoh: Provinsi Jawa Barat / Kab. Bogor" required style="font-size: 0.85rem; font-weight: 600;">
                    </div>

                    <div class="form-group">
                        <label style="font-size: 0.8rem; font-weight: 700; color: #022648;">Tingkat Level Wilayah <span style="color: red;">*</span></label>
                        <select name="level" id="level" class="form-control" required style="font-size: 0.85rem;">
                            <option value="provinsi">Provinsi</option>
                            <option value="kab_kota">Kabupaten / Kota</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label style="font-size: 0.8rem; font-weight: 700; color: #022648;">Status Organisasi <span style="color: red;">*</span></label>
                        <select name="status" id="status" class="form-control" required style="font-size: 0.85rem;">
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

                    <div class="form-group">
                        <label style="font-size: 0.8rem; font-weight: 700; color: #022648;">Upload File SK (PDF / Word)</label>
                        <input type="file" name="file_sk" id="file_sk" class="form-control" accept=".pdf,.doc,.docx,.jpg,.png" style="font-size: 0.8rem;">
                    </div>

                    <div class="form-group">
                        <label style="font-size: 0.8rem; font-weight: 700; color: #022648;">Dokumentasi Foto</label>
                        <input type="file" name="foto_dokumentasi" id="foto_dokumentasi" class="form-control" accept="image/*" style="font-size: 0.8rem;">
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
@endsection

@push('scripts')
<script>
    function openModal() {
        document.getElementById('temuKaryaForm').reset();
        document.getElementById('temuKaryaForm').action = "{{ route('admin.temu-karya.store') }}";
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('modalTitle').innerText = 'Tambah Data {{ request()->get('jenis') === 'caretaker' ? 'Caretaker' : 'Temu Karya' }}';
        document.getElementById('temuKaryaModal').classList.add('show');
    }

    function closeModal() {
        document.getElementById('temuKaryaModal').classList.remove('show');
    }

    function editData(data) {
        document.getElementById('temuKaryaForm').action = "/admin/temu-karya/" + data.id;
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('modalTitle').innerText = 'Edit Data ' + data.wilayah;

        document.getElementById('wilayah').value = data.wilayah;
        document.getElementById('level').value = data.level;
        document.getElementById('status').value = data.status;
        document.getElementById('tanggal_pelaksanaan').value = data.tanggal_pelaksanaan ? data.tanggal_pelaksanaan.substring(0, 10) : '';
        document.getElementById('jumlah_peserta').value = data.jumlah_peserta || 0;
        document.getElementById('lokasi').value = data.lokasi || '';
        document.getElementById('link_drive').value = data.link_drive || '';
        document.getElementById('catatan').value = data.catatan || '';

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
</script>
@endpush
