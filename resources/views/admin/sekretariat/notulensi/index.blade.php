@extends('admin.layouts.admin-layout')

@section('title', 'Notulensi Rapat - SIKTN Admin')
@section('page-title', 'Sekretariat - Notulensi Rapat')

@push('styles')
<style>
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
    <div class="card-box">
        <div class="action-bar">
            <form action="{{ route('admin.sekretariat.notulensi.index') }}" method="GET" class="search-filter-group">
                <input type="text" name="search" class="form-control" style="width: 240px;" placeholder="Cari judul rapat / pemimpin..." value="{{ request('search') }}">
                
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                @if(request()->filled('search'))
                    <a href="{{ route('admin.sekretariat.notulensi.index') }}" class="btn btn-secondary btn-sm">Reset</a>
                @endif
            </form>

            <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah Notulensi Baru
            </button>
        </div>

        {{-- Table List --}}
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>JUDUL RAPAT</th>
                        <th>TAUTAN AGENDA</th>
                        <th>TANGGAL & WAKTU</th>
                        <th>PEMIMPIN RAPAT</th>
                        <th>DOKUMEN (DRIVE)</th>
                        <th style="text-align: right;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notulensis as $item)
                    <tr>
                        <td>
                            <strong style="color: #0a2540;">{{ $item->judul_rapat }}</strong>
                            @if($item->ringkasan_hasil)
                                <div style="font-size: 0.8rem; color: #6b7280; margin-top: 2px;">{{ Str::limit($item->ringkasan_hasil, 80) }}</div>
                            @endif
                        </td>
                        <td>
                            @if($item->agenda)
                                <span style="font-size: 0.8rem; padding: 3px 8px; background: #e0f2fe; color: #0369a1; border-radius: 6px; font-weight: 600;">
                                    📅 {{ $item->agenda->judul }}
                                </span>
                            @else
                                <span style="color: #9ca3af; font-size: 0.8rem;">-</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_rapat)->format('d M Y, H:i') }} WIB</td>
                        <td>{{ $item->pemimpin_rapat ?? '-' }}</td>
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
                                <form action="{{ route('admin.sekretariat.notulensi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus notulensi ini?')" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 2rem; color: #6b7280;">Belum ada data Notulensi Rapat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1rem;">
            {{ $notulensis->links() }}
        </div>
    </div>
</div>

{{-- Create Modal --}}
<div class="modal-overlay" id="createModal">
    <div class="modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 style="color: #0a2540; margin: 0; font-weight: 700;">Tambah Notulensi Rapat Baru</h3>
            <button type="button" onclick="closeCreateModal()" style="background: none; border: none; font-size: 1.25rem; cursor: pointer; color: #6b7280;">&times;</button>
        </div>

        <form action="{{ route('admin.sekretariat.notulensi.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.375rem; color: #374151;">Tautkan ke Agenda / Rapat (Opsional)</label>
                <select name="agenda_id" class="form-control select2-basic" style="width: 100%;">
                    <option value="">-- Pilih Agenda Rapat Terkait --</option>
                    @foreach($agendas as $ag)
                        <option value="{{ $ag->id }}">{{ $ag->judul }} ({{ \Carbon\Carbon::parse($ag->waktu_mulai)->format('d M Y') }})</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.375rem; color: #374151;">Judul Rapat</label>
                <input type="text" name="judul_rapat" class="form-control" style="width: 100%;" placeholder="Contoh: Rapat Kerja Sekretariat Nasional..." required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.375rem; color: #374151;">Tanggal & Waktu Rapat</label>
                    <input type="text" name="tanggal_rapat" class="form-control datetimepicker" style="width: 100%; background: white;" value="{{ date('Y-m-d H:i') }}" placeholder="Pilih tanggal & waktu..." required>
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.375rem; color: #374151;">Pemimpin Rapat</label>
                    <input type="text" name="pemimpin_rapat" class="form-control" style="width: 100%;" placeholder="Nama pemimpin rapat...">
                </div>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.375rem; color: #374151;">Ringkasan Hasil Notulensi</label>
                <textarea name="ringkasan_hasil" class="form-control" style="width: 100%; height: 90px;" placeholder="Poin-poin penting hasil rapat..."></textarea>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.375rem; color: #374151;">Link Tautan Dokumen (Google Drive)</label>
                <input type="url" name="link_drive" class="form-control" style="width: 100%;" placeholder="https://drive.google.com/file/d/...">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                <button type="button" onclick="closeCreateModal()" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary" onclick="Toast.fire({ icon: 'success', title: 'Notulensi Rapat sedang disimpan...' })">Simpan Notulensi</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal-overlay" id="editModal">
    <div class="modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 style="color: #0a2540; margin: 0; font-weight: 700;">Edit Notulensi Rapat</h3>
            <button type="button" onclick="closeEditModal()" style="background: none; border: none; font-size: 1.25rem; cursor: pointer; color: #6b7280;">&times;</button>
        </div>

        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.375rem; color: #374151;">Tautkan ke Agenda / Rapat (Opsional)</label>
                <select name="agenda_id" id="editAgendaId" class="form-control select2-basic" style="width: 100%;">
                    <option value="">-- Pilih Agenda Rapat Terkait --</option>
                    @foreach($agendas as $ag)
                        <option value="{{ $ag->id }}">{{ $ag->judul }} ({{ \Carbon\Carbon::parse($ag->waktu_mulai)->format('d M Y') }})</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.375rem; color: #374151;">Judul Rapat</label>
                <input type="text" name="judul_rapat" id="editJudulRapat" class="form-control" style="width: 100%;" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.375rem; color: #374151;">Tanggal & Waktu Rapat</label>
                    <input type="text" name="tanggal_rapat" id="editTanggalRapat" class="form-control datetimepicker" style="width: 100%; background: white;" required>
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.375rem; color: #374151;">Pemimpin Rapat</label>
                    <input type="text" name="pemimpin_rapat" id="editPemimpinRapat" class="form-control" style="width: 100%;">
                </div>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.375rem; color: #374151;">Ringkasan Hasil Notulensi</label>
                <textarea name="ringkasan_hasil" id="editRingkasanHasil" class="form-control" style="width: 100%; height: 90px;"></textarea>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.375rem; color: #374151;">Link Tautan Dokumen (Google Drive)</label>
                <input type="url" name="link_drive" id="editLinkDrive" class="form-control" style="width: 100%;">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                <button type="button" onclick="closeEditModal()" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary" onclick="Toast.fire({ icon: 'success', title: 'Notulensi Rapat berhasil diperbarui...' })">Update Notulensi</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        if (typeof flatpickr !== 'undefined') {
            flatpickr(".datetimepicker", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
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
        document.getElementById('editForm').action = "/admin/sekretariat/notulensi/" + item.id;
        document.getElementById('editAgendaId').value = item.agenda_id || '';
        document.getElementById('editJudulRapat').value = item.judul_rapat;
        document.getElementById('editTanggalRapat').value = item.tanggal_rapat ? item.tanggal_rapat.substring(0, 16) : '';
        document.getElementById('editPemimpinRapat').value = item.pemimpin_rapat || '';
        document.getElementById('editRingkasanHasil').value = item.ringkasan_hasil || '';
        document.getElementById('editLinkDrive').value = item.link_drive || '';
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
