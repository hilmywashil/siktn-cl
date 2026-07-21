@extends('admin.layouts.admin-layout')

@section('title', 'Kelola Program (CSR & Bidang)')
@section('page-title', 'Kelola Program')

@push('styles')
<style>
    .page-header {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #0a2540;
        margin-bottom: 0.5rem;
    }

    .page-desc {
        color: #6b7280;
        font-size: 0.9375rem;
    }

    .btn-primary {
        background: #0a2540;
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.2s;
        font-family: 'Montserrat', sans-serif;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-primary:hover {
        background: #ffd700;
        color: #0a2540;
    }

    .btn-primary svg {
        width: 18px;
        height: 18px;
        stroke: currentColor;
        fill: none;
        stroke-width: 2;
    }

    .admin-table-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .table-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .table-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #0a2540;
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
    }

    .admin-table thead {
        background: #f9fafb;
    }

    .admin-table th {
        padding: 1rem 2rem;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 700;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .admin-table td {
        padding: 1.25rem 2rem;
        border-top: 1px solid #e5e7eb;
        font-size: 0.875rem;
        color: #374151;
    }

    .admin-table tbody tr {
        transition: background 0.2s;
    }

    .admin-table tbody tr:hover {
        background: #f9fafb;
    }

    .badge {
        padding: 0.375rem 0.875rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .badge-csr {
        background: #e0f2fe;
        color: #0369a1;
        border: 1px solid #bae6fd;
    }

    .badge-bidang {
        background: #fef3c7;
        color: #b45309;
        border: 1px solid #fde68a;
    }
    
    .badge-status {
        padding: 4px 10px;
        border-radius: 4px;
        font-weight: 600;
        font-size: 12px;
    }

    .status-perencanaan { background: #fef08a; color: #854d0e; }
    .status-berjalan { background: #bfdbfe; color: #1e40af; }
    .status-selesai { background: #bbf7d0; color: #166534; }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-edit {
        padding: 0.5rem 0.875rem;
        background: #f3f4f6;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.8125rem;
        font-weight: 600;
        color: #374151;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-block;
    }

    .btn-edit:hover { background: #e5e7eb; }

    .btn-delete {
        padding: 0.5rem 0.875rem;
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.8125rem;
        font-weight: 600;
        color: #dc2626;
        transition: all 0.2s;
    }

    .btn-delete:hover { background: #fee2e2; }

    .btn-status {
        padding: 0.5rem 0.875rem;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.8125rem;
        font-weight: 600;
        color: #166534;
        transition: all 0.2s;
    }
    .btn-status:hover { background: #dcfce7; }
    
    .btn-status-warning {
        background: #fffbeb;
        border: 1px solid #fde68a;
        color: #92400e;
    }
    .btn-status-warning:hover { background: #fef3c7; }

    .program-title {
        font-weight: 700;
        color: #0a2540;
        font-size: 0.95rem;
        margin-bottom: 4px;
    }
    
    .program-mitra {
        font-size: 0.8rem;
        color: #6b7280;
    }

</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Manajemen Program</h1>
        <p class="page-desc">Kelola data program kerja CSR dan program internal Bidang Karang Taruna.</p>
    </div>
    @if(auth()->guard('admin')->user()->isSuperAdmin() || auth()->guard('admin')->user()->isPNKT())
    <a href="{{ route('admin.program.create') }}" class="btn-primary">
        <svg viewBox="0 0 24 24">
            <line x1="12" y1="5" x2="12" y2="19"/>
            <line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Tambah Program Baru
    </a>
    @endif
</div>

<div class="admin-table-container">
    <div class="table-header">
        <h3 class="table-title">Daftar Program ({{ $programs->count() }})</h3>
    </div>

    @if(auth()->guard('admin')->user()->isSuperAdmin() || auth()->guard('admin')->user()->isPNKT())
        @if($programs->count() > 0)
            <div style="padding: 1rem 1.5rem; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: flex-start; background-color: #f8fafc;">
                <form id="bulk-delete-form" action="{{ route('admin.program.bulk-delete') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" id="btn-bulk-delete" class="btn-delete" style="display: none; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="16" height="16">
                            <path d="M3 6h18m-2 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                        Hapus Terpilih (<span id="selected-count">0</span>)
                    </button>
                </form>
            </div>
        @endif
    @endif

    <table class="admin-table">
        <thead>
            <tr>
                @if(auth()->guard('admin')->user()->isSuperAdmin() || auth()->guard('admin')->user()->isPNKT())
                <th width="5%" style="text-align: center;"><input type="checkbox" id="check-all" class="form-checkbox"></th>
                @endif
                <th>Nama Program</th>
                <th>Kategori</th>
                <th>Status</th>
                <th>Periode</th>
                <th>PIC</th>
                @if(auth()->guard('admin')->user()->isSuperAdmin() || auth()->guard('admin')->user()->isPNKT())
                <th>Aksi</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($programs as $program)
            <tr>
                @if(auth()->guard('admin')->user()->isSuperAdmin() || auth()->guard('admin')->user()->isPNKT())
                <td style="text-align: center;">
                    <input type="checkbox" name="ids[]" value="{{ $program->id }}" class="check-item form-checkbox" form="bulk-delete-form">
                </td>
                @endif
                <td>
                    <a href="{{ route('admin.program.show', $program->id) }}" class="program-title" style="text-decoration: none; color: #0a2540;">
                        {{ $program->nama_program }}
                    </a>
                    @if($program->kategori == 'CSR' && $program->mitra)
                        <div class="program-mitra">Mitra: {{ $program->mitra }}</div>
                    @elseif($program->kategori == 'Bidang' && $program->jabatan)
                        <div class="program-mitra">Bidang: {{ $program->jabatan->nama_jabatan }}</div>
                    @endif
                </td>
                <td>
                    <span class="badge {{ $program->kategori == 'CSR' ? 'badge-csr' : 'badge-bidang' }}">
                        {{ $program->kategori }}
                    </span>
                </td>
                <td>
                    <span class="badge-status status-{{ strtolower($program->status) }}">
                        {{ $program->status }}
                    </span>
                </td>
                <td>
                    {{ \Carbon\Carbon::parse($program->periode_mulai)->format('d M Y') }} - <br>
                    {{ \Carbon\Carbon::parse($program->periode_selesai)->format('d M Y') }}
                </td>
                <td><strong>{{ $program->pic }}</strong></td>
                @if(auth()->guard('admin')->user()->isSuperAdmin() || auth()->guard('admin')->user()->isPNKT())
                <td>
                    <div class="action-buttons">
                        @if($program->kategori == 'Bidang')
                            @if($program->status == 'Perencanaan')
                                <form action="{{ route('admin.program.update-status', $program->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="Berjalan">
                                    <button type="submit" class="btn-status btn-status-warning" title="Mulai Program">Mulai</button>
                                </form>
                            @elseif($program->status == 'Berjalan')
                                <form action="{{ route('admin.program.update-status', $program->id) }}" method="POST" style="display:inline;" class="form-selesai">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="Selesai">
                                    <button type="submit" class="btn-status" title="Selesaikan Program">Selesaikan</button>
                                </form>
                            @endif
                        @endif
                        <a href="{{ route('admin.program.edit', $program->id) }}" class="btn-edit">Edit</a>
                        <form action="{{ route('admin.program.destroy', $program->id) }}" method="POST" style="display:inline;" class="form-delete">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete">Hapus</button>
                        </form>
                    </div>
                </td>
                @endif
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 3rem;">
                    <p style="color: #6b7280; font-size: 0.95rem;">Belum ada data program yang ditambahkan.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Hapus Program Pop-up
        const deleteForms = document.querySelectorAll('.form-delete');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Hapus Program?',
                    text: "Data program yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Selesaikan Program Pop-up
        const selesaiForms = document.querySelectorAll('.form-selesai');
        selesaiForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Selesaikan Program?',
                    text: "Apakah Anda yakin program ini sudah benar-benar selesai?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Selesaikan',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Bulk Delete Logic
        const checkAll = document.getElementById('check-all');
        const checkItems = document.querySelectorAll('.check-item');
        const btnBulkDelete = document.getElementById('btn-bulk-delete');
        const selectedCountSpan = document.getElementById('selected-count');
        const bulkDeleteForm = document.getElementById('bulk-delete-form');

        function updateBulkDeleteButton() {
            const checkedCount = document.querySelectorAll('.check-item:checked').length;
            selectedCountSpan.textContent = checkedCount;
            if (checkedCount > 0) {
                btnBulkDelete.style.display = 'inline-flex';
            } else {
                btnBulkDelete.style.display = 'none';
                if(checkAll) checkAll.checked = false;
            }
        }

        if (checkAll) {
            checkAll.addEventListener('change', function() {
                checkItems.forEach(item => {
                    item.checked = this.checked;
                });
                updateBulkDeleteButton();
            });
        }

        checkItems.forEach(item => {
            item.addEventListener('change', updateBulkDeleteButton);
        });

        if (btnBulkDelete) {
            btnBulkDelete.addEventListener('click', function() {
                const checkedCount = document.querySelectorAll('.check-item:checked').length;
                Swal.fire({
                    title: 'Hapus ' + checkedCount + ' Program Terpilih?',
                    text: "Data program yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus Semua!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        bulkDeleteForm.submit();
                    }
                });
            });
        }
    });
</script>
@endpush
