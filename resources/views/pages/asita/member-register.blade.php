@extends('layouts.app')

@section('title', 'Daftar Akun Member - ASITA JABAR')

@push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap');

        .member-reg-section {
            padding: 60px 0 80px;
            background: #f0f2f8;
            font-family: 'Montserrat', Arial, sans-serif;
            min-height: 100vh;
        }

        .member-reg-section .container {
            max-width: 760px;
            margin: auto;
            padding: 0 20px;
        }

        /* FORM CARD */
        .reg-card {
            background: #ffffff;
            border-radius: 18px;
            box-shadow: 0 16px 48px rgba(42, 52, 141, 0.13);
            overflow: hidden;
        }

        .reg-card-header {
            background: linear-gradient(135deg, #2A348D 0%, #04293B 100%);
            padding: 42px 50px 36px;
            text-align: center;
            position: relative;
        }

        .reg-card-header::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 28px;
            background: #ffffff;
            border-radius: 28px 28px 0 0;
        }

        .badge-member {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.3);
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 7px 18px;
            border-radius: 30px;
            margin-bottom: 18px;
        }

        .badge-member span {
            width: 8px;
            height: 8px;
            background: #4ade80;
            border-radius: 50%;
            animation: pulse-dot 1.5s infinite;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        .reg-card-header h2 {
            font-size: 1.9rem;
            font-weight: 900;
            color: #ffffff;
            margin-bottom: 10px;
            letter-spacing: -0.5px;
        }

        .reg-card-header p {
            font-size: 0.92rem;
            color: rgba(255,255,255,0.78);
            font-weight: 500;
        }

        /* FORM BODY */
        .reg-form-body {
            padding: 40px 50px 50px;
        }

        /* INFO BOX */
        .info-box {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 10px;
            padding: 16px 20px;
            margin-bottom: 36px;
        }

        .info-box-icon {
            font-size: 1.4rem;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .info-box-text {
            font-size: 0.84rem;
            color: #1e40af;
            font-weight: 600;
            line-height: 1.7;
        }

        /* ERROR ALERT */
        .alert-danger {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            padding: 16px 20px;
            border-radius: 10px;
            margin-bottom: 28px;
            font-size: 0.88rem;
        }

        .alert-danger ul {
            margin: 8px 0 0 20px;
            padding: 0;
        }

        .alert-danger li {
            margin: 4px 0;
        }

        /* SECTION LABEL */
        .field-section-label {
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: #2A348D;
            margin-bottom: 18px;
            margin-top: 36px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .field-section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }

        .field-section-label:first-child {
            margin-top: 0;
        }

        /* FORM GROUP */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 7px;
            font-weight: 700;
            font-size: 0.83rem;
            color: #04293B;
            letter-spacing: 0.2px;
        }

        .form-group label .required {
            color: #dc2626;
            margin-left: 3px;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="date"],
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            border-radius: 9px;
            border: 2px solid #e5e7eb;
            font-size: 14px;
            font-family: 'Montserrat', Arial, sans-serif;
            color: #111827;
            background: #fafafa;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
            box-sizing: border-box;
        }

        .form-group textarea {
            min-height: 115px;
            resize: vertical;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #2A348D;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(42, 52, 141, 0.12);
        }

        .form-group .help-text {
            font-size: 11.5px;
            color: #9ca3af;
            margin-top: 5px;
            font-weight: 500;
        }

        /* TWO COLUMNS */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        /* SUBMIT BUTTON */
        .btn-submit {
            width: 100%;
            padding: 17px;
            font-size: 15px;
            font-weight: 800;
            background: linear-gradient(135deg, #2A348D 0%, #04293B 100%);
            border: none;
            color: #ffffff;
            border-radius: 10px;
            cursor: pointer;
            transition: transform 0.18s, box-shadow 0.18s;
            font-family: 'Montserrat', Arial, sans-serif;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-top: 14px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(42, 52, 141, 0.35);
        }

        .btn-submit:disabled {
            background: #9ca3af;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* BACK LINK */
        .back-link {
            text-align: center;
            margin-top: 28px;
            font-size: 0.85rem;
            color: #6b7280;
            font-weight: 600;
        }

        .back-link a {
            color: #2A348D;
            text-decoration: none;
            font-weight: 700;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

        /* RESPONSIVE */
        @media (max-width: 600px) {
            .reg-card-header {
                padding: 36px 28px 30px;
            }

            .reg-form-body {
                padding: 30px 24px 40px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <section class="hero-abouts">
        <div class="hero-about" data-aos="fade-up">
            <h1>Daftar Akun Member</h1>
            <p>Untuk Anggota ASITA yang Belum Terdaftar di Web</p>
        </div>
    </section>

    <section class="member-reg-section">
        <div class="container">

            @if ($errors->any())
                <div class="alert-danger" style="border-radius:12px; margin-bottom:24px;">
                    <strong>Terdapat kesalahan pada form:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="reg-card" data-aos="fade-up">
                <div class="reg-card-header">
                    <div class="badge-member">
                        <span></span>
                        Khusus Anggota ASITA
                    </div>
                    <h2>Form Member Log In</h2>
                    <p>Sudah menjadi anggota ASITA? Daftarkan perusahaan Anda ke portal web kami<br>dan dapatkan akses ke dashboard anggota secara otomatis.</p>
                </div>

                <div class="reg-form-body">
                    <div class="info-box">
                        <div class="info-box-icon">🔐</div>
                        <div class="info-box-text">
                            Setelah form ini dikirim, Anda akan langsung mendapatkan <strong>email</strong> dan <strong>password</strong> untuk masuk ke dashboard anggota. Simpan kredensial tersebut dengan aman.
                        </div>
                    </div>

                    <form action="{{ route('member-register.store') }}" method="POST" id="memberRegForm">
                        @csrf

                        {{-- DATA PERUSAHAAN --}}
                        <div class="field-section-label">Data Perusahaan</div>

                        <div class="form-group">
                            <label>Nama Perusahaan <span class="required">*</span></label>
                            <input type="text" name="nama_perusahaan" value="{{ old('nama_perusahaan') }}" required placeholder="Contoh: PT. Travel Nusantara Indonesia">
                        </div>

                        <div class="form-group">
                            <label>Trade Mark <span class="required">*</span></label>
                            <input type="text" name="trade_mark" value="{{ old('trade_mark') }}" required placeholder="Contoh: Nusantara Tour & Travel">
                            <span class="help-text">Nama merek / brand perusahaan yang dikenal publik</span>
                        </div>

                        <div class="form-group">
                            <label>Tanggal Berdiri Perusahaan <span class="required">*</span></label>
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required max="{{ date('Y-m-d') }}">
                        </div>

                        <div class="form-group">
                            <label>Alamat Kantor <span class="required">*</span></label>
                            <textarea name="alamat_kantor" required placeholder="Alamat lengkap kantor perusahaan">{{ old('alamat_kantor') }}</textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Telepon Kantor / WhatsApp <span class="required">*</span></label>
                                <input type="text" name="telepon_wa_perusahaan" value="{{ old('telepon_wa_perusahaan') }}" required placeholder="Contoh: 0812-3456-7890">
                            </div>

                            <div class="form-group">
                                <label>Email <span class="required">*</span></label>
                                <input type="email" name="email_website_perusahaan" value="{{ old('email_website_perusahaan') }}" required placeholder="email@perusahaan.com">
                                <span class="help-text">Digunakan untuk login</span>
                            </div>
                        </div>

                        {{-- LEGALITAS --}}
                        <div class="field-section-label">Nomor Legalitas</div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>NIA <span class="required">*</span></label>
                                <input type="text" name="nia" value="{{ old('nia') }}" required placeholder="Nomor Induk Anggota ASITA">
                                <span class="help-text">Nomor Induk Anggota ASITA</span>
                            </div>

                            <div class="form-group">
                                <label>NIB <span class="required">*</span></label>
                                <input type="text" name="nomor_induk_berusaha_tdup" value="{{ old('nomor_induk_berusaha_tdup') }}" required placeholder="Nomor Induk Berusaha">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>NPWP Perusahaan <span class="required">*</span></label>
                            <input type="text" name="npwp_perusahaan" value="{{ old('npwp_perusahaan') }}" required placeholder="00.000.000.0-000.000">
                        </div>

                        {{-- PRODUK USAHA / BIO --}}
                        <div class="field-section-label">Produk Usaha / Bio Perusahaan</div>

                        <div class="form-group">
                            <label>Produk Usaha / Bio Perusahaan <span class="required">*</span></label>
                            <textarea name="bio_perusahaan" required placeholder="Deskripsikan produk usaha atau profil singkat perusahaan Anda...&#10;Contoh: Kami bergerak di bidang Outbound Tour, Inbound Tour, dan Tiket Pesawat sejak 2010.">{{ old('bio_perusahaan') }}</textarea>
                            <span class="help-text">Ceritakan produk/layanan utama perusahaan Anda</span>
                        </div>

                        <button type="submit" class="btn-submit" id="submitBtn">
                            Daftarkan & Dapatkan Akses
                        </button>
                    </form>
                </div>
            </div>

            <div class="back-link">
                Belum menjadi anggota ASITA? <a href="{{ route('join-us') }}">Daftar sebagai calon member →</a>
            </div>

        </div>
    </section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('memberRegForm');
        const submitBtn = document.getElementById('submitBtn');

        form.addEventListener('submit', function () {
            submitBtn.disabled = true;
            submitBtn.textContent = 'MENGIRIM DATA...';
        });
    });
</script>
@endpush
