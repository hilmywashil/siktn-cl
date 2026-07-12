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

        .status-badge-large.pending,
        .status-badge-large.pending_verification,
        .status-badge-large.pending_profile {
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
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1.25rem;
            margin-bottom: 0;
            transition: all 0.2s;
        }
        
        .field-group:hover {
            background-color: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-color: #cbd5e1;
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
            word-break: break-word;
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
        <div class="alert alert-success" style="margin-bottom: 1.5rem; padding: 1rem; background-color: #d1fae5; color: #059669; border-radius: 4px;">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="detail-header">
        <div class="detail-header-content">
            <div class="detail-info">
                <h2>{{ $anggota->nama_lengkap ?? $anggota->username }}</h2>
                <div class="detail-meta">
                    <span><b>Username:</b> {{ $anggota->username }}</span>
                    <span><b>Email:</b> {{ $anggota->email ?? '-' }}</span>
                    <span><b>Domisili:</b> {{ $anggota->domisili ?? '-' }}</span>
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

                {{-- Tombol Ganti Password --}}
                <button onclick="showPasswordModal()" class="btn btn-password">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                    </svg>
                    Ganti Password
                </button>

                @if($anggota->status === 'pending_verification' || $anggota->status === 'pending')
                    <button onclick="showApproveModal()" class="btn btn-approve">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                        Verifikasi & Terima
                    </button>
                    <button onclick="showRejectModal()" class="btn btn-reject">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <line x1="18" y1="6" x2="6" y2="18" />
                            <line x1="6" y1="6" x2="18" y2="18" />
                        </svg>
                        Tolak Data
                    </button>
                @endif
            </div>
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
            @if($anggota->status === 'pending_profile')
                📝 Belum Melengkapi Profil
            @elseif($anggota->status === 'pending_verification' || $anggota->status === 'pending')
                ⏳ Menunggu Verifikasi
            @elseif($anggota->status === 'approved')
                ✓ Disetujui
            @else
                ✗ Ditolak
            @endif
        </span>

        @if(!empty($anggota->updated_fields) && is_array($anggota->updated_fields) && ($anggota->status === 'pending_verification' || $anggota->status === 'pending'))
            <div class="info-box" style="background: #eff6ff; color: #1e3a8a; border: 1px solid #bfdbfe; margin-top: 1rem;">
                <strong><i class="fa fa-info-circle" style="margin-right: 0.5rem;"></i>Data yang diperbarui:</strong>
                <ul style="margin-top: 0.5rem; margin-bottom: 0; padding-left: 1.5rem; font-size: 0.875rem;">
                    @foreach($anggota->updated_fields as $field)
                        <li>{{ ucwords(str_replace('_', ' ', $field)) }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

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
            <button class="tab-button active" onclick="switchTab('pribadi')">
                Biodata Pribadi
            </button>
            <button class="tab-button" onclick="switchTab('organisasi')">
                Organisasi & Pekerjaan
            </button>
            <button class="tab-button" onclick="switchTab('kontak')">
                Kontak & Sosmed
            </button>
        </div>

        <div class="tabs-content">
            {{-- Tab Biodata Pribadi --}}
            <div class="tab-panel active" id="tab-pribadi">
                <div class="detail-grid">
                    <div class="field-group" style="grid-column: 1 / -1; margin-bottom: 2rem;">
                        <div class="field-label">Foto Diri</div>
                        @if($anggota->foto_diri)
                            <img src="{{ Storage::url($anggota->foto_diri) }}" alt="Foto Diri" style="width: 150px; height: 150px; object-fit: cover; border-radius: 8px; border: 1px solid #e5e7eb;">
                        @else
                            <div style="width: 150px; height: 150px; background: #f3f4f6; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #9ca3af; border: 1px solid #e5e7eb;">
                                Belum ada foto
                            </div>
                        @endif
                    </div>

                    <div class="field-group">
                        <div class="field-label">NIK</div>
                        <div class="field-value">{{ $anggota->nik ?? '-' }}</div>
                    </div>

                    <div class="field-group">
                        <div class="field-label">Nama Lengkap</div>
                        <div class="field-value">{{ $anggota->nama_lengkap ?? '-' }}</div>
                    </div>

                    <div class="field-group">
                        <div class="field-label">Tempat & Tanggal Lahir</div>
                        <div class="field-value">
                            {{ $anggota->tempat_lahir ?? '-' }}, 
                            {{ $anggota->tanggal_lahir ? $anggota->tanggal_lahir->format('d F Y') : '-' }}
                        </div>
                    </div>

                    <div class="field-group" style="grid-column: 1 / -1;">
                        <div class="field-label">Alamat Lengkap</div>
                        <div class="field-value">{{ $anggota->alamat_lengkap ?? '-' }}</div>
                    </div>
                </div>
            </div>

            {{-- Tab Organisasi --}}
            <div class="tab-panel" id="tab-organisasi">
                <div class="detail-grid">
                    <div class="field-group">
                        <div class="field-label">Jabatan</div>
                        <div class="field-value">{{ $anggota->jabatan ?? '-' }}</div>
                    </div>

                    <div class="field-group">
                        <div class="field-label">Pendidikan Terakhir</div>
                        <div class="field-value">{{ $anggota->pendidikan_terakhir ?? '-' }}</div>
                    </div>

                    <div class="field-group">
                        <div class="field-label">Pekerjaan</div>
                        <div class="field-value">{{ $anggota->pekerjaan ?? '-' }}</div>
                    </div>

                    <div class="field-group" style="grid-column: 1 / -1;">
                        <div class="field-label">Riwayat Organisasi</div>
                        <div class="field-value">{!! nl2br(e($anggota->riwayat_organisasi ?? '-')) !!}</div>
                    </div>

                    <div class="field-group" style="grid-column: 1 / -1;">
                        <div class="field-label">Kompetensi</div>
                        <div class="field-value">{!! nl2br(e($anggota->kompetensi ?? '-')) !!}</div>
                    </div>
                </div>
            </div>

            {{-- Tab Kontak --}}
            <div class="tab-panel" id="tab-kontak">
                <div class="detail-grid">
                    <div class="field-group">
                        <div class="field-label">Nomor HP / WhatsApp</div>
                        <div class="field-value">{{ $anggota->no_hp ?? '-' }}</div>
                    </div>

                    <div class="field-group">
                        <div class="field-label">Email</div>
                        <div class="field-value">{{ $anggota->email ?? '-' }}</div>
                    </div>

                    <div class="field-group">
                        <div class="field-label">Instagram</div>
                        <div class="field-value">{{ $anggota->instagram ?? '-' }}</div>
                    </div>

                    <div class="field-group">
                        <div class="field-label">TikTok</div>
                        <div class="field-value">{{ $anggota->tiktok ?? '-' }}</div>
                    </div>
                    
                    <div class="field-group">
                        <div class="field-label">Twitter / X</div>
                        <div class="field-value">{{ $anggota->twitter ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    {{-- Approve Modal --}}
    <div class="modal" id="approveModal">
        <div class="modal-content">
            <h3 class="modal-title">Konfirmasi Persetujuan & Penempatan Jabatan</h3>
            <p>Apakah Anda yakin ingin menyetujui pendaftaran <strong>{{ $anggota->nama_lengkap ?? $anggota->username }}</strong>?</p>
            <form action="{{ route('admin.anggota.approve', $anggota) }}" method="POST">
                @csrf
                <input type="hidden" name="jabatan_nama" value="{{ $anggota->jabatan }}">
                
                {{-- Logic is fully automated, no inputs needed --}}
                <div style="margin: 1.5rem 0; padding: 1rem; background-color: #f8fafc; border-left: 4px solid #10b981; border-radius: 4px;">
                    <p style="margin: 0; font-size: 0.85rem; color: #475569;">
                        <strong>Info:</strong> Posisi jabatan dan urutan struktur (Bagan Organisasi) akan ditentukan secara otomatis berdasarkan profil jabatan yang telah diisi.
                    </p>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn btn-back" onclick="closeModal('approveModal')">Batal</button>
                    <button type="submit" class="btn btn-approve">Ya, Setujui & Simpan</button>
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

        // Logika Atasan dihapus (selalu tampil) karena 1 Anggota = 1 Node yang butuh atasan spesifik

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