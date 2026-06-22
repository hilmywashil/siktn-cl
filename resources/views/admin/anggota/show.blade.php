@extends('admin.layouts.admin-layout')

@section('title', 'Detail Anggota')
@section('page-title', 'Detail Pendaftar Anggota')

@php
    $activeMenu = 'anggota';
    $admin = auth()->guard('admin')->user();
@endphp

@push('styles')
    <style>
        @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
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
            border-radius: 6px;
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
            border-radius: 4px;
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
            transform: translateY(-1px);
        }

        .btn svg {
            width: 18px;
            height: 18px;
            stroke-width: 2;
        }

        .btn-approve {
            background: #10B981;
            color: white;
        }

        .btn-approve:hover {
            background: #059669;
        }

        .btn-reject {
            background: #D60B1C;
            color: white;
        }

        .btn-reject:hover {
            background: #a80816;
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
            border-radius: 6px;
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
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.875rem;
            border: 1px solid transparent;
        }

        .status-badge-large.pending {
            background: rgba(197, 146, 23, 0.1);
            color: #C59217;
            border-color: rgba(197, 146, 23, 0.2);
        }

        .status-badge-large.approved {
            background: rgba(16, 185, 129, 0.1);
            color: #10B981;
            border-color: rgba(16, 185, 129, 0.2);
        }

        .status-badge-large.rejected {
            background: rgba(214, 11, 28, 0.1);
            color: #D60B1C;
            border-color: rgba(214, 11, 28, 0.2);
        }

        /* Tabs Styling */
        .tabs-container {
            background: white;
            border-radius: 6px;
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

        .doc-box {
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
            background: #f8fafc;
            margin-bottom: 1rem;
        }

        .doc-box label {
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            display: block;
            padding: 12px 14px;
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
        }

        .doc-box img {
            width: 100%;
            height: 260px;
            object-fit: cover;
            display: block;
        }

        .pdf-preview {
            width: 100%;
            height: 400px;
            border: none;
            display: block;
        }

        .pdf-container {
            position: relative;
            background: #525659;
        }

        .pdf-actions {
            padding: 14px;
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .btn-download,
        .btn-view {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            color: #fff;
            border-radius: 4px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: background 0.2s;
        }

        .btn-download {
            background: #022648;
        }

        .btn-download:hover {
            background: #1c2780;
        }

        .btn-view {
            background: #64748b;
        }

        .btn-view:hover {
            background: #475569;
        }

        .produk-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 8px;
        }

        .produk-item {
            padding: 6px 12px;
            background: rgba(197, 146, 23, 0.1);
            color: #C59217;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 500;
            border: 1px solid rgba(197, 146, 23, 0.2);
        }

        .info-box {
            margin-top: 1rem;
            padding: 1rem;
            border-radius: 4px;
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
            border-radius: 6px;
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
            border-radius: 4px;
            font-size: 0.875rem;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: #022648;
            box-shadow: 0 0 0 3px rgba(11, 19, 84, 0.1);
        }

        .modal-actions {
            display: flex;
            gap: 0.75rem;
            justify-content: flex-end;
            margin-top: 1.5rem;
        }

        /* Responsive Design */
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
        .modal-lg {
    max-width: 800px;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.25rem;
}

.btn-edit {
    background: #C59217;
    color: white;
}

.btn-edit:hover {
    background: #a3750d;
    box-shadow: 0 4px 12px rgba(197, 146, 23, 0.3);
}

.btn-password {
    background: #022648;
    color: white;
}

.btn-password:hover {
    background: #1c2780;
    box-shadow: 0 4px 12px rgba(11, 19, 84, 0.3);
}
    </style>
@endpush

@section('content')
    <div class="detail-container">
    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 1.5rem;">
            {{ session('success') }}
        </div>
    @endif
        {{-- Header --}}
       {{-- Header dengan tombol Edit --}}
    <div class="detail-header">
        <div class="detail-header-content">
            <div class="detail-info">
                <h2>{{ $anggota->nama_pimpinan }}</h2>
                <div class="detail-meta">
                    <span><b>Perusahaan:</b> {{ $anggota->nama_perusahaan }}</span>
                    <span><b>Email:</b> {{ $anggota->email_website_perusahaan }}</span>
                    <span><b>Daftar:</b> {{ $anggota->created_at->format('d M Y') }}</span>
                </div>
            </div>
            <div class="detail-actions">
                <a href="{{ route('admin.anggota.index') }}" class="btn btn-back">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M19 12H5M12 19l-7-7 7-7" />
                    </svg>
                    Kembali
                </a>

                {{-- Tombol Edit Data --}}
                <button onclick="showEditModal()" class="btn btn-edit">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                    </svg>
                    Edit Data
                </button>

                {{-- Tombol Ganti Password --}}
                <button onclick="showPasswordModal()" class="btn btn-password">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                    </svg>
                    Ganti Password
                </button>

                @if($anggota->status === 'pending')
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
    {{-- Modal Edit Data Anggota --}}
    <div class="modal" id="editModal">
        <div class="modal-content modal-lg">
            <h3 class="modal-title">Edit Data Anggota</h3>
            <form action="{{ route('admin.anggota.update', $anggota) }}" method="POST">
                @csrf
                @method('PUT')
                
                <h4 style="margin: 1.5rem 0 1rem; font-size: 1rem; font-weight: 600; color: #0a2540;">Data Perusahaan</h4>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Nama Perusahaan *</label>
                        <input type="text" name="nama_perusahaan" class="form-control" 
                               value="{{ old('nama_perusahaan', $anggota->nama_perusahaan) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Trade Mark *</label>
                        <input type="text" name="trade_mark" class="form-control" 
                               value="{{ old('trade_mark', $anggota->trade_mark) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Lahir Perusahaan *</label>
                        <input type="date" name="tanggal_lahir" class="form-control" 
                               value="{{ old('tanggal_lahir', $anggota->tanggal_lahir?->format('Y-m-d')) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email Perusahaan *</label>
                        <input type="email" name="email_website_perusahaan" class="form-control" 
                               value="{{ old('email_website_perusahaan', $anggota->email_website_perusahaan) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Telepon/WA Perusahaan *</label>
                        <input type="text" name="telepon_wa_perusahaan" class="form-control" 
                               value="{{ old('telepon_wa_perusahaan', $anggota->telepon_wa_perusahaan) }}" required>
                    </div>
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label class="form-label">Alamat Kantor *</label>
                        <textarea name="alamat_kantor" class="form-control" rows="2" required>{{ old('alamat_kantor', $anggota->alamat_kantor) }}</textarea>
                    </div>
                </div>

                <h4 style="margin: 1.5rem 0 1rem; font-size: 1rem; font-weight: 600; color: #0a2540;">Data Pimpinan</h4>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Nama Pimpinan *</label>
                        <input type="text" name="nama_pimpinan" class="form-control" 
                               value="{{ old('nama_pimpinan', $anggota->nama_pimpinan) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email Pimpinan *</label>
                        <input type="email" name="email_pimpinan" class="form-control" 
                               value="{{ old('email_pimpinan', $anggota->email_pimpinan) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Telepon/WA Pimpinan *</label>
                        <input type="text" name="telepon_wa_pimpinan" class="form-control" 
                               value="{{ old('telepon_wa_pimpinan', $anggota->telepon_wa_pimpinan) }}" required>
                    </div>
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label class="form-label">Alamat Pimpinan *</label>
                        <textarea name="alamat_pimpinan" class="form-control" rows="2" required>{{ old('alamat_pimpinan', $anggota->alamat_pimpinan) }}</textarea>
                    </div>
                </div>

                <h4 style="margin: 1.5rem 0 1rem; font-size: 1rem; font-weight: 600; color: #0a2540;">Legalitas</h4>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Akte Notaris *</label>
                        <input type="text" name="akte_notaris" class="form-control" 
                               value="{{ old('akte_notaris', $anggota->akte_notaris) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">NIB / TDUP *</label>
                        <input type="text" name="nomor_induk_berusaha_tdup" class="form-control" 
                               value="{{ old('nomor_induk_berusaha_tdup', $anggota->nomor_induk_berusaha_tdup) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">NPWP Perusahaan *</label>
                        <input type="text" name="npwp_perusahaan" class="form-control" 
                               value="{{ old('npwp_perusahaan', $anggota->npwp_perusahaan) }}" required>
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn btn-back" onclick="closeModal('editModal')">Batal</button>
                    <button type="submit" class="btn btn-edit">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Ganti Password --}}
    <div class="modal" id="passwordModal">
        <div class="modal-content">
            <h3 class="modal-title">Ganti Password Anggota</h3>
            <form action="{{ route('admin.anggota.update-password', $anggota) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label class="form-label">Password Baru *</label>
                    <input type="password" name="new_password" class="form-control" 
                           placeholder="Minimal 8 karakter" required minlength="8">
                </div>
                <div class="form-group">
                    <label class="form-label">Konfirmasi Password Baru *</label>
                    <input type="password" name="new_password_confirmation" class="form-control" 
                           placeholder="Ketik ulang password baru" required>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-back" onclick="closeModal('passwordModal')">Batal</button>
                    <button type="submit" class="btn btn-password">Ganti Password</button>
                </div>
            </form>
        </div>
    </div>

        {{-- Status Card --}}
        <div class="status-card">
            <div class="card-title">Status Pendaftaran</div>
            <span class="status-badge-large {{ $anggota->status }}">
                @if($anggota->status === 'pending')
                    ⏳ Menunggu Verifikasi
                @elseif($anggota->status === 'approved')
                    ✓ Disetujui
                @else
                    ✗ Ditolak
                @endif
            </span>

            @if($anggota->status === 'approved')
                <div class="info-box success">
                    <strong>Disetujui pada:</strong> {{ $anggota->approved_at ? $anggota->approved_at->format('d M Y H:i') : '-' }}
                </div>
            @endif

            @if($anggota->status === 'rejected' && $anggota->rejection_reason)
                <div class="info-box danger">
                    <strong>Alasan Penolakan:</strong><br>
                    {{ $anggota->rejection_reason }}
                </div>
            @endif
        </div>

        {{-- Tabs Container --}}
        <div class="tabs-container">
            <div class="tabs-header">
                <button class="tab-button active" onclick="switchTab('pimpinan')">
                    Data Pimpinan
                </button>
                <button class="tab-button" onclick="switchTab('perusahaan')">
                    Data Perusahaan
                </button>
                <button class="tab-button" onclick="switchTab('legalitas')">
                    Legalitas
                </button>
                <button class="tab-button" onclick="switchTab('produk')">
                    Produk Usaha
                </button>
                <button class="tab-button" onclick="switchTab('dokumen')">
                    Dokumen
                </button>
            </div>

            <div class="tabs-content">
                {{-- Tab Data Pimpinan --}}
                <div class="tab-panel active" id="tab-pimpinan">
                    <div class="detail-grid">
                        <div class="field-group">
                            <div class="field-label">Nama Pimpinan</div>
                            <div class="field-value">{{ $anggota->nama_pimpinan }}</div>
                        </div>

                        <div class="field-group">
                            <div class="field-label">Email Pimpinan</div>
                            <div class="field-value">{{ $anggota->email_pimpinan }}</div>
                        </div>

                        <div class="field-group">
                            <div class="field-label">Telepon / WhatsApp</div>
                            <div class="field-value">{{ $anggota->telepon_wa_pimpinan }}</div>
                        </div>

                        <div class="field-group">
                            <div class="field-label">Alamat Pimpinan</div>
                            <div class="field-value">{{ $anggota->alamat_pimpinan }}</div>
                        </div>
                    </div>
                </div>

                {{-- Tab Data Perusahaan --}}
                <div class="tab-panel" id="tab-perusahaan">
                    <div class="detail-grid">
                        <div class="field-group">
                            <div class="field-label">Nama Perusahaan</div>
                            <div class="field-value">{{ $anggota->nama_perusahaan }}</div>
                        </div>

                        <div class="field-group">
                            <div class="field-label">Trade Mark</div>
                            <div class="field-value">{{ $anggota->trade_mark }}</div>
                        </div>

                        <div class="field-group">
                            <div class="field-label">Tanggal Lahir Perusahaan</div>
                            <div class="field-value">{{ $anggota->tanggal_lahir ? $anggota->tanggal_lahir->format('d F Y') : '-' }}</div>
                        </div>

                        <div class="field-group">
                            <div class="field-label">Email Perusahaan</div>
                            <div class="field-value">{{ $anggota->email_website_perusahaan }}</div>
                        </div>

                        <div class="field-group">
                            <div class="field-label">Telepon Perusahaan</div>
                            <div class="field-value">{{ $anggota->telepon_wa_perusahaan }}</div>
                        </div>

                        <div class="field-group">
                            <div class="field-label">Alamat Kantor</div>
                            <div class="field-value">{{ $anggota->alamat_kantor }}</div>
                        </div>
                    </div>
                </div>

                {{-- Tab Legalitas --}}
                <div class="tab-panel" id="tab-legalitas">
                    <div class="detail-grid">
                        <div class="field-group">
                            <div class="field-label">Akte Notaris</div>
                            <div class="field-value">{{ $anggota->akte_notaris }}</div>
                        </div>

                        <div class="field-group">
                            <div class="field-label">NIB / TDUP</div>
                            <div class="field-value">{{ $anggota->nomor_induk_berusaha_tdup }}</div>
                        </div>

                        <div class="field-group">
                            <div class="field-label">NPWP Perusahaan</div>
                            <div class="field-value">{{ $anggota->npwp_perusahaan }}</div>
                        </div>
                    </div>
                </div>

                {{-- Tab Produk Usaha --}}
                <div class="tab-panel" id="tab-produk">
                    @if($anggota->produk_usaha_yang_akan_dijual && count($anggota->produk_usaha_yang_akan_dijual) > 0)
                        <div class="produk-list">
                            @foreach($anggota->produk_usaha_yang_akan_dijual as $produk)
                                <span class="produk-item">{{ $produk }}</span>
                            @endforeach
                        </div>
                    @else
                        <p style="color: #64748b;">Belum ada produk yang dipilih</p>
                    @endif
                </div>

                {{-- Tab Dokumen --}}
                <div class="tab-panel" id="tab-dokumen">
                    <div class="detail-grid">
                        {{-- Surat Permohonan --}}
                        <div class="doc-box">
                            <label>Surat Permohonan</label>
                            <div class="pdf-container">
                                <iframe src="{{ Storage::url($anggota->surat_permohonan) }}" class="pdf-preview"></iframe>
                            </div>
                            <div class="pdf-actions">
                                <a href="{{ Storage::url($anggota->surat_permohonan) }}" target="_blank" class="btn-view">
                                    <i class="fa fa-eye"></i> Lihat PDF
                                </a>
                                <a href="{{ Storage::url($anggota->surat_permohonan) }}" download class="btn-download">
                                    <i class="fa fa-download"></i> Download
                                </a>
                            </div>
                        </div>

                        {{-- Akte Pendirian --}}
                        <div class="doc-box">
                            <label>Akte Pendirian Perusahaan</label>
                            <div class="pdf-container">
                                <iframe src="{{ Storage::url($anggota->akte_pendirian_perusahaan) }}" class="pdf-preview"></iframe>
                            </div>
                            <div class="pdf-actions">
                                <a href="{{ Storage::url($anggota->akte_pendirian_perusahaan) }}" target="_blank" class="btn-view">
                                    <i class="fa fa-eye"></i> Lihat PDF
                                </a>
                                <a href="{{ Storage::url($anggota->akte_pendirian_perusahaan) }}" download class="btn-download">
                                    <i class="fa fa-download"></i> Download
                                </a>
                            </div>
                        </div>

                        {{-- NIB/TDUP --}}
                        <div class="doc-box">
                            <label>NIB / TDUP</label>
                            <div class="pdf-container">
                                <iframe src="{{ Storage::url($anggota->nib_atau_tdup) }}" class="pdf-preview"></iframe>
                            </div>
                            <div class="pdf-actions">
                                <a href="{{ Storage::url($anggota->nib_atau_tdup) }}" target="_blank" class="btn-view">
                                    <i class="fa fa-eye"></i> Lihat PDF
                                </a>
                                <a href="{{ Storage::url($anggota->nib_atau_tdup) }}" download class="btn-download">
                                    <i class="fa fa-download"></i> Download
                                </a>
                            </div>
                        </div>

                        {{-- KTP Pimpinan --}}
                        <div class="doc-box">
                            <label>KTP Pimpinan</label>
                            <img src="{{ Storage::url($anggota->ktp_pimpinan) }}" alt="KTP">
                            <div class="pdf-actions">
                                <a href="{{ Storage::url($anggota->ktp_pimpinan) }}" target="_blank" class="btn-view">
                                    <i class="fa fa-eye"></i> Lihat Gambar
                                </a>
                                <a href="{{ Storage::url($anggota->ktp_pimpinan) }}" download class="btn-download">
                                    <i class="fa fa-download"></i> Download
                                </a>
                            </div>
                        </div>

                        {{-- NPWP Perusahaan --}}
                        <div class="doc-box">
                            <label>NPWP Perusahaan</label>
                            <div class="pdf-container">
                                <iframe src="{{ Storage::url($anggota->npwp_perusahaan_file) }}" class="pdf-preview"></iframe>
                            </div>
                            <div class="pdf-actions">
                                <a href="{{ Storage::url($anggota->npwp_perusahaan_file) }}" target="_blank" class="btn-view">
                                    <i class="fa fa-eye"></i> Lihat PDF
                                </a>
                                <a href="{{ Storage::url($anggota->npwp_perusahaan_file) }}" download class="btn-download">
                                    <i class="fa fa-download"></i> Download
                                </a>
                            </div>
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
            <p>Apakah Anda yakin ingin menyetujui pendaftaran <strong>{{ $anggota->nama_pimpinan }}</strong>?</p>
            <form action="{{ route('admin.anggota.approve', $anggota) }}" method="POST">
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
            <h3 class="modal-title">Tolak Pendaftaran</h3>
            <form action="{{ route('admin.anggota.reject', $anggota) }}" method="POST">
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
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelectorAll('.tab-panel').forEach(panel => {
                panel.classList.remove('active');
            });

            event.target.classList.add('active');
            document.getElementById('tab-' + tabName).classList.add('active');
        }

        function showEditModal() {
            document.getElementById('editModal').classList.add('active');
        }

        function showPasswordModal() {
            document.getElementById('passwordModal').classList.add('active');
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

        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function (e) {
                if (e.target === this) {
                    this.classList.remove('active');
                }
            });
        });

        setTimeout(() => {
            const alert = document.querySelector('.alert-success');
            if (alert) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        }, 5000);
    </script>
@endpush