@extends('admin.layouts.admin-layout')

@section('title', 'Detail Program')
@section('page-title', 'Detail Program')

@php
    $activeMenu = 'program';
    $admin = auth()->guard('admin')->user();
@endphp

@push('styles')
<style>
    @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');

    .detail-container {
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* Header Section */
    .detail-header {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .detail-header-content {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .detail-info h2 {
        font-size: 1.5rem;
        color: #0a2540;
        margin-bottom: 0.75rem;
        font-weight: 700;
    }

    .detail-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        color: #6b7280;
        font-size: 0.875rem;
    }

    .detail-meta span {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .detail-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    /* Buttons */
    .btn {
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        font-size: 0.875rem;
        font-family: 'Montserrat', sans-serif;
    }

    .btn:hover {
        transform: translateY(-1px);
    }

    .btn svg {
        width: 18px;
        height: 18px;
        stroke-width: 2;
    }

    .btn-back {
        background: #f3f4f6;
        color: #374151;
        border: 1px solid #e5e7eb;
    }

    .btn-back:hover {
        background: #e5e7eb;
    }

    .btn-edit {
        background: #022648;
        color: white;
    }

    .btn-edit:hover {
        background: #ffd700;
        color: #0a2540;
    }

    .btn-delete {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }

    .btn-delete:hover {
        background: #fee2e2;
    }

    .btn-status {
        background: #f0fdf4;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .btn-status:hover {
        background: #dcfce7;
    }

    .btn-status-warning {
        background: #fffbeb;
        color: #92400e;
        border: 1px solid #fde68a;
    }

    .btn-status-warning:hover {
        background: #fef3c7;
    }

    /* Status Card */
    .status-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 1.5rem;
    }

    .card-title {
        font-size: 1rem;
        font-weight: 700;
        color: #0a2540;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #f3f4f6;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
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
        padding: 0.375rem 0.875rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.75rem;
    }

    .status-perencanaan { background: #fef08a; color: #854d0e; }
    .status-berjalan { background: #bfdbfe; color: #1e40af; }
    .status-selesai { background: #bbf7d0; color: #166534; }

    /* Tabs */
    .tabs-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .tabs-header {
        display: flex;
        border-bottom: 2px solid #f3f4f6;
        background: #f9fafb;
        overflow-x: auto;
    }

    .tab-button {
        flex: 1;
        min-width: fit-content;
        padding: 1rem 1.5rem;
        background: transparent;
        border: none;
        font-size: 0.9375rem;
        font-weight: 600;
        color: #6b7280;
        cursor: pointer;
        transition: all 0.3s;
        border-bottom: 3px solid transparent;
        white-space: nowrap;
        font-family: 'Montserrat', sans-serif;
    }

    .tab-button:hover {
        background: #f3f4f6;
        color: #374151;
    }

    .tab-button.active {
        color: #022648;
        background: white;
        border-bottom-color: #022648;
    }

    .tabs-content {
        padding: 2rem;
    }

    .tab-panel {
        display: none;
        animation: fadeIn 0.3s ease-in;
    }

    .tab-panel.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Field Groups */
    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .field-group {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 1.25rem;
        transition: all 0.2s;
    }

    .field-group:hover {
        background-color: #ffffff;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        border-color: #cbd5e1;
    }

    .field-group.full-width {
        grid-column: 1 / -1;
    }

    .field-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .field-value {
        font-size: 1rem;
        font-weight: 500;
        color: #0f172a;
        line-height: 1.6;
        word-wrap: break-word;
    }

    /* Image Preview */
    .image-preview-container {
        margin-top: 0.5rem;
    }

    .image-preview-container img {
        width: 200px;
        height: 200px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #e5e7eb;
    }

    .no-image {
        width: 200px;
        height: 200px;
        background: #f3f4f6;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #9ca3af;
        font-size: 0.875rem;
        border: 2px solid #e5e7eb;
    }

    /* Info Boxes */
    .info-box {
        margin-top: 1rem;
        padding: 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
    }

    .info-box.info {
        background: #eff6ff;
        color: #1e3a8a;
        border: 1px solid #bfdbfe;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .detail-header {
            padding: 1.25rem;
        }

        .detail-header-content {
            flex-direction: column;
            align-items: stretch;
        }

        .detail-info h2 {
            font-size: 1.25rem;
        }

        .tabs-content {
            padding: 1.25rem;
        }

        .detail-grid {
            grid-template-columns: 1fr;
        }

        .detail-actions {
            width: 100%;
        }

        .btn {
            flex: 1;
            justify-content: center;
        }
    }

    /* SIKTN Benchmark Action Dropdown Trigger (⋮) */
    .btn-aksi-trigger {
        width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center;
        background: #022648; color: #fff; border: none; border-radius: 6px; cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 1px 3px rgba(2, 38, 72, 0.12);
    }
    .btn-aksi-trigger:hover {
        background: #18227C; transform: scale(1.08) translateY(-1px); box-shadow: 0 4px 12px rgba(2, 38, 72, 0.25);
    }
    .aksi-dropdown {
        display: block; position: fixed; min-width: 190px; background: #fff; border: 1px solid #e2e8f0;
        border-radius: 6px; box-shadow: 0 14px 32px rgba(2, 38, 72, 0.18); padding: 6px; z-index: 9999;
        opacity: 0; visibility: hidden; transform: translateY(-8px) scale(0.96);
        transition: opacity 0.18s cubic-bezier(0.16, 1, 0.3, 1), transform 0.18s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.18s;
        pointer-events: none;
    }
    .aksi-dropdown.is-open {
        opacity: 1; visibility: visible; transform: translateY(0) scale(1); pointer-events: auto;
    }
    .aksi-item {
        display: flex; align-items: center; gap: 9px; width: 100%; padding: 0.55rem 0.65rem; font-size: 0.8125rem; font-weight: 600;
        border-radius: 4px; color: #0f172a; text-decoration: none !important; border: none; background: transparent;
        text-align: left; cursor: pointer; transition: all 0.18s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .aksi-item:hover { background: #f1f5f9; transform: translateX(4px); color: #022648; }
</style>
@endpush

@section('content')
<div class="detail-container">
    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 1.5rem; padding: 1rem; background-color: #d1fae5; color: #059669; border-radius: 8px; border: 1px solid #6ee7b7;">
            <i class="fa fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="detail-header">
        <div class="detail-header-content">
            <div class="detail-info">
                <h2>{{ $program->nama_program }}</h2>
                <div class="detail-meta">
                    <span>
                        <strong>Kategori:</strong>
                        <span class="badge {{ $program->kategori == 'CSR' ? 'badge-csr' : 'badge-bidang' }}">
                            {{ $program->kategori }}
                        </span>
                    </span>
                    <span>
                        <strong>Dibuat:</strong>
                        {{ $program->created_at->format('d M Y') }}
                    </span>
                </div>
            </div>
            <div class="detail-actions">
                <a href="{{ route('admin.program.index') }}" class="btn btn-back">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M19 12H5M12 19l-7-7 7-7" />
                    </svg>
                    Kembali
                </a>

                @if($admin->isSuperAdmin() || $admin->isPNKT())
                    {{-- Update Status Buttons for Bidang --}}
                    @if($program->kategori == 'Bidang')
                        @if($program->status == 'Perencanaan')
                            <form action="{{ route('admin.program.update-status', $program->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="Berjalan">
                                <button type="submit" class="btn btn-status-warning" title="Mulai Program">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="16" height="16">
                                        <polygon points="5 3 19 12 5 21 5 3" />
                                    </svg>
                                    Mulai Program
                                </button>
                            </form>
                        @elseif($program->status == 'Berjalan')
                            <form action="{{ route('admin.program.update-status', $program->id) }}" method="POST" style="display:inline;" class="form-selesai">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="Selesai">
                                <button type="submit" class="btn btn-status" title="Selesaikan Program">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="16" height="16">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                    Selesaikan
                                </button>
                            </form>
                        @endif
                    @endif

                    <a href="{{ route('admin.program.edit', $program->id) }}" class="btn btn-edit">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                        </svg>
                        Edit
                    </a>
                    <form action="{{ route('admin.program.destroy', $program->id) }}" method="POST" style="display:inline;" class="form-delete">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-delete">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <polyline points="3 6 5 6 21 6" />
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                            </svg>
                            Hapus
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    {{-- SIKTN Benchmark Summary Stat Cards (4px left border) --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
        <!-- Card 1: Status Program -->
        <div style="background: white; border-radius: 6px; padding: 1.25rem; border-left: 4px solid #022648; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
            <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Status Program</div>
            <div style="margin-top: 8px; display: flex; align-items: center; justify-content: space-between;">
                <span class="badge badge-status status-{{ strtolower($program->status) }}" style="border-radius: 4px; font-size: 0.85rem; padding: 4px 10px;">
                    {{ $program->status }}
                </span>
                <i class="fa fa-flag" style="font-size: 1.3rem; color: #022648; opacity: 0.7;"></i>
            </div>
        </div>

        <!-- Card 2: Jumlah Peserta Anggota -->
        <div style="background: white; border-radius: 6px; padding: 1.25rem; border-left: 4px solid #b7830f; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
            <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Peserta Anggota</div>
            <div style="margin-top: 6px; display: flex; align-items: center; justify-content: space-between;">
                <span style="font-size: 1.5rem; font-weight: 800; color: #022648;">
                    {{ $program->peserta->count() }} <span style="font-size: 0.85rem; font-weight: 600; color: #64748b;">Orang</span>
                </span>
                <i class="fa fa-users" style="font-size: 1.3rem; color: #b7830f; opacity: 0.8;"></i>
            </div>
        </div>

        <!-- Card 3: Sisa Hari Pelaksanaan -->
        @php
            $today = \Carbon\Carbon::now()->startOfDay();
            $mulai = \Carbon\Carbon::parse($program->periode_mulai)->startOfDay();
            $selesai = \Carbon\Carbon::parse($program->periode_selesai)->startOfDay();
            
            if ($today->gt($selesai)) {
                $sisaText = 'Selesai';
                $sisaColor = '#10b981';
            } elseif ($today->lt($mulai)) {
                $diff = (int) round($today->diffInDays($mulai));
                $sisaText = $diff . ' Hari Lagi (Mulai)';
                $sisaColor = '#3b82f6';
            } else {
                $diff = (int) round($today->diffInDays($selesai)) + 1;
                $sisaText = $diff . ' Hari Sisa (Berjalan)';
                $sisaColor = '#f59e0b';
            }
        @endphp
        <div style="background: white; border-radius: 6px; padding: 1.25rem; border-left: 4px solid {{ $sisaColor }}; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
            <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Sisa Pelaksanaan</div>
            <div style="margin-top: 6px; display: flex; align-items: center; justify-content: space-between;">
                <span style="font-size: 1.1rem; font-weight: 800; color: {{ $sisaColor }};">
                    {{ $sisaText }}
                </span>
                <i class="fa fa-clock-o" style="font-size: 1.3rem; color: {{ $sisaColor }}; opacity: 0.8;"></i>
            </div>
        </div>
    </div>

    {{-- Tabs Container --}}
    <div class="tabs-container">
        <div class="tabs-header">
            <button class="tab-button active" onclick="switchTab('info')">
                <i class="fa fa-info-circle" style="margin-right: 0.5rem;"></i>
                Info Program
            </button>
            <button class="tab-button" onclick="switchTab('pelaksanaan')">
                <i class="fa fa-calendar" style="margin-right: 0.5rem;"></i>
                Pelaksanaan
            </button>
            <button class="tab-button" onclick="switchTab('target')">
                <i class="fa fa-bullseye" style="margin-right: 0.5rem;"></i>
                Target & Output
            </button>
            <button class="tab-button" onclick="switchTab('peserta')">
                <i class="fa fa-users" style="margin-right: 0.5rem;"></i>
                Peserta Anggota ({{ $program->peserta->count() }})
            </button>
        </div>

        <div class="tabs-content">
            {{-- Tab Info Program --}}
            <div class="tab-panel active" id="tab-info">
                <div class="detail-grid">
                    <div class="field-group">
                        <div class="field-label">Nama Program</div>
                        <div class="field-value">{{ $program->nama_program }}</div>
                    </div>

                    <div class="field-group">
                        <div class="field-label">Kategori</div>
                        <div class="field-value">
                            <span class="badge {{ $program->kategori == 'CSR' ? 'badge-csr' : 'badge-bidang' }}">
                                {{ $program->kategori }}
                            </span>
                        </div>
                    </div>

                    @if($program->kategori == 'CSR' && $program->mitra)
                    <div class="field-group">
                        <div class="field-label">Mitra / Sponsor</div>
                        <div class="field-value">{{ $program->mitra }}</div>
                    </div>
                    @endif

                    @if($program->kategori == 'Bidang' && $program->jabatan)
                    <div class="field-group">
                        <div class="field-label">Bidang</div>
                        <div class="field-value">{{ $program->jabatan->nama_jabatan }}</div>
                    </div>
                    @endif

                    <div class="field-group">
                        <div class="field-label">Status</div>
                        <div class="field-value">
                            <span class="badge badge-status status-{{ strtolower($program->status) }}">
                                {{ $program->status }}
                            </span>
                        </div>
                    </div>

                    <div class="field-group full-width">
                        <div class="field-label">Gambar / Thumbnail</div>
                        <div class="field-value">
                            @if($program->gambar)
                                <div class="image-preview-container">
                                    <img src="{{ $program->gambar_url }}" alt="{{ $program->nama_program }}" style="max-width: 300px; border-radius: 8px; border: 1px solid #e5e7eb;">
                                </div>
                            @else
                                <div class="no-image">
                                    <i class="fa fa-image" style="font-size: 2rem; margin-right: 0.5rem;"></i>
                                    Belum ada gambar
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab Pelaksanaan --}}
            <div class="tab-panel" id="tab-pelaksanaan">
                <div class="detail-grid">
                    <div class="field-group">
                        <div class="field-label"><i class="fa fa-calendar-plus-o"></i> Periode Mulai</div>
                        <div class="field-value">{{ $program->periode_mulai->format('d F Y') }}</div>
                    </div>

                    <div class="field-group">
                        <div class="field-label"><i class="fa fa-calendar-check-o"></i> Periode Selesai</div>
                        <div class="field-value">{{ $program->periode_selesai->format('d F Y') }}</div>
                    </div>

                    <div class="field-group">
                        <div class="field-label"><i class="fa fa-user"></i> PIC / Penanggung Jawab</div>
                        <div class="field-value">{{ $program->pic }}</div>
                    </div>

                    <div class="field-group">
                        <div class="field-label"><i class="fa fa-clock-o"></i> Durasi</div>
                        <div class="field-value">
                            {{ $program->periode_mulai->diffInDays($program->periode_selesai) + 1 }} hari
                        </div>
                    </div>

                    @if($program->kategori == 'Bidang' && $admin->isSuperAdmin())
                    <div class="field-group" style="display: none;">
                        <div class="field-label"><i class="fa fa-money"></i> Anggaran</div>
                        <div class="field-value">Rp {{ number_format($program->anggaran ?? 0, 0, ',', '.') }}</div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Tab Target & Output --}}
            <div class="tab-panel" id="tab-target">
                <div class="detail-grid">
                    <div class="field-group full-width">
                        <div class="field-label"><i class="fa fa-bullseye"></i> Target Output / Deskripsi</div>
                        <div class="field-value" style="white-space: pre-line;">{{ $program->target_output }}</div>
                    </div>

                    @if($program->kategori == 'Bidang' && $admin->isSuperAdmin())
                    <div class="field-group" style="display: none;">
                        <div class="field-label"><i class="fa fa-money"></i> Total Anggaran</div>
                        <div class="field-value">Rp {{ number_format($program->anggaran ?? 0, 0, ',', '.') }}</div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Tab Peserta Anggota --}}
            <div class="tab-panel" id="tab-peserta">
                <div style="margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                    <h3 style="font-size: 1.1rem; font-weight: 700; color: #022648; margin: 0;">
                        Daftar Peserta Terdaftar ({{ $program->peserta->count() }})
                    </h3>
                    <a href="{{ route('admin.program.export-peserta', $program->id) }}" class="btn" style="background: #022648; color: #ffd700; border: none; font-weight: 700; font-size: 0.8125rem; padding: 8px 16px; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;" onclick="Toast.fire({ icon: 'success', title: 'Export Data Peserta Berhasil!' });">
                        <i class="fa fa-file-excel-o"></i> Export Data Peserta (.xls)
                    </a>
                </div>

                <div style="overflow-x: auto; background: white; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                        <thead>
                            <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; color: #022648; text-align: left;">
                                <th style="padding: 12px 16px; font-weight: 700;">#</th>
                                <th style="padding: 12px 16px; font-weight: 700;">Anggota</th>
                                <th style="padding: 12px 16px; font-weight: 700;">NIA / Username</th>
                                <th style="padding: 12px 16px; font-weight: 700;">Wilayah</th>
                                <th style="padding: 12px 16px; font-weight: 700;">Kontak WA / Email</th>
                                <th style="padding: 12px 16px; font-weight: 700;">Tanggal Join</th>
                                <th style="padding: 12px 16px; font-weight: 700; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($program->peserta as $index => $p)
                            @php
                                $cleanHp = preg_replace('/[^0-9]/', '', $p->no_hp ?? '');
                                if(str_starts_with($cleanHp, '0')) {
                                    $cleanHp = '62' . substr($cleanHp, 1);
                                }
                            @endphp
                            <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                                <td style="padding: 12px 16px; font-weight: 600; color: #64748b;">{{ $index + 1 }}</td>
                                <td style="padding: 12px 16px;">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        @if($p->foto_diri)
                                            <img src="{{ Storage::url($p->foto_diri) }}" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover; border: 1.5px solid #cbd5e1;">
                                        @else
                                            <div style="width: 36px; height: 36px; border-radius: 50%; background: #022648; color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.8rem;">
                                                {{ strtoupper(substr($p->nama_lengkap, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div style="font-weight: 700; color: #022648;">{{ $p->nama_lengkap }}</div>
                                            <div style="font-size: 0.75rem; color: #64748b;">{{ $p->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 12px 16px; font-weight: 600; color: #334155;">
                                    <div>{{ $p->nik ?? '-' }}</div>
                                    <div style="font-size: 0.75rem; color: #64748b;">@ {{ $p->username }}</div>
                                </td>
                                <td style="padding: 12px 16px; font-weight: 600; color: #1e293b;">
                                    <span style="background: #e2e8f0; color: #0f172a; padding: 2px 8px; border-radius: 4px; font-size: 0.75rem;">
                                        {{ $p->domisili ?? 'Nasional' }}
                                    </span>
                                </td>
                                <td style="padding: 12px 16px;">
                                    @if($p->no_hp)
                                        <a href="https://wa.me/{{ $cleanHp }}" target="_blank" style="color: #059669; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                            <i class="fa fa-whatsapp"></i> {{ $p->no_hp }}
                                        </a>
                                    @else
                                        <span style="color: #94a3b8;">-</span>
                                    @endif
                                </td>
                                <td style="padding: 12px 16px; color: #64748b; font-size: 0.8rem;">
                                    {{ $p->pivot->created_at ? $p->pivot->created_at->format('d M Y H:i') : '-' }}
                                </td>
                                <td style="padding: 12px 16px; text-align: center;" class="cell-aksi">
                                    <div class="aksi-wrapper" style="position: relative; display: inline-block;">
                                        <button type="button" class="btn-aksi-trigger" data-target="dropdown-peserta-{{ $p->id }}" aria-label="Menu aksi">
                                            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                                <circle cx="12" cy="5" r="1.75"></circle>
                                                <circle cx="12" cy="12" r="1.75"></circle>
                                                <circle cx="12" cy="19" r="1.75"></circle>
                                            </svg>
                                        </button>
                                        <div class="aksi-dropdown" id="dropdown-peserta-{{ $p->id }}" style="right: 0; left: auto;">
                                            <button type="button" class="aksi-item" onclick="openAnggotaDrawer({{ json_encode($p) }})">
                                                <i class="fa fa-user-circle"></i> Lihat Detail Anggota
                                            </button>
                                            @if($p->no_hp)
                                                <a href="https://wa.me/{{ $cleanHp }}" target="_blank" class="aksi-item" style="color: #059669;">
                                                    <i class="fa fa-whatsapp"></i> Hubungi WhatsApp
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" style="padding: 2rem; text-align: center; color: #64748b;">
                                    <i class="fa fa-info-circle" style="font-size: 1.5rem; margin-bottom: 0.5rem; display: block;"></i>
                                    Belum ada anggota yang mendaftar pada program ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Drawer Slide Pop-Up Detail Anggota (SIKTN Benchmark) -->
<div id="drawerOverlay" style="position: fixed; inset: 0; background: rgba(2, 38, 72, 0.4); backdrop-filter: blur(3px); opacity: 0; visibility: hidden; transition: all 0.3s ease; z-index: 99998;" onclick="closeAnggotaDrawer()"></div>

<div id="detailAnggotaDrawer" style="position: fixed; top: 0; right: 0; bottom: 0; width: 440px; max-width: 90vw; background: #ffffff; box-shadow: -10px 0 30px rgba(2, 38, 72, 0.2); transform: translateX(100%); transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1); z-index: 99999; display: flex; flex-direction: column; overflow: hidden;">
    <div style="background: #022648; color: #ffffff; padding: 20px 24px; display: flex; align-items: center; justify-content: space-between; border-bottom: 3px solid #b7830f;">
        <h3 style="margin: 0; font-size: 1.1rem; font-weight: 700; color: #ffffff; display: flex; align-items: center; gap: 8px;">
            <i class="fa fa-user-circle"></i> Detail Profil Anggota
        </h3>
        <button type="button" onclick="closeAnggotaDrawer()" style="background: transparent; border: none; color: #ffffff; font-size: 1.4rem; cursor: pointer; padding: 4px; line-height: 1;">&times;</button>
    </div>

    <div style="flex: 1; overflow-y: auto; padding: 24px;">
        <div style="text-align: center; margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid #e2e8f0;">
            <div id="drawerAvatarWrap" style="width: 90px; height: 90px; border-radius: 50%; overflow: hidden; margin: 0 auto 12px; border: 3px solid #b7830f; box-shadow: 0 4px 14px rgba(2, 38, 72, 0.15); display: flex; align-items: center; justify-content: center; background: #022648; color: white; font-size: 2rem; font-weight: 800;">
            </div>
            <h4 id="drawerNama" style="margin: 0 0 4px; font-size: 1.15rem; font-weight: 800; color: #022648;"></h4>
            <div id="drawerNia" style="font-family: monospace; font-size: 0.85rem; font-weight: 700; color: #64748b;"></div>
            <div id="drawerStatusBadge" style="margin-top: 8px;"></div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 14px; font-size: 0.875rem;">
            <div style="background: #f8fafc; padding: 12px 14px; border-radius: 6px; border: 1px solid #e2e8f0;">
                <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Username</div>
                <div id="drawerUsername" style="font-weight: 600; color: #0f172a; margin-top: 2px;"></div>
            </div>
            <div style="background: #f8fafc; padding: 12px 14px; border-radius: 6px; border: 1px solid #e2e8f0;">
                <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Wilayah / Domisili</div>
                <div id="drawerDomisili" style="font-weight: 600; color: #0f172a; margin-top: 2px;"></div>
            </div>
            <div style="background: #f8fafc; padding: 12px 14px; border-radius: 6px; border: 1px solid #e2e8f0;">
                <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Jabatan</div>
                <div id="drawerJabatan" style="font-weight: 600; color: #0f172a; margin-top: 2px;"></div>
            </div>
            <div style="background: #f8fafc; padding: 12px 14px; border-radius: 6px; border: 1px solid #e2e8f0;">
                <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">No WhatsApp</div>
                <div id="drawerWa" style="font-weight: 600; color: #059669; margin-top: 2px;"></div>
            </div>
            <div style="background: #f8fafc; padding: 12px 14px; border-radius: 6px; border: 1px solid #e2e8f0;">
                <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Email</div>
                <div id="drawerEmail" style="font-weight: 600; color: #0f172a; margin-top: 2px;"></div>
            </div>
            <div style="background: #f8fafc; padding: 12px 14px; border-radius: 6px; border: 1px solid #e2e8f0;">
                <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Riwayat Organisasi</div>
                <div id="drawerRiwayat" style="font-weight: 500; color: #334155; margin-top: 2px; white-space: pre-line;"></div>
            </div>
            <div style="background: #f8fafc; padding: 12px 14px; border-radius: 6px; border: 1px solid #e2e8f0;">
                <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Kompetensi / Keahlian</div>
                <div id="drawerKompetensi" style="font-weight: 500; color: #334155; margin-top: 2px; white-space: pre-line;"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function switchTab(tabName) {
        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('active');
        });
        document.querySelectorAll('.tab-panel').forEach(panel => {
            panel.classList.remove('active');
        });

        event.target.classList.add('active');
        document.getElementById('tab-' + tabName).classList.add('active');
    }

    function openAnggotaDrawer(anggota) {
        const overlay = document.getElementById('drawerOverlay');
        const drawer = document.getElementById('detailAnggotaDrawer');
        const avatarWrap = document.getElementById('drawerAvatarWrap');
        
        if (anggota.foto_diri) {
            avatarWrap.innerHTML = `<img src="/storage/${anggota.foto_diri}" style="width:100%;height:100%;object-fit:cover;">`;
        } else {
            avatarWrap.innerHTML = (anggota.nama_lengkap || 'A').charAt(0).toUpperCase();
        }

        document.getElementById('drawerNama').textContent = anggota.nama_lengkap || '-';
        document.getElementById('drawerNia').textContent = 'NIA: ' + (anggota.nik || '-');
        document.getElementById('drawerUsername').textContent = '@' + (anggota.username || '-');
        document.getElementById('drawerDomisili').textContent = anggota.domisili || 'Nasional';
        document.getElementById('drawerJabatan').textContent = anggota.jabatan || '-';
        document.getElementById('drawerWa').textContent = anggota.no_hp || '-';
        document.getElementById('drawerEmail').textContent = anggota.email || '-';
        document.getElementById('drawerRiwayat').textContent = anggota.riwayat_organisasi || '-';
        document.getElementById('drawerKompetensi').textContent = anggota.kompetensi || '-';

        const statusBadge = document.getElementById('drawerStatusBadge');
        if (anggota.status === 'approved') {
            statusBadge.innerHTML = '<span style="background:#dcfce7;color:#166534;padding:4px 12px;border-radius:20px;font-size:0.75rem;font-weight:700;">✔ ANGGOTA AKTIF</span>';
        } else {
            statusBadge.innerHTML = '<span style="background:#fef3c7;color:#92400e;padding:4px 12px;border-radius:20px;font-size:0.75rem;font-weight:700;">⏳ MENUNGGU VERIFIKASI</span>';
        }

        overlay.style.visibility = 'visible';
        overlay.style.opacity = '1';
        drawer.style.transform = 'translateX(0)';
    }

    function closeAnggotaDrawer() {
        const overlay = document.getElementById('drawerOverlay');
        const drawer = document.getElementById('detailAnggotaDrawer');
        overlay.style.opacity = '0';
        overlay.style.visibility = 'hidden';
        drawer.style.transform = 'translateX(100%)';
    }

    document.addEventListener('DOMContentLoaded', function() {
        // SIKTN Benchmark Action Dropdown Trigger (⋮)
        let activeDropdown = null;

        document.querySelectorAll('.btn-aksi-trigger').forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                const targetId = this.getAttribute('data-target');
                const dropdown = document.getElementById(targetId);

                if (activeDropdown && activeDropdown !== dropdown) {
                    activeDropdown.classList.remove('is-open');
                }

                if (dropdown.classList.contains('is-open')) {
                    dropdown.classList.remove('is-open');
                    activeDropdown = null;
                } else {
                    const rect = this.getBoundingClientRect();
                    const dropdownHeight = 90;
                    if (rect.bottom + dropdownHeight > window.innerHeight) {
                        dropdown.style.top = (rect.top - dropdownHeight) + 'px';
                    } else {
                        dropdown.style.top = (rect.bottom + 4) + 'px';
                    }
                    dropdown.style.left = Math.max(10, Math.min(window.innerWidth - 200, rect.right - 190)) + 'px';
                    dropdown.classList.add('is-open');
                    activeDropdown = dropdown;
                }
            });
        });

        document.addEventListener('click', function(e) {
            if (activeDropdown && !e.target.closest('.aksi-dropdown')) {
                activeDropdown.classList.remove('is-open');
                activeDropdown = null;
            }
        });

        window.addEventListener('scroll', function() {
            if (activeDropdown) {
                activeDropdown.classList.remove('is-open');
                activeDropdown = null;
            }
        }, true);

        // Delete confirmation
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

        // Selesai confirmation
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

        // Auto hide alert
        setTimeout(() => {
            const alert = document.querySelector('.alert-success');
            if (alert) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        }, 5000);
    });
</script>
@endpush
