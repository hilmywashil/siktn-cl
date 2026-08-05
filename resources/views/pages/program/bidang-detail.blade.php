@extends('layouts.app')

@section('title', 'Detail Program Bidang - ' . $program->nama_program)

@push('styles')
<style>
    .detail-wrapper {
        background: #ffffff;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 2.5rem;
        border: 1px solid #e5e7eb;
        box-shadow: 0 10px 30px rgba(2, 38, 72, 0.06);
        transition: all 0.3s ease;
    }

    .detail-header {
        background: linear-gradient(135deg, #022648 0%, #0a3d6d 100%);
        color: white;
        padding: 1.75rem 2.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 3px solid #b7830f;
    }

    .detail-header h2 {
        margin: 0;
        font-size: 1.35rem;
        font-weight: 800;
        letter-spacing: -0.01em;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .back-link {
        background: rgba(255, 255, 255, 0.15);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.25);
        padding: 0.55rem 1.25rem;
        border-radius: 8px;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 700;
        transition: all 0.25s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        backdrop-filter: blur(4px);
    }

    .back-link:hover {
        background: rgba(255, 255, 255, 0.3);
        color: #ffffff;
        transform: translateX(-3px);
    }

    .detail-content {
        padding: 2.5rem;
        display: grid;
        grid-template-columns: 1fr 1.6fr;
        gap: 2.5rem;
        align-items: start;
    }

    .detail-image-wrapper {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        border: 1px solid #f1f5f9;
        background: #ffffff;
        height: fit-content;
        align-self: start;
    }

    .detail-image-wrapper img {
        width: 100%;
        height: auto;
        max-height: 380px;
        object-fit: cover;
        display: block;
        transition: transform 0.4s ease;
    }

    .detail-image-wrapper:hover img {
        transform: scale(1.03);
    }

    .detail-status-badge {
        position: absolute;
        top: 1.25rem;
        left: 1.25rem;
        padding: 0.45rem 1.1rem;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 800;
        color: white;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        z-index: 2;
    }

    .detail-status-badge::before {
        content: '';
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: currentColor;
        box-shadow: 0 0 8px currentColor;
    }

    .status-selesai { background: #059669; }
    .status-berjalan { background: #d97706; }
    .status-perencanaan { background: #4b5563; }

    .detail-info h3 {
        font-size: 1.85rem;
        font-weight: 800;
        color: #022648;
        margin-bottom: 1.5rem;
        line-height: 1.35;
        letter-spacing: -0.02em;
    }

    .detail-meta-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.1rem;
        margin-bottom: 1.75rem;
    }

    .detail-meta-item {
        background: #f8fafc;
        padding: 1.1rem 1.25rem;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        border-left: 4px solid #022648;
        transition: all 0.25s ease;
    }

    .detail-meta-item:hover {
        transform: translateY(-2px);
        background: #ffffff;
        box-shadow: 0 6px 16px rgba(2, 38, 72, 0.06);
    }

    .detail-meta-label {
        font-size: 0.725rem;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.7px;
        margin-bottom: 0.35rem;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .detail-meta-value {
        font-size: 0.975rem;
        font-weight: 700;
        color: #0f172a;
    }

    .detail-desc-box {
        background: #f8fafc;
        padding: 1.5rem;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        border-left: 4px solid #b7830f;
    }

    .detail-desc-title {
        font-size: 0.8rem;
        font-weight: 800;
        color: #022648;
        margin-bottom: 0.6rem;
        text-transform: uppercase;
        letter-spacing: 0.7px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .detail-desc-text {
        font-size: 0.95rem;
        color: #334155;
        line-height: 1.7;
        font-weight: 500;
    }

    .btn-action-primary {
        background: linear-gradient(135deg, #022648 0%, #0d3863 100%);
        color: white;
        border: none;
        padding: 0.85rem 2rem;
        border-radius: 8px;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.25s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 14px rgba(2, 38, 72, 0.2);
    }

    .btn-action-primary:hover {
        background: linear-gradient(135deg, #0a3d6d 0%, #022648 100%);
        box-shadow: 0 6px 20px rgba(2, 38, 72, 0.3);
        transform: translateY(-2px);
        color: #ffffff;
    }

    .btn-action-disabled {
        background: #10b981;
        color: white;
        border: none;
        padding: 0.85rem 2rem;
        border-radius: 8px;
        font-weight: 700;
        font-size: 1rem;
        cursor: not-allowed;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
    }

    @media (max-width: 992px) {
        .detail-content {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        .detail-image-wrapper img {
            height: 260px;
        }
    }

    @media (max-width: 768px) {
        .detail-content {
            padding: 1.5rem;
        }
        .detail-meta-grid {
            grid-template-columns: 1fr;
        }
        .detail-info h3 {
            font-size: 1.45rem;
        }
    }
</style>
@endpush

@section('content')
<section class="wrapper-white-1">
    <div class="tujuan-section">
        <div class="detail-wrapper">
            <div class="detail-header">
                <h2><i class="fa fa-file-alt"></i> Detail Program Bidang</h2>
                <a href="{{ route('program.bidang') }}" class="back-link">
                    <i class="fa fa-arrow-left"></i> Kembali
                </a>
            </div>
            <div class="detail-content">
                <div class="detail-image-wrapper">
                    @php
                        $statusClass = 'status-perencanaan';
                        if($program->status == 'Selesai') $statusClass = 'status-selesai';
                        if($program->status == 'Berjalan') $statusClass = 'status-berjalan';
                    @endphp
                    <span class="detail-status-badge {{ $statusClass }}">{{ $program->status }}</span>
                    <img src="{{ $program->gambar_url }}" alt="{{ $program->nama_program }}">
                </div>
                <div class="detail-info">
                    <h3>{{ $program->nama_program }}</h3>
                    <div class="detail-meta-grid">
                        <div class="detail-meta-item">
                            <div class="detail-meta-label">
                                <i class="fa fa-calendar-alt" style="color: #b7830f;"></i> Periode
                            </div>
                            <div class="detail-meta-value">
                                {{ \Carbon\Carbon::parse($program->periode_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($program->periode_selesai)->format('d M Y') }}
                            </div>
                        </div>
                        @if($program->jabatan)
                        <div class="detail-meta-item">
                            <div class="detail-meta-label">
                                <i class="fa fa-layer-group" style="color: #b7830f;"></i> Bidang
                            </div>
                            <div class="detail-meta-value">{{ $program->jabatan->nama_jabatan }}</div>
                        </div>
                        @endif
                        <div class="detail-meta-item">
                            <div class="detail-meta-label">
                                <i class="fa fa-user-tie" style="color: #b7830f;"></i> PIC Program
                            </div>
                            <div class="detail-meta-value">{{ $program->pic }}</div>
                        </div>
                        <div class="detail-meta-item">
                            <div class="detail-meta-label">
                                <i class="fa fa-tag" style="color: #b7830f;"></i> Kategori
                            </div>
                            <div class="detail-meta-value">Bidang</div>
                        </div>
                    </div>

                    <div class="detail-desc-box">
                        <div class="detail-desc-title">
                            <i class="fa fa-bullseye" style="color: #b7830f;"></i> Target Output
                        </div>
                        <div class="detail-desc-text">{{ $program->target_output }}</div>
                    </div>

                    @if($program->csrPrograms && $program->csrPrograms->count() > 0)
                        <div class="detail-desc-box" style="margin-top: 1.25rem; background: #f0fdf4; border: 1px solid #bbf7d0; border-left: 4px solid #16a34a; border-radius: 10px; padding: 1.25rem;">
                            <div class="detail-desc-title" style="color: #15803d; display: flex; align-items: center; gap: 8px; font-weight: 800; margin-bottom: 0.5rem;">
                                <i class="fa fa-handshake"></i> Program CSR Pendukung Terkait:
                            </div>
                            <div class="detail-desc-text">
                                <ul style="margin: 0; padding-left: 1.25rem; list-style-type: disc;">
                                    @foreach($program->csrPrograms as $csr)
                                        <li style="margin-bottom: 0.4rem;">
                                            <a href="{{ route('program.csr.detail', $csr->id) }}" style="font-weight: 700; color: #022648; text-decoration: underline; font-size: 0.95rem;">
                                                {{ $csr->nama_program }}
                                            </a>
                                            <span style="font-size: 0.85rem; color: #475569; font-weight: 600;">(Mitra: {{ $csr->mitra ?? 'Umum' }})</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    @php
                        $isJoined = false;
                        if (Auth::guard('anggota')->check()) {
                            $isJoined = $program->peserta()->where('anggota_id', Auth::guard('anggota')->id())->exists();
                        }
                    @endphp

                    <div style="margin-top: 1.75rem;">
                        @if($isJoined)
                            <button type="button" class="btn-action-disabled" disabled>
                                <i class="fas fa-check-circle"></i> Sudah Terdaftar
                            </button>
                        @else
                            <form id="joinProgramForm" action="{{ route('program.join', $program->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                <button type="button" class="btn-action-primary" onclick="confirmJoinProgram('{{ addslashes($program->nama_program) }}')">
                                    <i class="fas fa-paper-plane"></i> Join Program Kerja
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    function confirmJoinProgram(programName) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Konfirmasi Pendaftaran',
                html: `Apakah Anda yakin ingin mendaftar pada Program Kerja <strong>${programName}</strong>?<br><br><small style="color: #64748b;">Data pendaftaran Anda akan diajukan ke Sekretariat / Admin untuk diverifikasi.</small>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#022648',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: '<i class="fas fa-paper-plane"></i> Ya, Daftar Sekarang',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('joinProgramForm').submit();
                }
            });
        } else {
            if (confirm('Apakah Anda yakin ingin mendaftar pada Program Kerja ' + programName + '?')) {
                document.getElementById('joinProgramForm').submit();
            }
        }
    }
</script>
@endpush
