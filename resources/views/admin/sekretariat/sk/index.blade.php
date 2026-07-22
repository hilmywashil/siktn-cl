@extends('admin.layouts.admin-layout')

@section('title', 'Surat Keputusan (SK) - SIKTN Admin')
@section('page-title', 'Sekretariat - Surat Keputusan (SK)')

@push('styles')
<style>
    .alert-warning-custom {
        background: #fffbeb;
        border: 1px solid #fde68a;
        color: #92400e;
        padding: 1rem 1.25rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 12px;
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

    .btn-danger {
        background: #fef2f2;
        color: #dc2626;
        border-color: #fecaca;
    }

    .btn-info {
        background: #e0f2fe;
        color: #0369a1;
        border-color: #bae6fd;
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

    .status-badge.approved { background: #d1fae5; color: #059669; }
    .status-badge.rejected { background: #fee2e2; color: #dc2626; }

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
        max-width: 550px;
        width: 100%;
        padding: 1.5rem;
        max-height: 90vh;
        overflow-y: auto;
    }
</style>
@endpush

@section('content')
<div style="padding: 0.5rem 0;">

    {{-- Alert Banner Pengingat Masa Berlaku SK --}}
    @if(count($expiringSks) > 0)
    <div class="alert-warning-custom">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        <div>
            <strong>Perhatian (Pemberitahuan Sistem H-180 / 6 Bulan):</strong>
            Terdapat <strong>{{ count($expiringSks) }} Surat Keputusan (SK)</strong> yang masa berlakunya akan berakhir kurang dari 6 bulan lagi. Silakan periksa daftar SK di bawah.
        </div>
    </div>
    @endif

    <div class="card-box">
        <div class="action-bar">
            <form action="{{ route('admin.sekretariat.sk.index') }}" method="GET" class="search-filter-group">
                <select name="status" class="form-control select2-basic" style="width: 200px;" onchange="this.form.submit()">
                    <option value="">Semua Status (Aktif/Tidak Aktif)</option>
                    <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Tidak Aktif" {{ request('status') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>

                <input type="text" name="search" class="form-control" style="width: 220px;" placeholder="Cari no SK / judul..." value="{{ request('search') }}">
                
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                @if(request()->anyFilled(['status', 'search']))
                    <a href="{{ route('admin.sekretariat.sk.index') }}" class="btn btn-secondary btn-sm">Reset</a>
                @endif
            </form>

            <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah SK Baru
            </button>
        </div>

        {{-- Table List --}}
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>NO. SK</th>
                        <th>JUDUL SURAT KEPUTUSAN</th>
                        <th>TANGGAL BERLAKU</th>
                        <th>TANGGAL BERAKHIR</th>
                        <th>STATUS</th>
                        <th>DOKUMEN (DRIVE)</th>
                        <th style="text-align: right;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sks as $item)
                    @php
                        $daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($item->tanggal_berakhir), false);
                        $isNearExpiring = $item->status == 'Aktif' && $daysLeft >= 0 && $daysLeft <= 180;
                    @endphp
                    <tr style="{{ $isNearExpiring ? 'background: #fffbeb;' : '' }}">
                        <td>
                            <strong style="color: #0a2540;">{{ $item->nomor_sk }}</strong>
                        </td>
                        <td>
                            <div style="font-weight: 600;">{{ $item->judul_sk }}</div>
                            @if($item->keterangan)
                                <div style="font-size: 0.8rem; color: #6b7280;">{{ $item->keterangan }}</div>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_berlaku)->format('d M Y') }}</td>
                        <td>
                            <div>{{ \Carbon\Carbon::parse($item->tanggal_berakhir)->format('d M Y') }}</div>
                            @if($isNearExpiring)
                                <span style="font-size: 0.75rem; color: #d97706; font-weight: 700;">({{ $daysLeft }} hari lagi)</span>
                            @endif
                        </td>
                        <td>
                            @if($item->status == 'Aktif')
                                <span class="status-badge approved">Aktif</span>
                            @else
                                <span class="status-badge rejected">Tidak Aktif</span>
                            @endif
                        </td>
                        <td>
                            @if($item->link_drive)
                                <a href="{{ $item->link_drive }}" target="_blank" style="color: #2563eb; text-decoration: none; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 4px;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                                    Buka Drive
                                </a>
                            @else
                                <span style="color: #9ca3af; font-size: 0.8rem;">-</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                {{-- Edit Button --}}
                                <button type="button" class="btn btn-info btn-sm" onclick="openEditModal({{ json_encode($item) }})">
                                    Edit
                                </button>

                                {{-- Delete Button --}}
                                <form action="{{ route('admin.sekretariat.sk.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus SK ini?')" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem; color: #6b7280;">Belum ada data Surat Keputusan (SK).</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1rem;">
            {{ $sks->links() }}
        </div>
    </div>
</div>

{{-- Create Modal --}}
<div class="modal-overlay" id="createModal">
    <div class="modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 style="color: #0a2540; margin: 0; font-weight: 700;">Tambah Surat Keputusan (SK) Baru</h3>
            <button type="button" onclick="closeCreateModal()" style="background: none; border: none; font-size: 1.25rem; cursor: pointer; color: #6b7280;">&times;</button>
        </div>

        <form action="{{ route('admin.sekretariat.sk.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.375rem; color: #374151;">Nomor SK</label>
                <input type="text" name="nomor_sk" class="form-control" style="width: 100%;" placeholder="Contoh: SK/005/PNKT/2026" required>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.375rem; color: #374151;">Judul Surat Keputusan</label>
                <input type="text" name="judul_sk" class="form-control" style="width: 100%;" placeholder="Judul penetapan SK..." required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.375rem; color: #374151;">Tanggal Berlaku</label>
                    <input type="text" name="tanggal_berlaku" class="form-control datepicker" style="width: 100%; background: white;" value="{{ date('Y-m-d') }}" placeholder="Pilih tanggal..." required>
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.375rem; color: #374151;">Tanggal Berakhir</label>
                    <input type="text" name="tanggal_berakhir" class="form-control datepicker" style="width: 100%; background: white;" placeholder="Pilih tanggal berakhir..." required>
                </div>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.375rem; color: #374151;">Status SK</label>
                <select name="status" class="form-control select2-basic" style="width: 100%;" required>
                    <option value="Aktif" selected>Aktif</option>
                    <option value="Tidak Aktif">Tidak Aktif</option>
                </select>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.375rem; color: #374151;">Link Tautan Dokumen (Google Drive)</label>
                <input type="url" name="link_drive" class="form-control" style="width: 100%;" placeholder="https://drive.google.com/file/d/...">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.375rem; color: #374151;">Keterangan Tambahan</label>
                <textarea name="keterangan" class="form-control" style="width: 100%; height: 70px;" placeholder="Catatan tambahan..."></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                <button type="button" onclick="closeCreateModal()" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary" onclick="Toast.fire({ icon: 'success', title: 'Surat Keputusan (SK) sedang disimpan...' })">Simpan SK</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal-overlay" id="editModal">
    <div class="modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 style="color: #0a2540; margin: 0; font-weight: 700;">Edit Surat Keputusan (SK)</h3>
            <button type="button" onclick="closeEditModal()" style="background: none; border: none; font-size: 1.25rem; cursor: pointer; color: #6b7280;">&times;</button>
        </div>

        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.375rem; color: #374151;">Nomor SK</label>
                <input type="text" name="nomor_sk" id="editNomorSk" class="form-control" style="width: 100%;" required>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.375rem; color: #374151;">Judul Surat Keputusan</label>
                <input type="text" name="judul_sk" id="editJudulSk" class="form-control" style="width: 100%;" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.375rem; color: #374151;">Tanggal Berlaku</label>
                    <input type="text" name="tanggal_berlaku" id="editTanggalBerlaku" class="form-control datepicker" style="width: 100%; background: white;" required>
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.375rem; color: #374151;">Tanggal Berakhir</label>
                    <input type="text" name="tanggal_berakhir" id="editTanggalBerakhir" class="form-control datepicker" style="width: 100%; background: white;" required>
                </div>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.375rem; color: #374151;">Status SK</label>
                <select name="status" id="editStatus" class="form-control select2-basic" style="width: 100%;" required>
                    <option value="Aktif">Aktif</option>
                    <option value="Tidak Aktif">Tidak Aktif</option>
                </select>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.375rem; color: #374151;">Link Tautan Dokumen (Google Drive)</label>
                <input type="url" name="link_drive" id="editLinkDrive" class="form-control" style="width: 100%;">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.375rem; color: #374151;">Keterangan Tambahan</label>
                <textarea name="keterangan" id="editKeterangan" class="form-control" style="width: 100%; height: 70px;"></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                <button type="button" onclick="closeEditModal()" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary" onclick="Toast.fire({ icon: 'success', title: 'Surat Keputusan (SK) berhasil diperbarui...' })">Update SK</button>
            </div>
        </form>
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

    function openEditModal(item) {
        document.getElementById('editForm').action = "/admin/sekretariat/sk/" + item.id;
        document.getElementById('editNomorSk').value = item.nomor_sk;
        document.getElementById('editJudulSk').value = item.judul_sk;
        document.getElementById('editTanggalBerlaku').value = item.tanggal_berlaku;
        document.getElementById('editTanggalBerakhir').value = item.tanggal_berakhir;
        document.getElementById('editStatus').value = item.status;
        document.getElementById('editLinkDrive').value = item.link_drive || '';
        document.getElementById('editKeterangan').value = item.keterangan || '';
        document.getElementById('editModal').classList.add('active');

        if (typeof $.fn.select2 !== 'undefined') {
            $('#editModal .select2-basic').select2({
                width: '100%',
                dropdownParent: $('#editModal')
            });
        }
    }
    function closeEditModal() {
        document.getElementById('editModal').classList.remove('active');
    }
</script>
@endpush
@endsection
