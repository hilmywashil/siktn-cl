@extends('admin.layouts.admin-layout')

@section('title', 'Verifikasi Jabatan Pengurus')
@section('page-title', 'VERIFIKASI JABATAN PENGURUS')

@push('styles')
<style>
    .admin-ui-scope {
        --navy: #022648; --navy-dark: #01162f; --navy-light: #0a3a6b;
        --gold: #b7830f; --gold-light: #f59e0b;
        --gray-50: #f8fafc; --gray-100: #f1f5f9; --gray-200: #e2e8f0; --gray-300: #cbd5e1;
        --gray-500: #64748b; --gray-700: #334155; --gray-900: #0f172a;
        --radius-md: 6px; --radius-lg: 10px;
    }
    
    .table-container { background: white; border-radius: var(--radius-lg); border: 1px solid var(--gray-200); box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden; }
    .table-wrapper { overflow-x: auto; }
    .table { width: 100%; border-collapse: collapse; min-width: 900px; }
    .table thead { background: var(--gray-50); border-bottom: 1px solid var(--gray-200); }
    .table th { padding: 0.875rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: var(--gray-700); text-transform: uppercase; letter-spacing: 0.05em; }
    .table td { padding: 1rem; border-bottom: 1px solid var(--gray-100); font-size: 0.875rem; color: var(--gray-900); vertical-align: middle; }
    .table tbody tr:hover { background: var(--gray-50); }

    .btn-acc {
        background: #166534; color: white; border: none; padding: 5px 12px; border-radius: 6px; font-weight: 700; font-size: 0.775rem; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s;
    }
    .btn-acc:hover { background: #14532d; transform: translateY(-1px); }

    .btn-reject {
        background: #dc2626; color: white; border: none; padding: 5px 12px; border-radius: 6px; font-weight: 700; font-size: 0.775rem; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s;
    }
    .btn-reject:hover { background: #b91c1c; transform: translateY(-1px); }

    /* Modal Standard SIKTN */
    .modal-overlay {
        position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(2, 38, 72, 0.48); backdrop-filter: blur(4px);
        display: none; align-items: center; justify-content: center;
        z-index: 9999; padding: 1.5rem; opacity: 0; visibility: hidden; pointer-events: none; transition: all 0.2s ease;
    }
    .modal-overlay.active { display: flex; opacity: 1; visibility: visible; pointer-events: auto; }
    .modal-content-md {
        background: white; border-radius: 12px; max-width: 500px; width: 100%; box-shadow: 0 20px 40px rgba(0,0,0,0.2); overflow: hidden;
    }
</style>
@endpush

@section('content')
<div class="admin-ui-scope">
    
    <div style="background: white; border-radius: 10px; border: 1px solid #cbd5e1; padding: 1.25rem 1.5rem; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h3 style="font-size: 1.1rem; font-weight: 800; color: #022648; margin: 0;">Persetujuan & Verifikasi Jabatan Pengurus</h3>
            <span style="font-size: 0.8rem; color: #64748b;">Daftar pengajuan klaim posisi jabatan dari Admin Daerah (PPKT / PKKT) dan Pengurus Pusat (PNKT)</span>
        </div>

        <div style="display: flex; gap: 0.5rem;">
            <a href="{{ route('admin.verifikasi-jabatan.index') }}" class="btn {{ !request('status') ? 'btn-primary' : 'btn-outline-secondary' }}" style="font-size: 0.8rem; font-weight: 700; padding: 6px 14px; border-radius: 6px; text-decoration: none;">Semua</a>
            <a href="{{ route('admin.verifikasi-jabatan.index', ['status' => 'pending']) }}" class="btn {{ request('status') === 'pending' ? 'btn-primary' : 'btn-outline-secondary' }}" style="font-size: 0.8rem; font-weight: 700; padding: 6px 14px; border-radius: 6px; text-decoration: none; color: #b7830f;">🟡 Pending</a>
            <a href="{{ route('admin.verifikasi-jabatan.index', ['status' => 'approved']) }}" class="btn {{ request('status') === 'approved' ? 'btn-primary' : 'btn-outline-secondary' }}" style="font-size: 0.8rem; font-weight: 700; padding: 6px 14px; border-radius: 6px; text-decoration: none; color: #166534;">🟢 Approved</a>
            <a href="{{ route('admin.verifikasi-jabatan.index', ['status' => 'rejected']) }}" class="btn {{ request('status') === 'rejected' ? 'btn-primary' : 'btn-outline-secondary' }}" style="font-size: 0.8rem; font-weight: 700; padding: 6px 14px; border-radius: 6px; text-decoration: none; color: #991b1b;">🔴 Rejected</a>
        </div>
    </div>

    <div class="table-container">
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>NAMA ADMIN / PENGURUS</th>
                        <th>ROLE / KATEGORI</th>
                        <th>DOMISILI WILAYAH</th>
                        <th>JABATAN DIAJUKAN</th>
                        <th>STATUS ACC</th>
                        <th style="text-align: center; width: 180px;">AKSI VERIFIKASI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuans as $item)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    @if($item->photo || $item->foto_profil)
                                        <img src="{{ Storage::url($item->photo ?? $item->foto_profil) }}" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover;">
                                    @else
                                        <div style="width: 36px; height: 36px; border-radius: 50%; background: #022648; color: white; font-weight: 700; display: flex; align-items: center; justify-content: center; font-size: 0.85rem;">
                                            {{ strtoupper(substr($item->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <strong style="color: #022648;">{{ $item->name }}</strong>
                                        <div style="font-size: 0.775rem; color: #64748b;">{{ $item->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span style="background: #f1f5f9; color: #334155; padding: 3px 8px; border-radius: 4px; font-weight: 700; font-size: 0.75rem;">
                                    {{ strtoupper($item->category) }}
                                </span>
                            </td>
                            <td>
                                <strong style="color: #b7830f; font-size: 0.825rem;">{{ $item->domisili ?? 'Nasional' }}</strong>
                            </td>
                            <td>
                                <span style="font-weight: 800; color: #022648; font-size: 0.875rem;">
                                    {{ $item->jabatan_diajukan }}
                                </span>
                            </td>
                            <td>
                                @if($item->status_jabatan === 'pending')
                                    <span style="background: #fef3c7; color: #b7830f; border: 1px solid #fef08a; padding: 3px 8px; border-radius: 20px; font-weight: 700; font-size: 0.725rem;">
                                        Menunggu ACC
                                    </span>
                                @elseif($item->status_jabatan === 'approved')
                                    <span style="background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; padding: 3px 8px; border-radius: 20px; font-weight: 700; font-size: 0.725rem;">
                                        Disetujui
                                    </span>
                                @elseif($item->status_jabatan === 'rejected')
                                    <span style="background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; padding: 3px 8px; border-radius: 20px; font-weight: 700; font-size: 0.725rem;">
                                        Ditolak
                                    </span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <div style="display: flex; gap: 6px; justify-content: center;">
                                    @if($item->status_jabatan !== 'approved')
                                        <form action="{{ route('admin.verifikasi-jabatan.approve', $item->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn-acc" onclick="return confirm('Apakah Anda yakin ingin memverifikasi (ACC) jabatan {{ addslashes($item->jabatan_diajukan) }} untuk {{ addslashes($item->name) }}?')">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                ACC
                                            </button>
                                        </form>
                                    @endif

                                    @if($item->status_jabatan !== 'rejected')
                                        <button type="button" class="btn-reject" onclick="openRejectModal({{ $item->id }}, '{{ addslashes($item->name) }}')">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                            Tolak
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2.5rem; color: #64748b;">
                                Belum ada pengajuan jabatan pengurus.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding: 1rem;">
            {{ $pengajuans->links() }}
        </div>
    </div>
</div>

<!-- Modal Tolak Pengajuan -->
<div class="modal-overlay" id="rejectModal" onclick="if(event.target===this) closeRejectModal()">
    <div class="modal-content-md">
        <div style="background: linear-gradient(135deg, #022648 0%, #01162f 100%); padding: 1.2rem; color: white; display: flex; justify-content: space-between; align-items: center;">
            <h4 style="margin: 0; font-size: 1rem; font-weight: 800;">Tolak Pengajuan Jabatan</h4>
            <button type="button" onclick="closeRejectModal()" style="background: transparent; border: none; color: white; font-size: 1.2rem; cursor: pointer;">&times;</button>
        </div>
        <form id="rejectForm" method="POST">
            @csrf
            <div style="padding: 1.25rem;">
                <p style="font-size: 0.85rem; color: #334155; margin-bottom: 0.75rem;" id="rejectModalText">Berikan alasan penolakan pengajuan jabatan:</p>
                <textarea name="alasan" class="form-control" style="width: 100%; height: 90px; font-size: 0.85rem; border: 1px solid #cbd5e1; border-radius: 6px; padding: 8px;" placeholder="Contoh: Dokumen pendukung belum sesuai SK daerah..."></textarea>
            </div>
            <div style="padding: 1rem 1.25rem; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" onclick="closeRejectModal()" class="btn btn-outline-secondary" style="font-size: 0.8rem; font-weight: 600;">Batal</button>
                <button type="submit" class="btn btn-danger" style="font-size: 0.8rem; font-weight: 700; background: #dc2626; color: white; border: none; padding: 6px 14px; border-radius: 6px;">Konfirmasi Tolak</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openRejectModal(adminId, adminName) {
        document.getElementById('rejectForm').action = "/admin/verifikasi-jabatan/" + adminId + "/reject";
        document.getElementById('rejectModalText').textContent = `Berikan alasan penolakan pengajuan jabatan untuk ${adminName}:`;
        document.getElementById('rejectModal').classList.add('active');
    }
    function closeRejectModal() {
        document.getElementById('rejectModal').classList.remove('active');
    }
</script>
@endpush
@endsection
