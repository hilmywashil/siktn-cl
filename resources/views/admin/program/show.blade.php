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

    {{-- Status Card --}}
    <div class="status-card">
        <div class="card-title">Status Program</div>
        <span class="badge badge-status status-{{ strtolower($program->status) }}">
            {{ $program->status }}
        </span>

        @if($program->kategori == 'CSR' && $program->mitra)
            <div class="info-box info" style="margin-top: 1rem;">
                <i class="fa fa-handshake-o" style="margin-right: 0.5rem;"></i>
                <strong>Mitra:</strong> {{ $program->mitra }}
            </div>
        @endif
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

    document.addEventListener('DOMContentLoaded', function() {
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
