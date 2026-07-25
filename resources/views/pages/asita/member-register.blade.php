@extends('layouts.app')

@section('title', 'Daftar Akun Member - Karang Taruna')

@push('styles')
    <style>
        /* ==============================================
           MEMBER REGISTER PAGE - SIKTN
           ============================================== */

        :root {
            --primary-blue: #022648;
            --secondary-blue: #2A348D;
            --accent-yellow: #FFE701;
            --text-dark: #04293B;
            --text-grey: #6b7280;
            --bg-light: #F8F9FA;
        }

        /* ── Title Section ── */
        .title-section {
            padding: 50px 20px 30px;
            text-align: center;
            max-width: 900px;
            margin: 0 auto;
        }

        .title-section h1 {
            font-size: 36px;
            font-weight: 800;
            color: var(--primary-blue);
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
            line-height: 1.2;
        }

        .title-section h2 {
            font-size: 16px;
            font-weight: 500;
            color: var(--text-grey);
            margin: 8px 0 0;
        }

        /* ── Form Container ── */
        .form-container {
            max-width: 720px;
            margin: 0 auto 60px;
            padding: 0 20px;
        }

        /* ── Card ── */
        .form-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(2, 38, 72, 0.08);
            border: 1px solid rgba(2, 38, 72, 0.06);
            overflow: hidden;
        }

        .form-card-header {
            background: var(--primary-blue);
            padding: 20px 28px;
            display: flex;
            align-items: center;
            gap: 14px;
            border-bottom: 3px solid var(--accent-yellow);
        }

        .form-card-header-icon {
            width: 44px;
            height: 44px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .form-card-header-icon i {
            color: #fff;
            font-size: 18px;
        }

        .form-card-header-text h3 {
            color: #fff;
            font-size: 16px;
            font-weight: 700;
            margin: 0 0 2px;
        }

        .form-card-header-text p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 12px;
            margin: 0;
        }

        .form-card-body {
            padding: 24px 28px;
        }

        /* ── Info Box ── */
        .info-box {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            background: linear-gradient(135deg, rgba(42, 52, 141, 0.04) 0%, rgba(2, 38, 72, 0.02) 100%);
            border: 1px solid rgba(42, 52, 141, 0.1);
            border-radius: 8px;
            padding: 14px 16px;
            margin-bottom: 24px;
        }

        .info-box-icon {
            width: 32px;
            height: 32px;
            background: var(--secondary-blue);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .info-box-icon i {
            color: #fff;
            font-size: 14px;
        }

        .info-box-text {
            font-size: 13px;
            color: var(--text-dark);
            line-height: 1.55;
            font-weight: 500;
        }

        .info-box-text strong {
            color: var(--secondary-blue);
        }

        /* ── Form Section Title ── */
        .form-section-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 18px;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(42, 52, 141, 0.1);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-section-title i {
            color: var(--secondary-blue);
            font-size: 14px;
        }

        /* ── Form Grid ── */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px 24px;
            margin-bottom: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        .form-group label {
            font-size: 12px;
            font-weight: 700;
            color: var(--text-dark);
            letter-spacing: 0.3px;
        }

        .form-group label span {
            color: #ef4444;
            margin-left: 2px;
        }

        /* ── Inputs ── */
        .form-control {
            width: 100%;
            padding: 11px 14px;
            background-color: var(--bg-light);
            border: 1.5px solid #E5E7EB;
            border-radius: 8px;
            font-family: 'Google Sans', 'Outfit', sans-serif;
            font-size: 13px;
            color: #333;
            transition: all 0.2s;
            box-sizing: border-box;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--secondary-blue);
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(42, 52, 141, 0.08);
        }

        .form-control::placeholder {
            color: #9ca3af;
        }

        .help-text {
            font-size: 11px;
            color: var(--text-grey);
            font-weight: 500;
            line-height: 1.4;
        }

        /* ── Select2 Custom Styles ── */
        @keyframes select2DropdownFadeIn {
            from { opacity: 0; transform: translateY(-6px) scale(0.98); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        .select2-container--default .select2-selection--single {
            height: 40px;
            padding: 0 12px;
            font-size: 13px;
            font-weight: 500;
            color: #333;
            background-color: var(--bg-light);
            border: 1.5px solid #E5E7EB;
            border-radius: 8px;
            display: flex;
            align-items: center;
            transition: all 0.2s;
        }

        .select2-container--default .select2-selection--single:hover {
            border-color: var(--secondary-blue);
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding-left: 0;
            color: #333;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px;
            right: 10px;
        }

        .select2-container--default .select2-selection--single:focus,
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: var(--secondary-blue);
            box-shadow: 0 0 0 3px rgba(42, 52, 141, 0.08);
        }

        .select2-dropdown {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 13px;
            z-index: 9999;
            box-shadow: 0 8px 20px rgba(2, 38, 72, 0.1);
            margin-top: 4px;
            overflow: hidden;
        }

        .select2-container--open .select2-dropdown {
            animation: select2DropdownFadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .select2-results__option--highlighted[aria-selected],
        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
            background-color: var(--secondary-blue) !important;
            color: #ffffff !important;
            font-weight: 600 !important;
        }

        .select2-container--default .select2-results__option[aria-selected="true"] {
            background-color: rgba(42, 52, 141, 0.08);
            color: var(--primary-blue);
        }

        .select2-search--dropdown {
            padding: 10px;
        }

        .select2-search--dropdown .select2-search__field {
            border: 1.5px solid #e5e7eb;
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 13px;
        }

        .select2-search--dropdown .select2-search__field:focus {
            outline: none;
            border-color: var(--secondary-blue);
        }

        /* ── Error Alert ── */
        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 20px;
        }

        .alert-error strong {
            color: #dc2626;
            font-size: 13px;
        }

        .alert-error ul {
            margin: 8px 0 0 18px;
            padding: 0;
        }

        .alert-error ul li {
            color: #b91c1c;
            font-size: 12px;
            margin: 3px 0;
        }

        /* ── Submit Button ── */
        .form-submit {
            display: flex;
            gap: 12px;
            align-items: center;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }

        .btn-submit {
            flex: 1;
            padding: 14px 20px;
            background: var(--accent-yellow);
            color: #000;
            border: none;
            border-radius: 8px;
            font-family: 'Google Sans', sans-serif;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(255, 231, 1, 0.3);
        }

        .btn-submit:disabled {
            background: #d1d5db;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .btn-submit i {
            font-size: 14px;
        }

        /* ── Back Link ── */
        .back-link {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: var(--text-grey);
        }

        .back-link a {
            color: var(--secondary-blue);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .back-link a:hover {
            color: var(--primary-blue);
            text-decoration: underline;
        }

        /* ==============================================
           RESPONSIVE
           ============================================== */

        @media (max-width: 768px) {
            .title-section {
                padding: 40px 16px 20px;
            }

            .title-section h1 {
                font-size: 28px;
            }

            .form-card-header {
                padding: 16px 20px;
            }

            .form-card-body {
                padding: 20px;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 14px;
            }

            .form-group.full-width {
                grid-column: span 1;
            }

            .form-container {
                margin-bottom: 40px;
            }
        }

        @media (max-width: 480px) {
            .title-section h1 {
                font-size: 24px;
            }

            .info-box {
                flex-direction: column;
                gap: 10px;
            }

            .form-submit {
                flex-direction: column;
            }

            .form-card-header {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
@endpush

@section('content')
    <div class="title-section" data-aos="fade-up">
        <h1>Daftar Akun Member</h1>
        <h2>Daftarkan Anggota Karang Taruna Baru</h2>
    </div>

    <div class="form-container" data-aos="fade-up" data-aos-delay="50">
        {{-- Error Alert --}}
        @if ($errors->any())
            <div class="alert-error">
                <strong>Terdapat kesalahan:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Card --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-header-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div class="form-card-header-text">
                    <h3>Form Pendaftaran Akun</h3>
                    <p>Isi data dengan lengkap dan benar</p>
                </div>
            </div>

            <div class="form-card-body">
                {{-- Info Box --}}
                <div class="info-box">
                    <div class="info-box-icon">
                        <i class="fas fa-shield-halved"></i>
                    </div>
                    <div class="info-box-text">
                        Setelah form ini dikirim, Anda akan langsung mendapatkan <strong>email</strong> dan <strong>password</strong> untuk masuk ke dashboard anggota.
                    </div>
                </div>

                <form action="{{ route('member-register.store') }}" method="POST" id="memberRegForm">
                    @csrf

                    <div class="form-section-title">
                        <i class="fas fa-id-card"></i>
                        Informasi Akun
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="username">Username <span>*</span></label>
                            <input type="text" name="username" id="username" class="form-control"
                                   value="{{ old('username') }}" required
                                   placeholder="Contoh: bukit_jabar">
                            <span class="help-text">Username unik untuk login ke portal SIKTN</span>
                        </div>

                        <div class="form-group">
                            <label for="nama_lengkap">Nama Lengkap <span>*</span></label>
                            <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control"
                                   value="{{ old('nama_lengkap') }}" required
                                   placeholder="Contoh: Ahmad Hidayat, S.T.">
                        </div>

                        <div class="form-group">
                            <label for="email">E-mail <span>*</span></label>
                            <input type="email" name="email" id="email" class="form-control"
                                   value="{{ old('email') }}" required
                                   placeholder="email@domain.com">
                        </div>

                        <div class="form-group">
                            <label for="password">Password <span>*</span></label>
                            <input type="password" name="password" id="password" class="form-control"
                                   required placeholder="Minimal 8 karakter">
                        </div>

                        <div class="form-group full-width">
                            <label for="domisili">Pilih Wilayah / Domisili Provinsi <span>*</span></label>
                            <select name="domisili" id="domisili" class="form-control select2-basic" required>
                                <option value="">-- Pilih Wilayah Provinsi --</option>
                                @php
                                    $provinces = ["Aceh","Sumatera Utara","Sumatera Barat","Riau","Jambi","Sumatera Selatan","Bengkulu","Lampung","Kepulauan Bangka Belitung","Kepulauan Riau","DKI Jakarta","Jawa Barat","Jawa Tengah","DI Yogyakarta","Jawa Timur","Banten","Bali","Nusa Tenggara Barat","Nusa Tenggara Timur","Kalimantan Barat","Kalimantan Tengah","Kalimantan Selatan","Kalimantan Timur","Kalimantan Utara","Sulawesi Utara","Sulawesi Tengah","Sulawesi Selatan","Sulawesi Tenggara","Gorontalo","Sulawesi Barat","Maluku","Maluku Utara","Papua Barat","Papua"];
                                @endphp
                                @foreach($provinces as $prov)
                                    <option value="{{ $prov }}" {{ old('domisili') == $prov ? 'selected' : '' }}>{{ $prov }}</option>
                                @endforeach
                            </select>
                            <span class="help-text">Verifikasi pendaftaran Anda akan diproses oleh Admin PPKT Wilayah ini</span>
                        </div>
                    </div>

                    <div class="form-submit">
                        <button type="submit" class="btn-submit" id="submitBtn">
                            <i class="fas fa-paper-plane"></i>
                            Daftarkan & Kirim Verifikasi
                        </button>
                    </div>
                </form>

                <div class="back-link">
                    Belum menjadi anggota? <a href="{{ route('join-us') }}">Daftar sebagai calon member →</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
{{-- jQuery (required for Select2) --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
{{-- Select2 --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize Select2 with search
        if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
            jQuery('#domisili').select2({
                placeholder: '-- Ketik untuk mencari provinsi --',
                allowClear: true,
                width: '100%',
                minimumInputLength: 0,
                language: {
                    noResults: function() {
                        return "Provinsi tidak ditemukan";
                    },
                    searching: function() {
                        return "Mencari...";
                    },
                    inputTooShort: function() {
                        return "Ketik minimal 1 karakter";
                    }
                }
            });
        }

        // Form submit handler
        const form = document.getElementById('memberRegForm');
        const submitBtn = document.getElementById('submitBtn');

        if (form && submitBtn) {
            form.addEventListener('submit', function () {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> MENGIRIM DATA...';
            });
        }
    });
</script>
@endpush
