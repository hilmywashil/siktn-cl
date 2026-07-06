@extends('admin.layouts.admin-layout')

@section('title', 'Data Terhapus')
@section('page-title', 'Data Terhapus')

@php
    $activeMenu = 'trash';
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
            color: #0a2540;
        }

        .page-header p {
            color: #6b7280;
            font-size: 0.875rem;
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

        /* Bulk Action Buttons */
        .btn-bulk-restore {
            font-family: 'Montserrat', sans-serif;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #10b981;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            color: white;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn-bulk-restore:hover {
            background: #059669;
        }
        
        .btn-bulk-delete {
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
        .btn-bulk-delete:hover {
            background: #dc2626;
        }
        
        /* Action icons */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
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
        }
        
        .btn-icon.restore {
            color: #10b981;
            border-color: #10b981;
        }
        .btn-icon.restore:hover {
            background: #d1fae5;
        }
        
        .btn-icon.delete {
            color: #ef4444;
            border-color: #ef4444;
        }
        .btn-icon.delete:hover {
            background: #fee2e2;
        }

        .btn-icon svg {
            width: 16px;
            height: 16px;
            stroke-width: 2;
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <h1>Data Terhapus</h1>
        <p>Kelola data anggota yang telah dihapus sementara (Soft Delete). Fitur ini eksklusif untuk Super Admin.</p>
    </div>

    <div class="table-container">
        @if($anggotas->count() > 0)
            <div style="padding: 1rem 1.5rem; border-bottom: 1px solid #e5e7eb; display: none; gap: 1rem;" id="bulk-action-container">
                <button type="button" onclick="bulkRestore()" class="btn-bulk-restore">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                        <path d="M3 3v5h5"/>
                    </svg>
                    Pulihkan (<span id="selected-count-restore">0</span>)
                </button>
                <button type="button" onclick="bulkForceDelete()" class="btn-bulk-delete">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                    Hapus Permanen (<span id="selected-count-delete">0</span>)
                </button>
            </div>
            
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 40px; text-align: center;">
                                <input type="checkbox" id="select-all" style="cursor: pointer;">
                            </th>
                            <th>No</th>
                            <th>Nama Lengkap</th>
                            <th>Username</th>
                            <th>Domisili</th>
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
                                <td><strong>{{ $item->nama_lengkap ?? '-' }}</strong></td>
                                <td>{{ $item->username }}</td>
                                <td>{{ $item->domisili ?? '-' }}</td>
                                <td>{{ $item->deleted_at->format('d M Y, H:i') }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <button onclick="confirmRestore({{ $item->id }})" class="btn-icon restore" title="Pulihkan">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                                                <path d="M3 3v5h5"/>
                                            </svg>
                                        </button>
                                        <button onclick="confirmForceDelete({{ $item->id }})" class="btn-icon delete" title="Hapus Permanen">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            </svg>
                                        </button>
                                        
                                        <form id="restore-form-{{ $item->id }}" action="{{ route('admin.anggota.restore', $item->id) }}" method="POST" style="display:none;">
                                            @csrf
                                            @method('PUT')
                                        </form>
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
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 4H8l-7 8 7 8h13a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"/>
                    <line x1="18" y1="9" x2="12" y2="15"/>
                    <line x1="12" y1="9" x2="18" y2="15"/>
                </svg>
                <h3>Tong Sampah Kosong</h3>
                <p>Tidak ada data anggota yang terhapus saat ini.</p>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    const selectAllBtn = document.getElementById('select-all');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    const bulkActionContainer = document.getElementById('bulk-action-container');
    
    function updateBulkUI() {
        if(!bulkActionContainer) return;
        const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
        
        document.getElementById('selected-count-restore').textContent = checkedCount;
        document.getElementById('selected-count-delete').textContent = checkedCount;
        
        if (checkedCount > 0) {
            bulkActionContainer.style.display = 'flex';
        } else {
            bulkActionContainer.style.display = 'none';
        }
    }

    if (selectAllBtn) {
        selectAllBtn.addEventListener('change', function() {
            rowCheckboxes.forEach(cb => cb.checked = this.checked);
            updateBulkUI();
        });
        rowCheckboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                selectAllBtn.checked = document.querySelectorAll('.row-checkbox:checked').length === rowCheckboxes.length;
                updateBulkUI();
            });
        });
    }

    function confirmRestore(id) {
        Swal.fire({
            title: 'Pulihkan Data?',
            text: "Akun anggota ini akan aktif kembali.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#f3f4f6',
            confirmButtonText: 'Ya, Pulihkan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('restore-form-' + id).submit();
            }
        });
    }

    function confirmForceDelete(id) {
        Swal.fire({
            title: 'Hapus Permanen?',
            text: "Data yang dihapus permanen TIDAK DAPAT dikembalikan!",
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#f3f4f6',
            confirmButtonText: 'Hapus Permanen',
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
        
        const ids = Array.from(checked).map(cb => cb.value);
        Swal.fire({
            title: 'Pulihkan Massal?',
            text: `Yakin ingin memulihkan ${ids.length} data ini?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#f3f4f6',
            confirmButtonText: 'Ya, Pulihkan Semua'
        }).then((result) => {
            if (result.isConfirmed) {
                executeBulkAction("{{ route('admin.trash.anggota.bulk-restore') }}", ids);
            }
        });
    }

    function bulkForceDelete() {
        const checked = document.querySelectorAll('.row-checkbox:checked');
        if (checked.length === 0) return;
        
        const ids = Array.from(checked).map(cb => cb.value);
        Swal.fire({
            title: 'Hapus Permanen Massal?',
            text: `Perhatian: ${ids.length} data ini akan musnah dan tidak bisa dikembalikan.`,
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#f3f4f6',
            confirmButtonText: 'Ya, Hapus Permanen'
        }).then((result) => {
            if (result.isConfirmed) {
                executeBulkAction("{{ route('admin.trash.anggota.bulk-force-delete') }}", ids, 'DELETE');
            }
        });
    }

    function executeBulkAction(url, ids, method = 'POST') {
        fetch(url, {
            method: method,
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            },
            body: JSON.stringify({ ids: ids })
        })
        .then(async res => {
            const data = await res.json();
            if (!res.ok) {
                throw new Error(data.message || 'Terjadi kesalahan sistem.');
            }
            return data;
        })
        .then(data => {
            if (data.status === 'success') {
                Swal.fire('Berhasil!', data.message, 'success').then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire('Error!', data.message || 'Terjadi kesalahan.', 'error');
            }
        })
        .catch(err => {
            Swal.fire('Error!', err.message || 'Sistem sedang sibuk.', 'error');
        });
    }
</script>
@endpush
