@extends('layouts.app')

@section('title', 'Edit Katalog Perusahaan')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Premium Select2 Customization SIKTN */
        .select2-container--default .select2-selection--single {
            height: 48px; padding: 0.4rem 1rem; font-size: 0.92rem; font-family: 'Google Sans', sans-serif;
            font-weight: 500; color: #022648; background-color: #ffffff; border: 1px solid rgba(0, 0, 0, 0.12); border-radius: 8px;
            display: flex; align-items: center; transition: all 0.3s ease;
        }
        .select2-container--default .select2-selection--single:focus,
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #022648; outline: none; box-shadow: 0 0 0 3px rgba(9, 11, 98, 0.05);
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered { color: #022648; padding-left: 0; line-height: normal; font-weight: 600; }
        .select2-container--default .select2-selection--single .select2-selection__placeholder { color: #9ca3af; font-weight: normal; }
        .select2-container--default .select2-selection--single .select2-selection__arrow { height: 46px; right: 12px; }
        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #022648 transparent transparent transparent; border-width: 6px 5px 0 5px;
        }
        
        .select2-dropdown {
            border: 1px solid rgba(2, 38, 72, 0.12); border-radius: 12px; font-family: 'Google Sans', sans-serif; font-size: 0.92rem; z-index: 9999;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12); margin-top: 6px; overflow: hidden;
            background-color: #ffffff;
        }
        .select2-search--dropdown { padding: 10px; }
        .select2-search--dropdown .select2-search__field { 
            border: 1px solid #d1d5db; border-radius: 6px; padding: 0.5rem 0.875rem; outline: none; transition: border 0.3s;
        }
        .select2-search--dropdown .select2-search__field:focus { border-color: #022648 !important; box-shadow: 0 0 0 3px rgba(9, 11, 98, 0.05); }
        
        .select2-results__options { padding: 4px; max-height: 250px; }
        .select2-results__option { 
            padding: 10px 14px !important; margin: 2px 0 !important; border-radius: 6px !important; transition: all 0.2s ease; font-weight: 500; color: #374151;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected],
        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable { 
            background-color: #f0f4f8 !important; color: #022648 !important; font-weight: 700 !important; transform: translateX(4px);
        }
        .select2-container--default .select2-results__option[aria-selected=true] { 
            background-color: #022648 !important; color: #ffffff !important; font-weight: 700 !important; 
        }
        .select2-results__group {
            padding: 8px 12px !important; font-size: 0.75rem !important; text-transform: uppercase !important; letter-spacing: 1px !important; color: #6b7280 !important; font-weight: 700 !important;
        }

        :root {
            --primary-blue: #022648;
            --secondary-blue: #18227C;
            --accent-yellow: #C59217;
            --accent-red: #D60B1C;
            --text-dark: #022648;
            --text-grey: #6b7280;
            --bg-light: #F8F9FC;
        }

        body {
            font-family: 'Google Sans', 'Outfit', sans-serif !important;
            background-color: var(--bg-light) !important;
        }

        .header-sticky {
            top: 0 !important;
            position: sticky !important;
            box-shadow: 0 4px 20px rgba(11, 19, 84, 0.05) !important;
            background-color: #ffffff !important;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .header-inner {
            max-width: 1400px !important;
        }

        .dashboard-container {
            max-width: 1400px;
            margin: 40px auto 80px auto;
            padding: 0 20px;
            font-family: 'Google Sans', sans-serif;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 30px;
        }

        /* SIDEBAR STYLING */
        .dashboard-sidebar {
            background: #ffffff;
            border-radius: 14px;
            padding: 28px 20px;
            box-shadow: 0 10px 30px rgba(11, 19, 84, 0.03);
            border: 1px solid rgba(11, 19, 84, 0.06);
            height: fit-content;
        }

        .user-badge {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        }

        .user-avatar-wrapper {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 15px auto;
            border: 3px solid var(--primary-blue);
            box-shadow: 0 4px 15px rgba(11, 19, 84, 0.12);
        }

        .user-avatar {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-name {
            font-family: 'Google Sans', sans-serif;
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin: 0 0 4px 0;
            letter-spacing: -0.2px;
        }

        .user-nrp {
            font-family: monospace;
            font-size: 0.85rem;
            color: var(--text-grey);
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 10px;
        }

        .status-pending { background-color: rgba(197, 146, 23, 0.08) !important; color: var(--accent-yellow) !important; border: 1px solid rgba(197, 146, 23, 0.2) !important; }
        .status-approved { background-color: rgba(11, 19, 84, 0.06) !important; color: var(--primary-blue) !important; border: 1px solid rgba(11, 19, 84, 0.12) !important; }
        .status-rejected { background-color: rgba(214, 11, 28, 0.08) !important; color: var(--accent-red) !important; border: 1px solid rgba(214, 11, 28, 0.18) !important; }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .sidebar-menu-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 16px;
            width: 100%;
            border: none;
            background: transparent;
            font-family: 'Google Sans', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-grey);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.25s ease;
            text-align: left;
            text-decoration: none;
        }

        .sidebar-menu-btn:hover {
            background-color: rgba(11, 19, 84, 0.04);
            color: var(--primary-blue);
        }

        .sidebar-menu-btn.active {
            background-color: var(--primary-blue);
            color: #ffffff;
        }

        .sidebar-menu-btn i {
            font-size: 1rem;
            width: 18px;
            text-align: center;
            opacity: 0.9;
        }

        .dashboard-content {
            background: #ffffff;
            border-radius: 14px;
            padding: 35px 40px;
            box-shadow: 0 10px 30px rgba(11, 19, 84, 0.03);
            border: 1px solid rgba(11, 19, 84, 0.06);
            min-height: 550px;
        }

        .section-header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid rgba(11, 19, 84, 0.06);
            padding-bottom: 18px;
            margin-bottom: 30px;
        }

        .section-title {
            font-family: 'Google Sans', sans-serif;
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* FORM STYLING */
        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            font-size: 0.92rem;
            border: 1px solid rgba(0, 0, 0, 0.12);
            border-radius: 8px;
            font-family: inherit;
            color: var(--text-dark);
            background-color: #ffffff;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(9, 11, 98, 0.05);
            outline: none;
        }

        .form-control::placeholder {
            color: #9CA3AF;
        }

        .form-hint {
            font-size: 0.8rem;
            color: var(--text-grey);
            margin-top: 6px;
            font-weight: 500;
        }

        .form-file-input {
            display: block;
            width: 100%;
            padding: 16px;
            border: 2px dashed rgba(9, 11, 98, 0.15);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.88rem;
            font-weight: 500;
            color: var(--text-grey);
            background: rgba(9, 11, 98, 0.005);
        }

        .form-file-input:hover {
            border-color: var(--primary-blue);
            background: rgba(9, 11, 98, 0.02);
            color: var(--primary-blue);
        }

        .current-image-label {
            font-size: 0.78rem;
            color: var(--primary-blue);
            font-weight: 700;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .current-logo-wrapper {
            margin-bottom: 15px;
        }

        .current-logo {
            width: 100px;
            height: 100px;
            object-fit: contain;
            border-radius: 8px;
            border: 1px solid rgba(9, 11, 98, 0.08);
            background: rgba(9, 11, 98, 0.005);
            padding: 6px;
        }

        .current-images-grid {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 15px;
        }

        .image-wrapper {
            position: relative;
        }

        .current-gallery-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid rgba(9, 11, 98, 0.08);
        }

        .delete-image-btn {
            position: absolute;
            top: -6px;
            right: -6px;
            background: #EF4444;
            color: white;
            border: 2px solid white;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 13px;
            font-weight: bold;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
            transition: all 0.2s ease;
        }

        .delete-image-btn:hover {
            background: #DC2626;
            transform: scale(1.1);
        }

        .file-preview {
            margin-top: 14px;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .file-preview img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid rgba(9, 11, 98, 0.08);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .info-badge {
            background-color: #E0F2FE;
            color: #0369A1;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 700;
            margin-left: 6px;
            text-transform: uppercase;
        }

        .format-accepted {
            background-color: #D1FAE5;
            color: #065F46;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 700;
            margin-left: 6px;
            text-transform: uppercase;
        }

        .current-map {
            margin-bottom: 14px;
            border: 1px solid rgba(9, 11, 98, 0.08);
            border-radius: 8px;
            overflow: hidden;
        }

        .current-map iframe {
            width: 100%;
            height: 300px;
            border: none;
            display: block;
        }

        .map-preview-container {
            margin-top: 16px;
            border: 1px solid rgba(9, 11, 98, 0.08);
            border-radius: 8px;
            overflow: hidden;
            display: none;
        }

        .map-preview-container.show {
            display: block;
        }

        .map-preview-container iframe {
            width: 100%;
            height: 300px;
            border: none;
        }

        .map-helper {
            background: rgba(9, 11, 98, 0.01);
            border-radius: 8px;
            padding: 18px;
            margin-top: 14px;
            border: 1px solid rgba(9, 11, 98, 0.06);
        }

        .map-helper-title {
            font-weight: 700;
            font-size: 11px;
            color: var(--primary-blue);
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .map-helper-steps {
            font-size: 0.85rem;
            color: #4b5563;
            line-height: 1.6;
        }

        .map-helper-steps ol {
            margin: 8px 0;
            padding-left: 20px;
        }

        .map-helper-steps li {
            margin-bottom: 6px;
        }

        .map-status {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8rem;
            margin-top: 10px;
            padding: 8px 14px;
            border-radius: 6px;
            font-weight: 600;
        }

        .map-status.success { background: #D1FAE5; color: #065F46; border: 1px solid rgba(16, 185, 129, 0.2); }
        .map-status.error { background: #FEE2E2; color: #991B1B; border: 1px solid rgba(239, 68, 68, 0.2); }

        .form-error {
            color: #DC2626;
            font-size: 0.8rem;
            margin-top: 6px;
            font-weight: 600;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 30px;
            border-top: 1px solid rgba(9, 11, 98, 0.06);
            padding-top: 24px;
        }

        .btn-action-submit {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: var(--accent-yellow);
            color: #000000;
            border: 1px solid #000000;
            border-radius: 8px;
            font-size: 0.88rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-action-submit:hover {
            background: transparent;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transform: translateY(-1px);
        }

        .btn-action-cancel {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: #ffffff;
            color: var(--primary-blue);
            border: 1px solid rgba(9, 11, 98, 0.15);
            border-radius: 8px;
            font-size: 0.88rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-action-cancel:hover {
            background: var(--primary-blue);
            color: #ffffff;
            border-color: var(--primary-blue);
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-container">
        <div class="dashboard-grid">
            <!-- SIDEBAR -->
            <aside class="dashboard-sidebar">
                <div class="user-badge">
                    <div class="user-avatar-wrapper">
                        @if($anggota->foto_diri)
                            <img src="{{ Storage::url($anggota->foto_diri) }}" class="user-avatar" alt="{{ $anggota->nama_lengkap }}">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($anggota->nama_lengkap) }}&background=090b62&color=fff&size=120" class="user-avatar" alt="Avatar">
                        @endif
                    </div>
                    <h4 class="user-name">{{ $anggota->nama_lengkap }}</h4>
                    <div class="user-nrp">Username: {{ $anggota->username }}</div>
                    
                    @if($anggota->status == 'pending')
                        <span class="status-badge status-pending"><i class="fas fa-clock"></i> PENDING ACC</span>
                    @elseif($anggota->status == 'approved')
                        <span class="status-badge status-approved"><i class="fas fa-check-circle"></i> VERIFIED MEMBER</span>
                    @else
                        <span class="status-badge status-rejected"><i class="fas fa-times-circle"></i> REJECTED</span>
                    @endif
                </div>

                <nav class="sidebar-menu">
                    <a href="{{ route('profile-anggota') }}#informasi-umum" class="sidebar-menu-btn">
                        <i class="fas fa-user-circle"></i> Informasi Umum
                    </a>
                    <a href="{{ route('profile-anggota') }}#kta-digital" class="sidebar-menu-btn">
                        <i class="fas fa-id-card"></i> KTA Digital
                    </a>
                    <a href="{{ route('profile-anggota') }}#profil-perusahaan" class="sidebar-menu-btn">
                        <i class="fas fa-building"></i> Profil Perusahaan
                    </a>
                    <a href="{{ route('anggota.katalog.index') }}" class="sidebar-menu-btn active">
                        <i class="fas fa-store"></i> Katalog Saya
                    </a>
                    <a href="{{ route('profile-anggota') }}#keamanan" class="sidebar-menu-btn">
                        <i class="fas fa-lock"></i> Ubah Password
                    </a>
                    
                    <form action="{{ route('anggota.logout') }}" method="POST" style="margin-top: 15px; width: 100%;">
                        @csrf
                        <button type="submit" class="sidebar-menu-btn" style="color: #EF4444; border: 1px solid rgba(239, 68, 68, 0.2);">
                            <i class="fas fa-sign-out-alt"></i> Keluar / Logout
                        </button>
                    </form>
                </nav>
            </aside>

            <!-- MAIN CONTENT -->
            <main class="dashboard-content">
                <div class="section-header-row">
                    <h2 class="section-title"><i class="fas fa-edit"></i> Edit Data Katalog</h2>
                </div>

                <form action="{{ route('anggota.katalog.update', $katalog->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Nama Perusahaan -->
                    <div class="form-group">
                        <label for="company_name">
                            Nama Perusahaan <span style="color: red;">*</span>
                            <span class="info-badge">Dapat Diedit</span>
                        </label>
                        <input 
                            type="text" 
                            name="company_name" 
                            id="company_name"
                            class="form-control" 
                            value="{{ old('company_name', $katalog->company_name) }}" 
                            required>
                        <div class="form-hint">Nama perusahaan yang akan ditampilkan di katalog</div>
                        @error('company_name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Bidang Perusahaan -->
                    <div class="form-group">
                        <label for="business_field">
                            Bidang Perusahaan <span style="color: red;">*</span>
                            <span class="info-badge">Dapat Diedit</span>
                        </label>
                        <input 
                            type="text" 
                            name="business_field" 
                            id="business_field"
                            class="form-control" 
                            value="{{ old('business_field', $katalog->business_field) }}" 
                            required>
                        <div class="form-hint">Bidang usaha atau kategori produk/jasa</div>
                        @error('business_field')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Deskripsi Perusahaan -->
                    <div class="form-group">
                        <label for="description">Deskripsi Perusahaan <span style="color: red;">*</span></label>
                        <textarea 
                            name="description" 
                            id="description"
                            class="form-control" 
                            rows="5"
                            placeholder="Ceritakan tentang perusahaan Anda, produk/layanan yang ditawarkan, keunggulan..."
                            required>{{ old('description', $katalog->description) }}</textarea>
                        <div class="form-hint">Deskripsi menarik tentang bisnis Anda</div>
                        @error('description')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Kategori & Harga -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div class="form-group">
                            <label for="kategori_id">Kategori Produk</label>
                            <select name="kategori_id" id="kategori_id" class="form-control">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategoris as $kat)
                                    <option value="{{ $kat->id }}" {{ old('kategori_id', $katalog->kategori_id) == $kat->id ? 'selected' : '' }}>{{ $kat->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="harga">Kisaran Harga</label>
                            <input type="text" name="harga" id="harga" class="form-control" value="{{ old('harga', $katalog->harga) }}" placeholder="Contoh: Rp 50.000">
                        </div>
                    </div>

                    <!-- Logo Perusahaan -->
                    <div class="form-group">
                        <label for="logo">Logo Perusahaan</label>
                        @if($katalog->logo)
                            <div class="current-image-label">Logo saat ini:</div>
                            <div class="current-logo-wrapper">
                                <img src="{{ Storage::url($katalog->logo) }}" alt="Current Logo" class="current-logo">
                            </div>
                        @endif
                        <input type="file" name="logo" id="logo" class="form-file-input" accept="image/*" onchange="previewLogo(event)">
                        <div class="form-hint">Biarkan kosong jika tidak ingin mengubah. Format: JPG, PNG, GIF (Max: 10MB)</div>
                        <div class="file-preview" id="logoPreview"></div>
                        @error('logo')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Galeri Gambar -->
                    <div class="form-group">
                        <label for="images">Galeri Gambar (Max 3)</label>
                        @if($katalog->images && count($katalog->images) > 0)
                            <div class="current-image-label">Gambar saat ini ({{ count($katalog->images) }}):</div>
                            <div class="current-images-grid">
                                @foreach($katalog->images as $index => $image)
                                    <div class="image-wrapper">
                                        <img src="{{ Storage::url($image) }}" alt="Gallery {{ $index + 1 }}" class="current-gallery-img">
                                        <button type="button" class="delete-image-btn" onclick="deleteImage({{ $index }})" title="Hapus gambar">×</button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        <input type="file" name="images[]" id="images" class="form-file-input" accept="image/*" multiple onchange="previewImages(event)">
                        <div class="form-hint">Upload gambar baru akan mengganti seluruh gambar lama. Format: JPG, PNG, GIF (Max: 10MB per file)</div>
                        <div class="file-preview" id="imagesPreview"></div>
                        @error('images.*')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div class="form-group">
                        <label for="address">Alamat Kantor <span style="color: red;">*</span></label>
                        <textarea 
                            name="address" 
                            id="address"
                            class="form-control" 
                            rows="3" 
                            required>{{ old('address', $katalog->address) }}</textarea>
                        <div class="form-hint">Alamat lengkap perusahaan</div>
                        @error('address')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Wilayah -->
                    <div class="form-group">
                        <label for="wilayahSelect">Wilayah <span style="color: red;">*</span></label>
                        <select name="wilayah" id="wilayahSelect" class="form-control" style="width: 100%;" required>
                            <option value="">-- Pilih Wilayah Domisili --</option>
                            @php $currentWilayah = old('wilayah', $katalog->wilayah ?? $anggota->domisili ?? ''); @endphp
                            @if($currentWilayah)
                                <option value="{{ $currentWilayah }}" selected>{{ $currentWilayah }}</option>
                            @endif
                        </select>
                        <div class="form-hint">Provinsi atau Kabupaten/Kota lokasi perusahaan/produk</div>
                        @error('wilayah')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Nomor Telepon -->
                    <div class="form-group">
                        <label for="phone">Nomor Telepon / WhatsApp <span style="color: red;">*</span></label>
                        <input 
                            type="text" 
                            name="phone" 
                            id="phone"
                            class="form-control" 
                            value="{{ old('phone', $katalog->phone) }}" 
                            placeholder="Contoh: 08123456789"
                            required>
                        <div class="form-hint">Nomor kontak bisnis perusahaan Anda</div>
                        @error('phone')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">Email Bisnis <span style="color: red;">*</span></label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email"
                            class="form-control" 
                            value="{{ old('email', $katalog->email) }}" 
                            placeholder="email@perusahaan.com"
                            required>
                        <div class="form-hint">Email bisnis perusahaan Anda</div>
                        @error('email')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Website & Marketplace -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div class="form-group">
                            <label for="website_url">Link Website</label>
                            <input type="url" name="website_url" id="website_url" class="form-control" value="{{ old('website_url', $katalog->website_url) }}" placeholder="https://website.com">
                        </div>
                        <div class="form-group">
                            <label for="marketplace_url">Link Marketplace</label>
                            <input type="url" name="marketplace_url" id="marketplace_url" class="form-control" value="{{ old('marketplace_url', $katalog->marketplace_url) }}" placeholder="https://tokopedia.com/...">
                        </div>
                    </div>

                    <!-- Google Maps Embed -->
                    <div class="form-group">
                        <label for="map_embed_url">
                            Lokasi Google Maps (Embed Code)
                            <span class="format-accepted">Hanya kode Embed!</span>
                        </label>
                        @if($katalog->map_embed_url)
                            <div class="current-image-label">Map saat ini:</div>
                            <div class="current-map">
                                <iframe src="{{ $katalog->map_embed_url }}" allowfullscreen loading="lazy"></iframe>
                            </div>
                        @endif
                        <textarea 
                            name="map_embed_url" 
                            id="map_embed_url"
                            class="form-control" 
                            rows="4"
                            placeholder="Paste kode iframe embed dari Google Maps di sini..."
                            oninput="handleMapInput(this.value)">{{ old('map_embed_url', $katalog->map_embed_url) }}</textarea>
                        <div class="form-hint">
                            <strong>Yang diterima:</strong> Kode iframe embed lengkap dari Google Maps. Biarkan kosong jika tidak ingin mengubah.
                        </div>
                        
                        <div id="mapStatus"></div>

                        <div class="map-helper">
                            <div class="map-helper-title">Cara mendapatkan kode embed:</div>
                            <div class="map-helper-steps">
                                <ol>
                                    <li>Buka <strong>google.com/maps</strong> di browser</li>
                                    <li>Cari dan klik lokasi perusahaan Anda</li>
                                    <li>Klik tombol <strong>"Share"</strong> atau <strong>"Bagikan"</strong></li>
                                    <li>Pilih tab <strong>"Embed a map"</strong> atau <strong>"Sematkan peta"</strong></li>
                                    <li>Klik <strong>"COPY HTML"</strong></li>
                                    <li>Paste kode yang sudah dicopy ke kolom di atas</li>
                                </ol>
                            </div>
                        </div>

                        <div class="map-preview-container" id="mapPreviewContainer">
                            <iframe id="mapPreviewFrame" src="" allowfullscreen loading="lazy"></iframe>
                        </div>
                        
                        @error('map_embed_url')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- FORM ACTIONS -->
                    <div class="form-actions">
                        <button type="submit" class="btn-action-submit">
                            <i class="fas fa-save"></i> Perbarui Data Katalog
                        </button>
                        <a href="{{ route('anggota.katalog.index') }}" class="btn-action-cancel">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </main>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function deleteImage(index) {
            if (!confirm('Apakah Anda yakin ingin menghapus gambar ini?')) {
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("anggota.katalog.delete-image", $katalog->id) }}';

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';

            const indexInput = document.createElement('input');
            indexInput.type = 'hidden';
            indexInput.name = 'image_index';
            indexInput.value = index;

            form.appendChild(csrfInput);
            form.appendChild(methodInput);
            form.appendChild(indexInput);

            document.body.appendChild(form);
            form.submit();
        }

        function previewLogo(event) {
            const preview = document.getElementById('logoPreview');
            preview.innerHTML = '';
            
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    preview.appendChild(img);
                }
                reader.readAsDataURL(file);
            }
        }

        function previewImages(event) {
            const preview = document.getElementById('imagesPreview');
            preview.innerHTML = '';
            
            const files = event.target.files;
            for (let i = 0; i < Math.min(files.length, 3); i++) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    preview.appendChild(img);
                }
                reader.readAsDataURL(files[i]);
            }
        }

        let debounceTimer;
        function handleMapInput(input) {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                previewMap(input);
            }, 500);
        }

        function showMapStatus(type, message) {
            const statusDiv = document.getElementById('mapStatus');
            const icons = {
                success: '<i class="fas fa-check-circle"></i>',
                error: '<i class="fas fa-exclamation-circle"></i>'
            };
            
            statusDiv.innerHTML = `
                <div class="map-status ${type}">
                    ${icons[type]}
                    <span>${message}</span>
                </div>
            `;
        }

        function previewMap(input) {
            const previewContainer = document.getElementById('mapPreviewContainer');
            const previewFrame = document.getElementById('mapPreviewFrame');
            
            if (!input || input.trim() === '') {
                previewContainer.classList.remove('show');
                document.getElementById('mapStatus').innerHTML = '';
                return;
            }

            const embedUrl = extractEmbedUrl(input);
            
            if (embedUrl) {
                previewFrame.src = embedUrl;
                previewContainer.classList.add('show');
                showMapStatus('success', 'Kode embed terdeteksi! Preview map ditampilkan di bawah.');
            } else {
                previewContainer.classList.remove('show');
                showMapStatus('error', 'Kode embed tidak valid. Pastikan Anda copy KODE IFRAME dari Google Maps (bukan URL biasa).');
            }
        }

        function extractEmbedUrl(input) {
            input = input.trim();
            const srcMatch = input.match(/src=["']([^"']+)["']/);
            if (srcMatch) {
                const url = srcMatch[1];
                if (url.includes('google.com/maps/embed') || (url.includes('google.com/maps') && url.includes('output=embed'))) {
                    return url;
                }
            }
            if (input.includes('google.com/maps/embed') || (input.includes('google.com/maps') && input.includes('output=embed'))) {
                return input;
            }
            return null;
        }

        // Initialize map preview if old embed url exists
        document.addEventListener('DOMContentLoaded', () => {
            const oldMapInput = document.getElementById('map_embed_url').value;
            if (oldMapInput) {
                previewMap(oldMapInput);
            }
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const currentWilayah = "{{ old('wilayah', $katalog->wilayah ?? $anggota->domisili ?? '') }}";
            
            Promise.all([
                fetch("{{ asset('provinces.json') }}").then(res => res.json()),
                fetch("{{ asset('regencies.json') }}").then(res => res.json())
            ]).then(([provinces, regencies]) => {
                let optgroupProv = $('<optgroup label="Tingkat Provinsi"></optgroup>');
                provinces.forEach(prov => {
                    if (prov !== currentWilayah) optgroupProv.append(`<option value="${prov}">${prov}</option>`);
                });

                let optgroupReg = $('<optgroup label="Tingkat Kabupaten/Kota"></optgroup>');
                regencies.forEach(reg => {
                    if (reg !== currentWilayah) optgroupReg.append(`<option value="${reg}">${reg}</option>`);
                });

                $('#wilayahSelect').append(optgroupProv).append(optgroupReg);
                $('#wilayahSelect').select2({
                    placeholder: "-- Pilih Wilayah Domisili --",
                    allowClear: true,
                    width: '100%'
                });

                $('#kategori_id').select2({
                    placeholder: "-- Pilih atau Ketik Kategori Baru --",
                    tags: true,
                    allowClear: true,
                    width: '100%'
                });
            }).catch(err => {
                console.error('Gagal meload data wilayah:', err);
                $('#wilayahSelect').select2({
                    placeholder: "-- Pilih Wilayah Domisili --",
                    allowClear: true,
                    width: '100%'
                });

                $('#kategori_id').select2({
                    placeholder: "-- Pilih atau Ketik Kategori Baru --",
                    tags: true,
                    allowClear: true,
                    width: '100%'
                });
            });
        });
    </script>
@endpush