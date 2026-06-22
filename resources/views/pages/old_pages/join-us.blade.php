@extends('layouts.app')

@section('title', 'Jadi Anggota')

@push('styles')
    <style>
        /* ==============================================
           JOIN US PAGE
           ============================================== */

        :root {
            --primary-blue: #022648;
            --secondary-blue: #2A348D;
            --accent-yellow: #FFE701;
            --text-dark: #04293B;
            --text-grey: #6b7280;
            --bg-light: #F8F9FA;
        }

        /* Hide sticky header & scroll-top on this page */
        #headerSticky {
            display: none !important;
        }

        #scrollTop {
            display: none !important;
        }

        /* ── Custom Page Header ── */
        .custom-page-header {
            width: 100%;
            display: flex;
            justify-content: center;
            padding: 20px;
            background: #fff;
            border-bottom: 1px solid #eee;
        }

        .custom-page-header .header-inner {
            width: 100%;
            max-width: 1300px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }

        .custom-page-header .header-inner .logo img {
            width: 80px;
            height: 80px;
            display: block;
        }

        .custom-page-header .header-inner .nav-link {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 25px;
            align-items: center;
        }

        .custom-page-header .header-inner .nav-link a {
            text-decoration: none;
            color: #000;
            font-weight: 400;
            transition: all 0.2s;
            font-size: 15px;
            white-space: nowrap;
        }

        .custom-page-header .header-inner .nav-link a:hover {
            color: var(--accent-yellow);
            transform: translateY(-2px);
        }

        .custom-page-header .header-inner .nav-link a.active {
            font-weight: 700;
        }

        .custom-page-header .header-inner .buttons {
            display: flex;
            gap: 16px;
            align-items: center;
            margin-left: auto;
        }

        /* ── Hamburger color override for white bg header ── */
        .custom-page-header .hamburger span {
            background-color: #000;
        }

        /* ── Title Section ── */
        .title-section {
            padding: 80px 20px 40px;
            text-align: center;
            max-width: 900px;
            margin: 0 auto;
        }

        .title-section h1 {
            font-size: 52px;
            font-weight: 800;
            color: var(--primary-blue);
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
            line-height: 1.1;
        }

        .title-section h2 {
            font-size: 20px;
            font-weight: 600;
            color: #000;
            margin: 12px 0 0;
        }

        .title-section p {
            font-size: 15px;
            color: var(--text-grey);
            line-height: 1.6;
            margin-top: 16px;
        }

        /* ── Form Container ── */
        .form-container {
            max-width: 1200px;
            margin: 0 auto 100px;
            padding: 0 20px;
        }

        /* ── Step Indicator ── */
        .step-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
            margin-bottom: 50px;
            padding-top: 20px;
        }

        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .step-circle {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background-color: #E5E7EB;
            color: #9ca3af;
            font-weight: 800;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .step-label {
            font-size: 12px;
            font-weight: 600;
            color: #9ca3af;
            transition: all 0.3s;
            white-space: nowrap;
        }

        .step-item.active .step-circle {
            background-color: var(--primary-blue);
            color: var(--accent-yellow);
        }

        .step-item.active .step-label {
            color: var(--primary-blue);
        }

        .step-item.done .step-circle {
            background-color: var(--secondary-blue);
            color: #fff;
        }

        .step-item.done .step-label {
            color: var(--secondary-blue);
        }

        .step-line {
            flex: 1;
            max-width: 120px;
            height: 2px;
            background-color: #E5E7EB;
            margin: 0 12px;
            margin-bottom: 26px;
        }

        /* ── Form Sections ── */
        .form-section {
            display: none;
        }

        .form-section.active {
            display: block;
        }

        .form-section-title {
            font-size: 26px;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 36px;
            padding-top: 20px;
        }

        /* ── Form Grid ── */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px 45px;
            margin-bottom: 50px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        .form-group label {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-dark);
        }

        .form-group label span {
            color: #ff0000;
            margin-left: 3px;
        }

        /* ── Inputs ── */
        .form-control {
            width: 100%;
            padding: 16px 22px;
            background-color: var(--bg-light);
            border: 1.5px solid #E5E7EB;
            border-radius: 14px;
            font-family: 'Google Sans', sans-serif;
            font-size: 16px;
            color: #333;
            transition: all 0.2s;
            box-sizing: border-box;
            appearance: auto;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--secondary-blue);
            background-color: #fff;
            box-shadow: 0 0 0 5px rgba(42, 52, 141, 0.08);
        }

        .form-control.textarea {
            height: 120px;
            resize: vertical;
        }

        /* ── Radio Group ── */
        .radio-group {
            display: flex;
            gap: 30px;
            padding: 12px 0;
            flex-wrap: wrap;
        }

        .radio-group--wrap {
            flex-direction: column;
            gap: 10px;
        }

        .radio-item {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 500;
        }

        .radio-item input[type="radio"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: var(--secondary-blue);
            flex-shrink: 0;
        }

        /* ── Checkbox ── */
        .checkbox {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: var(--secondary-blue);
            flex-shrink: 0;
        }

        /* ── File Upload ── */
        .file-hidden {
            display: none;
        }

        .file-upload-wrapper {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .btn-upload {
            width: fit-content;
            padding: 12px 28px;
            background: #fff;
            border: 1.5px solid #E5E7EB;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 10px;
            white-space: nowrap;
        }

        .btn-upload:hover {
            border-color: var(--text-grey);
            background: #F9FAFB;
        }

        .file-info {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .file-name {
            font-size: 13px;
            color: var(--text-grey);
            word-break: break-all;
        }

        /* ── Upload hint text ── */
        .upload-hint {
            font-size: 12px;
            color: #666;
            line-height: 1.7;
            margin: 0;
        }

        /* ── Agreement block ── */
        .form-agree {
            margin-bottom: 40px;
        }

        .agree-text {
            font-size: 14px;
            font-weight: 600;
            margin: 0 0 12px;
        }

        /* ── Navigation Buttons ── */
        .form-navigation {
            display: flex;
            gap: 20px;
            margin-top: 60px;
        }

        .btn-prev {
            font-family: 'Google Sans', sans-serif;
            flex: 1;
            padding: 18px 20px;
            background-color: #04293B;
            color: #fff;
            border: none;
            border-radius: 12px;
            font-weight: 800;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
            text-decoration: none;
        }

        .btn-prev:hover {
            transform: translateY(-2px);
            opacity: 0.9;
        }

        .btn-next {
            font-family: 'Google Sans', sans-serif;
            flex: 1;
            padding: 18px 20px;
            background-color: var(--accent-yellow);
            color: #000;
            border: none;
            border-radius: 12px;
            font-weight: 800;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
            text-decoration: none;
        }

        .btn-next:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        /* ==============================================
           RESPONSIVE
           ============================================== */

        /* ── Tablet: 1024px ── */
        @media (max-width: 1024px) {
            .title-section h1 {
                font-size: 42px;
            }

            .form-grid {
                gap: 20px 30px;
            }

            .step-line {
                max-width: 80px;
            }
        }

        /* ── Mobile: 900px (hamburger header) ── */
        @media (max-width: 900px) {

            .custom-page-header .header-inner .nav-link,
            .custom-page-header .header-inner .buttons {
                display: none !important;
            }

            .custom-page-header .header-inner .hamburger {
                display: flex;
            }
        }

        /* ── Mobile: 768px ── */
        @media (max-width: 768px) {
            .title-section {
                padding: 50px 16px 30px;
            }

            .title-section h1 {
                font-size: 32px;
                letter-spacing: 1px;
            }

            .title-section h2 {
                font-size: 16px;
            }

            .title-section p {
                font-size: 14px;
            }

            /* Form grid: 1 kolom */
            .form-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .form-group.full-width {
                grid-column: span 1;
            }

            .form-section-title {
                font-size: 22px;
            }

            /* Navigation buttons stack on very small screens */
            .form-navigation {
                flex-direction: column;
                gap: 12px;
                margin-top: 40px;
            }

            .btn-prev,
            .btn-next {
                font-size: 15px;
                padding: 16px;
            }

            /* Step indicator smaller */
            .step-circle {
                width: 36px;
                height: 36px;
                font-size: 14px;
            }

            .step-label {
                font-size: 11px;
            }

            .step-line {
                max-width: 40px;
                margin: 0 6px;
                margin-bottom: 22px;
            }

            /* Radio group vertical on mobile */
            .radio-group {
                gap: 14px;
                flex-direction: column;
            }

            /* Header logo smaller */
            .custom-page-header .header-inner .logo img {
                width: 56px;
                height: 56px;
            }

            .custom-page-header {
                padding: 14px 16px;
            }
        }

        /* ── Small: 480px ── */
        @media (max-width: 480px) {
            .title-section h1 {
                font-size: 26px;
            }

            .form-container {
                margin-bottom: 60px;
                padding: 0 14px;
            }

            .form-control {
                padding: 13px 16px;
                font-size: 15px;
                border-radius: 10px;
            }

            .btn-upload {
                padding: 10px 18px;
                font-size: 13px;
            }

            .step-indicator {
                margin-bottom: 30px;
            }
        }
    </style>
@endpush

@section('hero-section')
    <header class="custom-page-header">
        <div class="header-inner">
            <div class="logo">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('assets-front/images/logo_karang_taruna.png') }}" alt="Logo Karang Taruna">
                </a>
            </div>
            <div class="nav-link">
                <a href="{{ route('home') }}" @if(Route::currentRouteName() == 'home') class="active" @endif>Beranda</a>
                <a href="{{ route('about') }}" @if(Route::currentRouteName() == 'about') class="active" @endif>Tentang</a>
                <a href="{{ route('organisasi') }}" @if(Route::currentRouteName() == 'organisasi') class="active"
                @endif>Organisasi</a>
                <a href="{{ route('e-katalog') }}" @if(Route::currentRouteName() == 'e-katalog') class="active"
                @endif>E-Catalog</a>
                <a href="{{ route('berita') }}" @if(Route::currentRouteName() == 'berita') class="active" @endif>Berita</a>
            </div>
            <div class="buttons">
                <a href="{{ route('join-us') }}" class="btn-transparent-border-black">Jadi Anggota</a>
                <a href="{{ route('anggota.login') }}" class="btn-yellow-border-black">Login</a>
            </div>
        </div>
    </header>
@endsection

@section('content')
    <div class="title-section">
        <h1 data-aos="fade-up">JADI ANGGOTA</h1>
        <h2 data-aos="fade-up" data-aos-delay="100">Formulir Pendaftaran Corps Alumni Akademi Ilmu Pelayaran</h2>
        <p data-aos="fade-up" data-aos-delay="200">
            Wadah resmi alumni STIP Jakarta untuk kolaborasi, karier, dan kontribusi nasional.
        </p>
    </div>

    <div class="form-container" data-aos="fade-up" data-aos-delay="300">
        <form action="{{ route('jadi-anggota.store') }}" method="POST" enctype="multipart/form-data" id="multiStepForm">
            @csrf

            <!-- STEP INDICATOR -->
            <div class="step-indicator">
                <div class="step-item active" id="indicator-1">
                    <div class="step-circle">1</div>
                    <span class="step-label">Data Pribadi</span>
                </div>
                <div class="step-line"></div>
                <div class="step-item" id="indicator-2">
                    <div class="step-circle">2</div>
                    <span class="step-label">Organisasi</span>
                </div>
            </div>

            <!-- STEP 1: DATA PRIBADI -->
            <div class="form-section active" id="step1">
                <div class="form-section-title">Data Pribadi</div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Nama Lengkap<span>*</span></label>
                        <input type="text" name="nama_usaha" class="form-control" placeholder="Nama Lengkap Anda" required>
                    </div>

                    <div class="form-group">
                        <label>NRP (Nomor Registrasi Pokok)<span>*</span></label>
                        <input type="text" name="nrp" class="form-control" placeholder="Contoh: 11.6562/K" required>
                    </div>

                    <div class="form-group">
                        <label>Angkatan<span>*</span></label>
                        <input type="number" name="angkatan" class="form-control" placeholder="Contoh: 19" required>
                    </div>

                    <div class="form-group">
                        <label>Jenis Kelamin<span>*</span></label>
                        <div class="radio-group">
                            <label class="radio-item">
                                <input type="radio" name="gender" value="laki-laki" required> Laki-laki
                            </label>
                            <label class="radio-item">
                                <input type="radio" name="gender" value="perempuan" required> Perempuan
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Tempat Lahir<span>*</span></label>
                        <input type="text" name="tempat_lahir" class="form-control" placeholder="" required>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Lahir<span>*</span></label>
                        <input type="date" name="tanggal_lahir" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Agama<span>*</span></label>
                        <input type="text" name="agama" class="form-control" placeholder="" required>
                    </div>

                    <div class="form-group">
                        <label>Nomor Telepon (yang terhubung dengan WhatsApp)<span>*</span></label>
                        <input type="text" name="no_telp" class="form-control" placeholder="" required>
                    </div>

                    <div class="form-group full-width">
                        <label>Alamat Domisili<span>*</span></label>
                        <textarea name="alamat_domisili" class="form-control textarea" required></textarea>
                    </div>

                    <div class="form-group">
                        <label>Kode Pos<span>*</span></label>
                        <input type="text" name="kode_pos" class="form-control" placeholder="" required>
                    </div>

                    <div class="form-group">
                        <label>Email<span>*</span></label>
                        <input type="email" name="email" class="form-control" placeholder="" required>
                    </div>

                    <div class="form-group full-width">
                        <label>Nomor KTP<span>*</span></label>
                        <input type="text" name="no_ktp" class="form-control" placeholder="" required>
                    </div>

                    <div class="form-group">
                        <label>Upload Foto KTP<span>*</span></label>
                        <div class="file-upload-wrapper">
                            <input type="file" name="foto_ktp" id="ktp" class="file-hidden" onchange="updateFileName(this)"
                                required>
                            <div class="file-info">
                                <label for="ktp" class="btn-upload">
                                    <i class="fas fa-cloud-upload-alt"></i> Choose File
                                </label>
                                <span class="file-name">No file chosen</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Upload Foto Diri<span>*</span></label>
                        <div class="file-upload-wrapper">
                            <input type="file" name="foto_diri" id="foto" class="file-hidden"
                                onchange="updateFileName(this)" required>
                            <div class="file-info">
                                <label for="foto" class="btn-upload">
                                    <i class="fas fa-cloud-upload-alt"></i> Choose File
                                </label>
                                <span class="file-name">No file chosen</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-navigation">
                    <button type="button" class="btn-next" onclick="nextStep(2)">Berikutnya</button>
                </div>
            </div>

            <!-- STEP 2: ORGANISASI & DAFTAR -->
            <div class="form-section" id="step2">
                <div class="form-section-title">Organisasi</div>

                <div class="form-grid">
                    <div class="form-group full-width">
                        <label>Apakah anda referensi dari Anggota Karang Taruna?<span>*</span></label>
                        <div class="radio-group">
                            <label class="radio-item"><input type="radio" name="ref_hipmi" value="Ya" required> Ya</label>
                            <label class="radio-item"><input type="radio" name="ref_hipmi" value="Tidak" required>
                                Tidak</label>
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label>Apakah Anda aktif di organisasi selain Karang Taruna?<span>*</span></label>
                        <div class="radio-group">
                            <label class="radio-item"><input type="radio" name="aktif_org_lain" value="Ya" required>
                                Ya</label>
                            <label class="radio-item"><input type="radio" name="aktif_org_lain" value="Tidak" required>
                                Tidak</label>
                        </div>
                    </div>
                </div>

                <div class="form-section-title">Daftar</div>
                <div class="form-group full-width form-agree">
                    <p class="agree-text">Dengan ini saya menyatakan bahwa data yang saya isi adalah benar dan
                        valid<span>*</span></p>
                    <label class="radio-item">
                        <input type="checkbox" name="setuju" class="checkbox" required> Ya, saya setuju
                    </label>
                </div>

                <div class="form-navigation">
                    <button type="button" class="btn-prev" onclick="nextStep(1)">Sebelumnya</button>
                    <button type="submit" class="btn-next">Kirim Pendaftaran</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        function nextStep(step) {
            // Basic validation for the first step
            if (step === 2) {
                const step1Inputs = document.querySelectorAll('#step1 input[required], #step1 textarea[required]');
                let isValid = true;

                // Reset errors
                step1Inputs.forEach(input => {
                    input.style.borderColor = '';
                });

                // Simple check
                for (let input of step1Inputs) {
                    if (input.type === 'radio') {
                        const name = input.name;
                        const checked = document.querySelector(`input[name="${name}"]:checked`);
                        if (!checked) {
                            isValid = false;
                            alert('Mohon lengkapi data Jenis Kelamin.');
                            return;
                        }
                    } else if (!input.value.trim()) {
                        input.style.borderColor = 'red';
                        isValid = false;
                    }
                }

                if (!isValid) {
                    alert('Mohon lengkapi semua data wajib pada Data Pribadi.');
                    return;
                }
            }

            document.querySelectorAll('.form-section').forEach(s => s.classList.remove('active'));
            document.getElementById('step' + step).classList.add('active');

            // Update step indicator
            document.querySelectorAll('.step-item').forEach((el, i) => {
                el.classList.toggle('active', i + 1 === step);
                el.classList.toggle('done', i + 1 < step);
            });

            window.scrollTo({ top: document.querySelector('.title-section').offsetTop - 50, behavior: 'smooth' });
        }

        function updateFileName(input) {
            const fileName = input.files[0] ? input.files[0].name : 'No file chosen';
            input.parentElement.querySelector('.file-name').textContent = fileName;
        }
    </script>
    <script>
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#022648',
                confirmButtonText: 'OK'
            });
        @endif

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#FFE701',
                confirmButtonText: 'OK'
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonColor: '#022648',
                confirmButtonText: 'OK'
            });
        @endif
    </script>
@endpush