{{-- resources/views/admin/anggota/trash.blade.php --}}
@extends('admin.layouts.admin-layout')

@section('title', 'Data Terhapus Anggota')
@section('page-title', 'Data Terhapus Anggota')

@php
    $activeMenu = 'trash';
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
    .btn-bulk-success {
        background: #10b981;
    }
    .btn-bulk-success:hover {
        background: #059669;
    }

    .page-header {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
        font-size: 0.875rem;
    }

    .breadcrumb a {
        color: #6b7280;
        text-decoration: none;
    }

    .breadcrumb a:hover {
        color: #0a2540;
    }

    .breadcrumb-separator {
        color: #d1d5db;
    }

    .breadcrumb-current {
        color: #0a2540;
        font-weight: 600;
    }

    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #0a2540;
        margin-bottom: 0.5rem;
    }

    .page-desc {
        color: #6b7280;
        font-size: 0.9375rem;
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
        min-width: 800px;
    }

    .table th {
        background: #f9fafb;
        padding: 1rem 1.5rem;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }

    .table td {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e5e7eb;
        color: #111827;
        font-size: 0.875rem;
    }

    .table tr:last-child td {
        border-bottom: none;
    }

    .table tr:hover {
        background: #f9fafb;
    }

    .btn-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        border: 1px solid #e5e7eb;
        background: white;
        color: #6b7280;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-icon:hover {
        background: #f3f4f6;
        color: #111827;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }

    .empty-icon {
        width: 48px;
        height: 48px;
        margin: 0 auto 1rem;
        color: #9ca3af;
    }

    .empty-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.5rem;
    }

    .empty-desc {
        color: #6b7280;
        font-size: 0.875rem;
    }
</style>
@endpush

@section('content')
    <div class="page-header">
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <span class="breadcrumb-separator">›</span>
            <span class="breadcrumb-current">Data Terhapus</span>
        </div>
        <h1 class="page-title">Data Terhapus Anggota</h1>
        <p class="page-desc">Data anggota yang dihapus akan tersimpan di sini selama 30 hari sebelum dihapus permanen secara otomatis.</p>
    </div>



    <div class="table-container">
        @if($anggotas->count() > 0)
            <div style="padding: 1rem 1.5rem; border-bottom: 1px solid #e5e7eb; display: none; gap: 0.5rem; align-items: center;" id="bulk-action-container">
                <span style="font-size: 0.875rem; font-weight: 600; color: #374151; margin-right: auto;"><span id="selected-count">0</span> item terpilih:</span>
                
                <button type="button" onclick="bulkRestore()" class="btn-bulk btn-bulk-success">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    Kembalikan
                </button>
                
                <button type="button" onclick="bulkForceDelete()" class="btn-bulk">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        <line x1="10" y1="11" x2="10" y2="17"></line>
                        <line x1="14" y1="11" x2="14" y2="17"></line>
                    </svg>
                    Hapus Permanen
                </button>
            </div>
            <div class="table-wrapper">
                <table class="table" id="trash-table">
                    <thead>
                        <tr>
                            <th style="width: 40px; text-align: center;">
                                <input type="checkbox" id="select-all" style="cursor: pointer;">
                            </th>
                            <th>No</th>
                            <th>Nama Lengkap</th>
                            <th>Username</th>
                            <th>Dihapus Pada</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($anggotas as $index => $item)
                            <tr>
                                <td style="text-align: center;">
                                    <input type="checkbox" class="row-checkbox" value="{{ $item->id }}" style="cursor: pointer;">
                                </td>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $item->nama_lengkap ?? '-' }}</strong>
                                </td>
                                <td>{{ $item->username }}</td>
                                <td>{{ $item->deleted_at->format('d M Y H:i') }}</td>
                                <td>
                                    <div class="action-buttons">
                                        {{-- Tombol Restore --}}
                                        <button type="button" onclick="confirmRestore({{ $item->id }})" class="btn-icon" style="color: #10b981; background: #ecfdf5; border-color: #a7f3d0;" title="Kembalikan (Restore)">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                            </svg>
                                        </button>
                                        <form id="restore-form-{{ $item->id }}" action="{{ route('admin.anggota.restore', $item->id) }}" method="POST" style="display:none;">
                                            @csrf
                                        </form>

                                        {{-- Tombol Hapus Permanen --}}
                                        <button type="button" onclick="confirmForceDelete({{ $item->id }})" class="btn-icon" style="color: #ef4444; background: #fef2f2; border-color: #fca5a5;" title="Hapus Permanen">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                                <line x1="14" y1="11" x2="14" y2="17"></line>
                                            </svg>
                                        </button>
                                        <form id="force-delete-form-{{ $item->id }}" action="{{ route('admin.anggota.force-delete', $item->id) }}" method="POST" style="display:none;">
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
        @else
            <div class="empty-state">
                <svg class="empty-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                </svg>
                <h3 class="empty-title">Data Terhapus Kosong</h3>
                <p class="empty-desc">Tidak ada data anggota yang terhapus saat ini.</p>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    const selectAllBtn = document.getElementById('select-all');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    const bulkActionContainer = document.getElementById('bulk-action-container');
    const selectedCountSpan = document.getElementById('selected-count');

    function updateBulkActionUI() {
        if(!bulkActionContainer) return;
        const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
        selectedCountSpan.textContent = checkedCount;
        if (checkedCount > 0) {
            bulkActionContainer.style.display = 'flex';
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

    function confirmRestore(id) {
        Swal.fire({
            title: 'Kembalikan Data?',
            text: "Data anggota akan dipulihkan dan bisa diakses kembali.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Kembalikan',
            cancelButtonText: 'Batal',
            customClass: {
                confirmButton: 'btn-restore swal2-confirm'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('restore-form-' + id).submit();
            }
        });
    }

    function confirmForceDelete(id) {
        Swal.fire({
            title: 'Hapus Permanen?',
            text: "PERINGATAN: Data anggota dan semua file terkait akan dihapus permanen dan tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#f3f4f6',
            confirmButtonText: 'Ya, Hapus Permanen',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('force-delete-form-' + id).submit();
            }
        });
    }

    function bulkRestore() {
        const checked = document.querySelectorAll('.row-checkbox:checked');
        if (checked.length === 0) return;

        Swal.fire({
            title: 'Kembalikan Massal?',
            text: `Yakin ingin memulihkan ${checked.length} data anggota ini?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Kembalikan Semua',
            cancelButtonText: 'Batal',
            customClass: {
                confirmButton: 'btn-restore swal2-confirm'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const ids = Array.from(checked).map(cb => cb.value);
                executeBulkAction("{{ route('admin.trash.anggota.bulk-restore') }}", ids, 'POST', 'Berhasil Dikembalikan!');
            }
        });
    }

    function bulkForceDelete() {
        const checked = document.querySelectorAll('.row-checkbox:checked');
        if (checked.length === 0) return;

        Swal.fire({
            title: 'Hapus Permanen Massal?',
            text: `PERINGATAN: ${checked.length} data anggota dan semua file yang terkait akan dihapus secara PERMANEN. Lanjutkan?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#f3f4f6',
            confirmButtonText: 'Ya, Hapus Permanen Semua',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const ids = Array.from(checked).map(cb => cb.value);
                executeBulkAction("{{ route('admin.trash.anggota.bulk-force-delete') }}", ids, 'DELETE', 'Berhasil Dihapus Permanen!');
            }
        });
    }

    function executeBulkAction(url, ids, method = 'POST', successTitle) {
        fetch(url, {
            method: method,
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ ids: ids })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                Toast.fire({ icon: 'success', title: data.message });
                setTimeout(() => window.location.reload(), 1500);
            } else if (data.status === 'deleted') {
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
</script>
@endpush