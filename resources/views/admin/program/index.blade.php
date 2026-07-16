@extends('admin.layouts.admin-layout')

@section('title', 'Kelola Program (CSR & Bidang)')

@section('content')
<div class="header">
    <div class="header-text">
        <h1>Kelola Program</h1>
        <p>Kelola data program CSR dan program internal Bidang Karang Taruna.</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.program.create') }}" class="btn-tambah">
            <i class="fa fa-plus"></i> Tambah Program Baru
        </a>
    </div>
</div>

<div class="card" style="margin-top: 20px;">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="programTable">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Program</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Periode</th>
                        <th>PIC</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($programs as $index => $program)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $program->nama_program }}</td>
                        <td>
                            @if($program->kategori == 'CSR')
                                <span style="background-color: #28a745; color: white; padding: 3px 8px; border-radius: 4px; font-size: 12px;">CSR</span>
                            @else
                                <span style="background-color: #17a2b8; color: white; padding: 3px 8px; border-radius: 4px; font-size: 12px;">Bidang</span>
                            @endif
                        </td>
                        <td>
                            @if($program->status == 'Perencanaan')
                                <span style="background-color: #ffc107; color: black; padding: 3px 8px; border-radius: 4px; font-size: 12px;">Perencanaan</span>
                            @elseif($program->status == 'Berjalan')
                                <span style="background-color: #007bff; color: white; padding: 3px 8px; border-radius: 4px; font-size: 12px;">Berjalan</span>
                            @else
                                <span style="background-color: #6c757d; color: white; padding: 3px 8px; border-radius: 4px; font-size: 12px;">Selesai</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($program->periode_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($program->periode_selesai)->format('d M Y') }}</td>
                        <td>{{ $program->pic }}</td>
                        <td>
                            <div class="btn-group" style="display: flex; gap: 5px;">
                                <a href="{{ route('admin.program.edit', $program->id) }}" class="btn-action btn-edit" title="Edit">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.program.destroy', $program->id) }}" method="POST" class="form-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn-action btn-delete btn-delete-confirm" title="Hapus">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.btn-delete-confirm');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                
                Swal.fire({
                    title: 'Hapus Program?',
                    text: "Data program ini akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
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
