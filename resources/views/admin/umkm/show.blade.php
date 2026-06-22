@extends('admin.layouts.admin-layout')

@section('title', 'Detail UMKM')
@section('page-title', 'Detail Pendaftar UMKM')

@php
    $activeMenu = 'umkm';
    $admin = auth()->guard('admin')->user();
@endphp

@push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap');

        * {
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
        }

        .detail-container {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
        }

        .detail-header {
            background: white;
            border-radius: 12px;
            padding: 1.75rem;
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

        .detail-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

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
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn svg {
            width: 18px;
            height: 18px;
            stroke-width: 2;
        }

        .btn-approve {
            background: #10b981;
            color: white;
        }

        .btn-approve:hover {
            background: #059669;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-reject {
            background: #ef4444;
            color: white;
        }

        .btn-reject:hover {
            background: #dc2626;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .btn-back {
            background: #f3f4f6;
            color: #374151;
        }

        .btn-back:hover {
            background: #e5e7eb;
        }

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

        .status-badge-large {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .status-badge-large.pending {
            background: #fef3c7;
            color: #d97706;
        }

        .status-badge-large.approved {
            background: #d1fae5;
            color: #059669;
        }

        .status-badge-large.rejected {
            background: #fee2e2;
            color: #dc2626;
        }

        .info-box {
            margin-top: 1rem;
            padding: 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
        }

        .info-box.success {
            background: #d1fae5;
            color: #059669;
            border: 1px solid #6ee7b7;
        }

        .info-box.danger {
            background: #fee2e2;
            color: #dc2626;
            border: 1px solid #fca5a5;
        }

        /* Tabs Styling */
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
        }

        .tab-button:hover {
            background: #f3f4f6;
            color: #374151;
        }

        .tab-button.active {
            color: #2563eb;
            background: white;
            border-bottom-color: #2563eb;
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
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .field-group {
            margin-bottom: 1.25rem;
        }

        .field-group:last-child {
            margin-bottom: 0;
        }

        .field-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.025em;
            margin-bottom: 0.5rem;
        }

        .field-value {
            font-size: 0.9375rem;
            color: #0a2540;
            font-weight: 500;
            word-wrap: break-word;
            word-break: break-word;
            line-height: 1.5;
        }

        .badges-container {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .badge {
            display: inline-block;
            padding: 0.375rem 0.875rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-info {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-primary {
            background: #e0e7ff;
            color: #3730a3;
        }

        .link-primary {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
        }

        .link-primary:hover {
            text-decoration: underline;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #0a2540;
            margin-bottom: 1rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .form-control {
            width: 100%;
            padding: 0.625rem 0.875rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.875rem;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .modal-actions {
            display: flex;
            gap: 0.75rem;
            justify-content: flex-end;
            margin-top: 1.5rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .detail-container {
                padding: 1.25rem;
            }

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

            .detail-meta {
                flex-direction: column;
                gap: 0.5rem;
            }

            .detail-actions {
                width: 100%;
            }

            .btn {
                flex: 1;
                justify-content: center;
            }

            .tabs-header {
                flex-wrap: nowrap;
            }

            .tab-button {
                font-size: 0.8125rem;
                padding: 0.875rem 1rem;
            }

            .tabs-content {
                padding: 1.25rem;
            }

            .detail-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .detail-header {
                padding: 1rem;
            }

            .detail-info h2 {
                font-size: 1.125rem;
            }

            .btn {
                padding: 0.5rem 1rem;
                font-size: 0.8125rem;
            }

            .tabs-content {
                padding: 1rem;
            }

            .field-value {
                font-size: 0.875rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="detail-container">
        {{-- Header --}}
        <div class="detail-header">
            <div class="detail-header-content">
                <div class="detail-info">
                    <h2>{{ $umkm->nama_usaha }}</h2>
                    <div class="detail-meta">
                        <span><b>Email:</b> {{ $umkm->email }}</span>
                        <span><b>Phone:</b> {{ $umkm->nomor_hp }}</span>
                        <span><b>Daftar:</b> {{ $umkm->created_at->format('d M Y') }}</span>
                    </div>
                </div>
                <div class="detail-actions">
                    <a href="{{ route('admin.umkm.index') }}" class="btn btn-back">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M19 12H5M12 19l-7-7 7-7" />
                        </svg>
                        Kembali
                    </a>

                    @if($umkm->status === 'pending')
                        <button onclick="showApproveModal()" class="btn btn-approve">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                            Setujui
                        </button>
                        <button onclick="showRejectModal()" class="btn btn-reject">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <line x1="18" y1="6" x2="6" y2="18" />
                                <line x1="6" y1="6" x2="18" y2="18" />
                            </svg>
                            Tolak
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Status Card --}}
        <div class="status-card">
            <div class="card-title">Status Pendaftaran</div>
            <span class="status-badge-large {{ $umkm->status }}">
                @if($umkm->status === 'pending')
                    ⏳ Menunggu Verifikasi
                @elseif($umkm->status === 'approved')
                    ✓ Disetujui
                @else
                    ✗ Ditolak
                @endif
            </span>

            @if($umkm->status === 'approved' && $umkm->verified_at)
                <div class="info-box success">
                    <strong>Tanggal Verifikasi:</strong> {{ \Carbon\Carbon::parse($umkm->verified_at)->format('d M Y H:i') }} WIB
                </div>
            @endif

            @if($umkm->status === 'rejected' && $umkm->rejection_reason)
                <div class="info-box danger">
                    <strong>Alasan Penolakan:</strong><br>
                    {{ $umkm->rejection_reason }}
                </div>
            @endif
        </div>

        {{-- Tabs Container --}}
        <div class="tabs-container">
            <div class="tabs-header">
                <button class="tab-button active" onclick="switchTab('usaha')">
                    Data Usaha
                </button>
                <button class="tab-button" onclick="switchTab('pribadi')">
                    Data Pribadi Pemilik
                </button>
                <button class="tab-button" onclick="switchTab('tambahan')">
                    Informasi Tambahan
                </button>
            </div>

            <div class="tabs-content">
                {{-- Tab Data Usaha --}}
                <div class="tab-panel active" id="tab-usaha">
                    <div class="detail-grid">
                        <div class="field-group">
                            <div class="field-label">Nama Usaha / Brand</div>
                            <div class="field-value">{{ $umkm->nama_usaha }}</div>
                        </div>

                        <div class="field-group">
                            <div class="field-label">Bidang Usaha</div>
                            <div class="field-value">
                                <span class="badge badge-info">{{ $umkm->bidang_usaha }}</span>
                            </div>
                        </div>

                        <div class="field-group">
                            <div class="field-label">Status Legalitas</div>
                            <div class="field-value">{{ $umkm->status_legalitas }}</div>
                        </div>

                        <div class="field-group">
                            <div class="field-label">Jenis Legalitas</div>
                            <div class="field-value">{{ $umkm->jenis_legalitas ?? '-' }}</div>
                        </div>

                        <div class="field-group">
                            <div class="field-label">Tahun Berdiri</div>
                            <div class="field-value">{{ $umkm->tahun_berdiri }}</div>
                        </div>
                    </div>
                </div>

                {{-- Tab Data Pribadi --}}
                <div class="tab-panel" id="tab-pribadi">
                    <div class="detail-grid">
                        <div class="field-group">
                            <div class="field-label">Nama Lengkap</div>
                            <div class="field-value">{{ $umkm->nama_lengkap }}</div>
                        </div>

                        <div class="field-group">
                            <div class="field-label">Jenis Kelamin</div>
                            <div class="field-value">{{ $umkm->jenis_kelamin }}</div>
                        </div>

                        <div class="field-group">
                            <div class="field-label">Tanggal Lahir</div>
                            <div class="field-value">
                                {{ \Carbon\Carbon::parse($umkm->tanggal_lahir)->format('d F Y') }}
                            </div>
                        </div>

                        <div class="field-group">
                            <div class="field-label">No HP / WhatsApp</div>
                            <div class="field-value">
                                <a href="https://wa.me/{{ $umkm->nomor_hp }}" target="_blank"
                                    class="link-primary">{{ $umkm->nomor_hp }}</a>
                            </div>
                        </div>

                        <div class="field-group">
                            <div class="field-label">Email</div>
                            <div class="field-value">
                                <a href="mailto:{{ $umkm->email }}" class="link-primary">{{ $umkm->email }}</a>
                            </div>
                        </div>

                        <div class="field-group">
                            <div class="field-label">Alamat Domisili</div>
                            <div class="field-value">{{ $umkm->alamat_domisili }}</div>
                        </div>
                    </div>
                </div>

                {{-- Tab Informasi Tambahan --}}
                <div class="tab-panel" id="tab-tambahan">
                    <div class="detail-grid">
                        <div class="field-group">
                            <div class="field-label">Platform Digital</div>
                            <div class="field-value">{{ $umkm->platform_digital }}</div>
                        </div>

                        @if($umkm->platform_digital === 'Ya' && $umkm->platform)
                        <div class="field-group">
                            <div class="field-label">Platform yang Digunakan</div>
                            <div class="field-value">
                                <div class="badges-container">
                                    @foreach($umkm->platform as $platform)
                                    <span class="badge badge-primary">{{ $platform }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="field-group">
                            <div class="field-label">Pendapatan per Bulan</div>
                            <div class="field-value">
                                <span class="badge badge-success">{{ $umkm->pendapatan }}</span>
                            </div>
                        </div>

                        <div class="field-group">
                            <div class="field-label">Sudah Menerima Pembiayaan</div>
                            <div class="field-value">{{ $umkm->pembiayaan ?? 'Tidak' }}</div>
                        </div>

                        @if($umkm->pembiayaan === 'Ya')
                        <div class="field-group">
                            <div class="field-label">Sumber Pembiayaan</div>
                            <div class="field-value">{{ $umkm->sumber_pembiayaan ?? '-' }}</div>
                        </div>
                        @endif

                        <div class="field-group">
                            <div class="field-label">Tujuan Program</div>
                            <div class="field-value">{{ $umkm->tujuan }}</div>
                        </div>

                        <div class="field-group">
                            <div class="field-label">Pelatihan yang Dibutuhkan</div>
                            <div class="field-value">{{ $umkm->pelatihan }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Approve Modal --}}
    <div class="modal" id="approveModal">
        <div class="modal-content">
            <h3 class="modal-title">Konfirmasi Persetujuan</h3>
            <p>Apakah Anda yakin ingin menyetujui pendaftaran UMKM <strong>{{ $umkm->nama_usaha }}</strong>?</p>
            <form action="{{ route('admin.umkm.approve', $umkm->id) }}" method="POST">
                @csrf
                <div class="modal-actions">
                    <button type="button" class="btn btn-back" onclick="closeModal('approveModal')">Batal</button>
                    <button type="submit" class="btn btn-approve">Ya, Setujui</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Reject Modal --}}
    <div class="modal" id="rejectModal">
        <div class="modal-content">
            <h3 class="modal-title">Tolak Pendaftaran UMKM</h3>
            <form action="{{ route('admin.umkm.reject', $umkm->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Alasan Penolakan <span style="color: #ef4444;">*</span></label>
                    <textarea name="rejection_reason" class="form-control" rows="4"
                        placeholder="Jelaskan alasan penolakan..." required></textarea>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-back" onclick="closeModal('rejectModal')">Batal</button>
                    <button type="submit" class="btn btn-reject">Tolak</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function switchTab(tabName) {
            // Remove active class from all tabs and panels
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelectorAll('.tab-panel').forEach(panel => {
                panel.classList.remove('active');
            });

            // Add active class to clicked tab and corresponding panel
            event.target.classList.add('active');
            document.getElementById('tab-' + tabName).classList.add('active');
        }

        function showApproveModal() {
            document.getElementById('approveModal').classList.add('active');
        }

        function showRejectModal() {
            document.getElementById('rejectModal').classList.add('active');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }

        // Close modal on outside click
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function (e) {
                if (e.target === this) {
                    this.classList.remove('active');
                }
            });
        });
    </script>
@endpush