@extends('layouts.app')

@section('title', 'Detail Program Bidang - ' . $program->nama_program)

@push('styles')
<style>
    .detail-wrapper {
        background: white;
        border-radius: 6px;
        overflow: hidden;
        margin-bottom: 2rem;
        border: 1px solid #e5e7eb;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .detail-header {
        background: #0a2540;
        color: white;
        padding: 1.5rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .detail-header h2 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 700;
    }

    .back-link {
        background: rgba(255,255,255,0.2);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.2s;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .back-link:hover {
        background: rgba(255,255,255,0.3);
    }

    .detail-content {
        padding: 2.5rem;
        display: grid;
        grid-template-columns: 1fr 1.5fr;
        gap: 3rem;
    }

    .detail-image-wrapper {
        position: relative;
    }

    .detail-image-wrapper img {
        width: 100%;
        height: 300px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #e5e7eb;
    }

    .detail-status-badge {
        position: absolute;
        top: 1rem;
        left: 1rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        color: white;
        text-transform: uppercase;
    }

    .status-selesai { background: #10b981; }
    .status-berjalan { background: #f59e0b; }
    .status-perencanaan { background: #6b7280; }

    .detail-info h3 {
        font-size: 1.75rem;
        font-weight: 800;
        color: #0a2540;
        margin-bottom: 1.5rem;
        line-height: 1.3;
    }

    .detail-meta-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .detail-meta-item {
        background: #f9fafb;
        padding: 1rem;
        border-radius: 6px;
        border: 1px solid #e5e7eb;
    }

    .detail-meta-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }

    .detail-meta-value {
        font-size: 1rem;
        font-weight: 600;
        color: #0a2540;
    }

    .detail-desc-box {
        background: #f9fafb;
        padding: 1.5rem;
        border-radius: 6px;
        border: 1px solid #e5e7eb;
    }

    .detail-desc-title {
        font-size: 0.875rem;
        font-weight: 700;
        color: #0a2540;
        margin-bottom: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .detail-desc-text {
        font-size: 0.9375rem;
        color: #374151;
        line-height: 1.7;
    }

    @media (max-width: 992px) {
        .detail-content {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        .detail-image-wrapper img {
            height: 250px;
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
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<section class="wrapper-white-1">
    <div class="tujuan-section">
        <div class="detail-wrapper">
            <div class="detail-header">
                <h2><i class="fa fa-file-alt" style="margin-right: 0.5rem;"></i> Detail Program Bidang</h2>
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
                            <div class="detail-meta-label">Periode</div>
                            <div class="detail-meta-value">
                                {{ \Carbon\Carbon::parse($program->periode_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($program->periode_selesai)->format('d M Y') }}
                            </div>
                        </div>
                        @if($program->jabatan)
                        <div class="detail-meta-item">
                            <div class="detail-meta-label">Bidang</div>
                            <div class="detail-meta-value">{{ $program->jabatan->nama_jabatan }}</div>
                        </div>
                        @endif
                        <div class="detail-meta-item">
                            <div class="detail-meta-label">PIC</div>
                            <div class="detail-meta-value">{{ $program->pic }}</div>
                        </div>
                        <div class="detail-meta-item">
                            <div class="detail-meta-label">Kategori</div>
                            <div class="detail-meta-value">Bidang</div>
                        </div>
                    </div>
                    <div class="detail-desc-box">
                        <div class="detail-desc-title">Target Output</div>
                        <div class="detail-desc-text">{{ $program->target_output }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
