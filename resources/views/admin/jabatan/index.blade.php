@extends('admin.layouts.admin-layout')

@section('title', 'Master Jabatan')
@section('page-title', 'Master Jabatan')

@push('styles')
<style>
    .content-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f3f4f6;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0a2540;
    }

    .btn-primary {
        background: #0a2540;
        color: white;
        padding: 0.625rem 1.5rem;
        border-radius: 8px;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-primary:hover {
        background: #164e63;
        transform: translateY(-1px);
    }

    .table-container {
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        background: #f9fafb;
        padding: 0.875rem;
        text-align: left;
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        border-bottom: 2px solid #e5e7eb;
    }

    .data-table td {
        padding: 1rem 0.875rem;
        border-bottom: 1px solid #e5e7eb;
        font-size: 0.875rem;
    }

    .data-table tr:hover {
        background: #f9fafb;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-edit, .btn-delete {
        padding: 0.5rem 0.875rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-edit {
        background: #dbeafe;
        color: #1e40af;
    }

    .btn-edit:hover {
        background: #bfdbfe;
    }

    .btn-delete {
        background: #fee2e2;
        color: #991b1b;
    }

    .btn-delete:hover {
        background: #fecaca;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #6b7280;
    }

    .empty-state svg {
        width: 64px;
        height: 64px;
        margin: 0 auto 1rem;
        opacity: 0.5;
    }

    .alert {
        padding: 1rem 1.25rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #6ee7b7;
    }

    /* Hierarchy Visualization Styles */
    .hierarchy-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 2rem;
        padding: 2rem 0;
    }

    .hierarchy-level {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 1.5rem;
        position: relative;
        width: 100%;
    }

    .hierarchy-level:not(:last-child)::after {
        content: '';
        position: absolute;
        bottom: -2rem;
        left: 50%;
        transform: translateX(-50%);
        width: 2px;
        height: 2rem;
        background-color: #cbd5e1;
    }

    .hierarchy-card {
        background: white;
        border: 2px solid #e2e8f0;
        border-top: 4px solid #0a2540;
        border-radius: 8px;
        padding: 1rem 1.5rem;
        text-align: center;
        min-width: 200px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        position: relative;
    }

    .hierarchy-card::before {
        content: attr(data-urutan);
        position: absolute;
        top: -12px;
        left: 50%;
        transform: translateX(-50%);
        background: #0a2540;
        color: white;
        font-size: 0.75rem;
        font-weight: bold;
        padding: 2px 8px;
        border-radius: 12px;
    }

    .hierarchy-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }
</style>
@endpush

@section('content')


    <div class="content-card" style="margin-bottom: 2rem;">
        <div class="card-header">
            <div>
                <h3 class="card-title">Visualisasi Struktur Jabatan</h3>
                <p style="color: #6b7280; font-size: 0.875rem; margin-top: 0.25rem;">Bagan hierarki organisasi berdasarkan nomor urutan posisi.</p>
            </div>
        </div>
        
        <div class="hierarchy-container">
            @if($jabatansByUrutan->count() > 0)
                @foreach($jabatansByUrutan as $urutan => $jabatansInLevel)
                    <div class="hierarchy-level">
                        @foreach($jabatansInLevel as $jabatan)
                            <div class="hierarchy-card" data-urutan="Urutan {{ $urutan }}">
                                <h4 class="hierarchy-title">{{ $jabatan->nama_jabatan }}</h4>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            @else
                <div class="empty-state" style="padding: 1rem;">
                    <p>Belum ada hierarki jabatan untuk divisualisasikan.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="content-card">
        <div class="card-header" style="flex-wrap: wrap; gap: 1rem;">
            <div style="flex: 1; min-width: 250px;">
                <h3 class="card-title">Master Jabatan</h3>
                <p style="color: #6b7280; font-size: 0.875rem; margin-top: 0.25rem;">Kelola daftar jabatan untuk form pendaftaran dan profil anggota.</p>
            </div>
            <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
                <div style="position: relative; width: 250px;">
                    <select id="searchInput" class="select2-search" style="width: 100%;">
                        <option value="">Semua Jabatan...</option>
                        @foreach($jabatans as $jab)
                            <option value="{{ strtolower($jab->nama_jabatan) }}">{{ $jab->nama_jabatan }}</option>
                        @endforeach
                    </select>
                </div>
                <a href="{{ route('admin.jabatan.create') }}" class="btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="16" height="16">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Tambah Jabatan
                </a>
            </div>
        </div>

        <div class="table-container">
            @if($jabatans->count() > 0)
                <table class="data-table">
                    <thead>
                        <tr>
                            <th width="5%">Urutan</th>
                            <th>Nama Jabatan</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jabatans as $item)
                            <tr>
                                <td style="text-align: center; font-weight: bold;">
                                    <span style="background: #f3f4f6; padding: 4px 10px; border-radius: 6px; color: #374151;">
                                        {{ $item->urutan }}
                                    </span>
                                </td>
                                <td style="font-weight: 600;">{{ $item->nama_jabatan }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.jabatan.edit', $item) }}" class="btn-edit">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="14" height="14">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.jabatan.destroy', $item) }}" method="POST" class="delete-form" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn-delete delete-btn">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="14" height="14">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline>
                        <path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path>
                    </svg>
                    <h3>Belum ada data jabatan</h3>
                    <p>Mulai tambahkan master jabatan</p>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2 for Search
        $('#searchInput').select2({
            placeholder: "Cari jabatan...",
            allowClear: true,
            theme: 'default'
        }).on('change', function() {
            const filter = $(this).val();
            const rows = document.querySelectorAll('.data-table tbody tr');
            
            rows.forEach(row => {
                const text = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                if (!filter || text.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('.delete-form');
                
                Swal.fire({
                    title: 'Hapus Jabatan?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        confirmButton: 'swal2-confirm-btn',
                        cancelButton: 'swal2-cancel-btn'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush
