@extends('layouts.app')

@section('title', 'Registrasi UMKM - ASITA Jawa Barat')

{{-- Success/Error Messages --}}
@if(session('success'))
<div class="alert alert-success">
    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none">
        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
        <polyline points="22 4 12 14.01 9 11.01"></polyline>
    </svg>
    <span>{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div class="alert alert-error">
    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none">
        <circle cx="12" cy="12" r="10"></circle>
        <line x1="15" y1="9" x2="9" y2="15"></line>
        <line x1="9" y1="9" x2="15" y2="15"></line>
    </svg>
    <span>{{ session('error') }}</span>
</div>
@endif

@if($errors->any())
<div class="alert alert-error">
    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none">
        <circle cx="12" cy="12" r="10"></circle>
        <line x1="12" y1="8" x2="12" y2="12"></line>
        <line x1="12" y1="16" x2="12.01" y2="16"></line>
    </svg>
    <div>
        <strong>Ada beberapa kesalahan:</strong>
        <ul style="margin: 10px 0 0 20px; padding: 0;">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

@section('content')

<section class="ja-page-banner">
    <h1>Registrasi UMKM</h1>
    <p>ASITA coNEXTion - UMKM Kota Bogor</p>
    <div class="rules">
        <p>Formulir ini ditujukan untuk UMKM yang ingin bergabung dalam program ASITA coNEXTion. Mohon isi data dengan lengkap dan benar.</p>
        <h3>Syarat & Ketentuan</h3>
        <p>1. Memiliki usaha yang aktif beroperasi<br>2. Berdomisili di wilayah Kota Bogor<br>3. Bersedia mengikuti program pembinaan UMKM</p>
    </div>

    <!-- Stepper Progress -->
    <div class="stepper-container">
        <div class="stepper-wrapper">
            <div class="stepper-item active" data-step="1">
                <div class="stepper-circle"></div>
                <div class="stepper-label">Data Usaha</div>
            </div>
            <div class="stepper-line" data-line="1"></div>
            <div class="stepper-item" data-step="2">
                <div class="stepper-circle"></div>
                <div class="stepper-label">Data Pribadi</div>
            </div>
            <div class="stepper-line" data-line="2"></div>
            <div class="stepper-item" data-step="3">
                <div class="stepper-circle"></div>
                <div class="stepper-label">Data Lainnya</div>
            </div>
        </div>
    </div>

    <!-- Form Multi-Step -->
    <div class="form-section">
        <form action="{{ route('umkm.store') }}" method="POST" enctype="multipart/form-data" id="multiStepForm">
            @csrf

            <!-- Step 1: Data Usaha -->
            <div class="form-step active" data-step="1">
                <h2 class="form-title">Data Usaha</h2>

                <div class="form-group full-width">
                    <label for="nama_usaha">Nama Usaha / Brand<span class="required">*</span></label>
                    <input type="text" id="nama_usaha" name="nama_usaha" class="form-control" value="{{ old('nama_usaha') }}" required>
                </div>

                <div class="form-group full-width">
                    <label>Bidang Usaha<span class="required">*</span></label>
                    <div class="radio-group-vertical">
                        <label class="radio-label">
                            <input type="radio" name="bidang_usaha" value="Makanan & Minuman" {{ old('bidang_usaha') == 'Makanan & Minuman' ? 'checked' : '' }} required>
                            <span>Makanan & Minuman</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="bidang_usaha" value="Fashion" {{ old('bidang_usaha') == 'Fashion' ? 'checked' : '' }} required>
                            <span>Fashion</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="bidang_usaha" value="Kerajinan / Handmade" {{ old('bidang_usaha') == 'Kerajinan / Handmade' ? 'checked' : '' }} required>
                            <span>Kerajinan / Handmade</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="bidang_usaha" value="Pertanian / Perikanan" {{ old('bidang_usaha') == 'Pertanian / Perikanan' ? 'checked' : '' }} required>
                            <span>Pertanian / Perikanan</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="bidang_usaha" value="Jasa" {{ old('bidang_usaha') == 'Jasa' ? 'checked' : '' }} required>
                            <span>Jasa</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="bidang_usaha" value="Lainnya" {{ old('bidang_usaha') == 'Lainnya' ? 'checked' : '' }} required>
                            <span>Lainnya</span>
                        </label>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
    <label>Status Legalitas Usaha<span class="required">*</span></label>
    <div class="radio-group-vertical">
        <label class="radio-label">
            <input type="radio" name="status_legalitas" value="Sudah Berizin" {{ old('status_legalitas') == 'Sudah Berizin' ? 'checked' : '' }} required>
            <span>Sudah Berizin</span>
        </label>
        <label class="radio-label">
            <input type="radio" name="status_legalitas" value="Belum Berizin" {{ old('status_legalitas') == 'Belum Berizin' ? 'checked' : '' }} required>
            <span>Belum Berizin</span>
        </label>
        <!-- HAPUS YANG KETIGA INI -->
    </div>
</div>
                    <div class="form-group">
                        <label>Jenis Legalitas (Jika Ada)</label>
                        <div class="radio-group-vertical">
                            <label class="radio-label">
                                <input type="radio" name="jenis_legalitas" value="NIB" {{ old('jenis_legalitas') == 'NIB' ? 'checked' : '' }}>
                                <span>NIB</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="jenis_legalitas" value="PIRT" {{ old('jenis_legalitas') == 'PIRT' ? 'checked' : '' }}>
                                <span>PIRT</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="jenis_legalitas" value="Halal" {{ old('jenis_legalitas') == 'Halal' ? 'checked' : '' }}>
                                <span>Halal</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="jenis_legalitas" value="BPOM" {{ old('jenis_legalitas') == 'BPOM' ? 'checked' : '' }}>
                                <span>BPOM</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="jenis_legalitas" value="Sertifikat Lainnya" {{ old('jenis_legalitas') == 'Sertifikat Lainnya' ? 'checked' : '' }}>
                                <span>Sertifikat Lainnya</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="jenis_legalitas" value="Lainnya" {{ old('jenis_legalitas') == 'Lainnya' ? 'checked' : '' }}>
                                <span>Lainnya</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label for="tahun_berdiri">Tahun Berdiri Usaha<span class="required">*</span></label>
                    <input type="text" id="tahun_berdiri" name="tahun_berdiri" class="form-control" value="{{ old('tahun_berdiri') }}" placeholder="Contoh: 2020" required>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-submit btn-next" data-next="2">Berikutnya</button>
                </div>
            </div>

            <!-- Step 2: Data Pribadi -->
            <div class="form-step" data-step="2">
                <h2 class="form-title">Data Pribadi</h2>

                <div class="form-row">
                    <div class="form-group">
                        <label for="nama_lengkap">Nama Lengkap<span class="required">*</span></label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Jenis Kelamin<span class="required">*</span></label>
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio" name="jenis_kelamin" value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'checked' : '' }} required>
                                <span>Laki-laki</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="jenis_kelamin" value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'checked' : '' }} required>
                                <span>Perempuan</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="tanggal_lahir">Tanggal Lahir<span class="required">*</span></label>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="nomor_hp">No HP/WhatsApp aktif<span class="required">*</span></label>
                        <input type="tel" id="nomor_hp" name="nomor_hp" class="form-control" value="{{ old('nomor_hp') }}" placeholder="Contoh: 081234567890" required>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label for="email">Email<span class="required">*</span></label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>

                <div class="form-group full-width">
                    <label for="alamat_domisili">Alamat Domisili<span class="required">*</span></label>
                    <textarea id="alamat_domisili" name="alamat_domisili" class="form-control" rows="4" placeholder="Masukkan alamat lengkap Anda" required>{{ old('alamat_domisili') }}</textarea>
                </div>

                <div class="form-actions-two">
                    <button type="button" class="btn-secondary btn-prev" data-prev="1">Sebelumnya</button>
                    <button type="button" class="btn-submit btn-next" data-next="3">Berikutnya</button>
                </div>
            </div>

            <!-- Step 3: Data Lainnya -->
            <div class="form-step" data-step="3">
                <h2 class="form-title">Data Lainnya</h2>

                <div class="form-group full-width">
                    <label>Apakah usaha Bapak/Ibu sudah menggunakan platform digital untuk penjualan?<span class="required">*</span></label>
                    <div class="radio-group-vertical">
                        <label class="radio-label">
                            <input type="radio" name="platform_digital" value="Ya" {{ old('platform_digital') == 'Ya' ? 'checked' : '' }} required>
                            <span>Ya</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="platform_digital" value="Tidak" {{ old('platform_digital') == 'Tidak' ? 'checked' : '' }} required>
                            <span>Tidak</span>
                        </label>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label>Platform yang digunakan<span class="required">*</span></label>
                    <div class="checkbox-group-vertical">
                        <label class="checkbox-label">
                            <input type="checkbox" name="platform[]" value="Instagram" {{ is_array(old('platform')) && in_array('Instagram', old('platform')) ? 'checked' : '' }}>
                            <span>Instagram</span>
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" name="platform[]" value="Facebook" {{ is_array(old('platform')) && in_array('Facebook', old('platform')) ? 'checked' : '' }}>
                            <span>Facebook</span>
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" name="platform[]" value="Tiktok" {{ is_array(old('platform')) && in_array('Tiktok', old('platform')) ? 'checked' : '' }}>
                            <span>Tiktok</span>
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" name="platform[]" value="Shopee" {{ is_array(old('platform')) && in_array('Shopee', old('platform')) ? 'checked' : '' }}>
                            <span>Shopee</span>
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" name="platform[]" value="Tokopedia" {{ is_array(old('platform')) && in_array('Tokopedia', old('platform')) ? 'checked' : '' }}>
                            <span>Tokopedia</span>
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" name="platform[]" value="Website Sendiri" {{ is_array(old('platform')) && in_array('Website Sendiri', old('platform')) ? 'checked' : '' }}>
                            <span>Website Sendiri</span>
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" name="platform[]" value="Lainnya" {{ is_array(old('platform')) && in_array('Lainnya', old('platform')) ? 'checked' : '' }}>
                            <span>Lainnya</span>
                        </label>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label>Pendapatan perbulan<span class="required">*</span></label>
                    <div class="radio-group-vertical">
                        <label class="radio-label">
                            <input type="radio" name="pendapatan" value="1 juta s/d 10 juta" {{ old('pendapatan') == '1 juta s/d 10 juta' ? 'checked' : '' }} required>
                            <span>1 juta s/d 10 juta</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="pendapatan" value="10 juta s/d 100 juta" {{ old('pendapatan') == '10 juta s/d 100 juta' ? 'checked' : '' }} required>
                            <span>10 juta s/d 100 juta</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="pendapatan" value="100 juta s/d 500 juta" {{ old('pendapatan') == '100 juta s/d 500 juta' ? 'checked' : '' }} required>
                            <span>100 juta s/d 500 juta</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="pendapatan" value="500 juta s/d 1 miliar" {{ old('pendapatan') == '500 juta s/d 1 miliar' ? 'checked' : '' }} required>
                            <span>500 juta s/d 1 miliar</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="pendapatan" value="Lebih dari 1 miliar" {{ old('pendapatan') == 'Lebih dari 1 miliar' ? 'checked' : '' }} required>
                            <span>Lebih dari 1 miliar</span>
                        </label>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label>Apakah Bapak/Ibu sudah menerima pembiayaan (pinjaman) untuk usaha?</label>
                    <div class="radio-group-vertical">
                        <label class="radio-label">
                            <input type="radio" name="pembiayaan" value="Ya" {{ old('pembiayaan') == 'Ya' ? 'checked' : '' }}>
                            <span>Ya</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="pembiayaan" value="Tidak" {{ old('pembiayaan') == 'Tidak' ? 'checked' : '' }}>
                            <span>Tidak</span>
                        </label>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label>Sumber Pembiayaan (Jika ada)</label>
                    <div class="radio-group-vertical">
                        <label class="radio-label">
                            <input type="radio" name="sumber_pembiayaan" value="Bank" {{ old('sumber_pembiayaan') == 'Bank' ? 'checked' : '' }}>
                            <span>Bank</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="sumber_pembiayaan" value="Fintech" {{ old('sumber_pembiayaan') == 'Fintech' ? 'checked' : '' }}>
                            <span>Fintech</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="sumber_pembiayaan" value="Koperasi" {{ old('sumber_pembiayaan') == 'Koperasi' ? 'checked' : '' }}>
                            <span>Koperasi</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="sumber_pembiayaan" value="Lainnya" {{ old('sumber_pembiayaan') == 'Lainnya' ? 'checked' : '' }}>
                            <span>Lainnya</span>
                        </label>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label>Tujuan mengikuti program ini<span class="required">*</span></label>
                    <div class="radio-group-vertical">
                        <label class="radio-label">
                            <input type="radio" name="tujuan" value="Meningkatkan penjualan online" {{ old('tujuan') == 'Meningkatkan penjualan online' ? 'checked' : '' }} required>
                            <span>Meningkatkan penjualan online</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="tujuan" value="Mendapatkan akses pembiayaan" {{ old('tujuan') == 'Mendapatkan akses pembiayaan' ? 'checked' : '' }} required>
                            <span>Mendapatkan akses pembiayaan</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="tujuan" value="Meningkatkan literasi keuangan" {{ old('tujuan') == 'Meningkatkan literasi keuangan' ? 'checked' : '' }} required>
                            <span>Meningkatkan literasi keuangan</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="tujuan" value="Mencari mitra atau jaringan" {{ old('tujuan') == 'Mencari mitra atau jaringan' ? 'checked' : '' }} required>
                            <span>Mencari mitra atau jaringan</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="tujuan" value="Lainnya" {{ old('tujuan') == 'Lainnya' ? 'checked' : '' }} required>
                            <span>Lainnya</span>
                        </label>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label for="pelatihan">Pelatihan yang dibutuhkan<span class="required">*</span></label>
                    <textarea id="pelatihan" name="pelatihan" class="form-control" rows="4" placeholder="Tuliskan pelatihan yang Anda butuhkan" required>{{ old('pelatihan') }}</textarea>
                </div>

                <div class="form-actions-two">
                    <button type="button" class="btn-secondary btn-prev" data-prev="2">Sebelumnya</button>
                    <button type="submit" class="btn-submit">Selesai</button>
                </div>
            </div>
        </form>
    </div>
</section>

<style>
/* Alert Styles */
.alert {
    display: flex;
    align-items: flex-start;
    padding: 16px 20px;
    margin-bottom: 24px;
    border-radius: 8px;
    font-size: 14px;
    line-height: 1.5;
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.alert svg {
    flex-shrink: 0;
    margin-right: 12px;
    margin-top: 2px;
}

.alert-success {
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}

.alert-success svg {
    stroke: #28a745;
}

.alert-error {
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
}

.alert-error svg {
    stroke: #dc3545;
}

.alert strong {
    font-weight: 600;
    display: block;
    margin-bottom: 8px;
}

.alert ul {
    margin: 0;
    padding-left: 20px;
}

.alert ul li {
    margin-bottom: 4px;
}

.alert ul li:last-child {
    margin-bottom: 0;
}

/* Checkbox Styles */
.checkbox-group-vertical {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-top: 8px;
}

.checkbox-label {
    display: flex;
    align-items: center;
    cursor: pointer;
    font-size: 16px;
    color: #04293B;
}

.checkbox-label input[type="checkbox"] {
    width: 20px;
    height: 20px;
    margin-right: 10px;
    cursor: pointer;
    accent-color: #10B981;
}

.checkbox-label span {
    user-select: none;
}

/* Field validation styles */
.field-invalid {
    border: 2px solid #ff4444 !important;
    background-color: #fff5f5 !important;
}

.field-invalid:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(255, 68, 68, 0.1) !important;
}
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Next buttons with validation
        const nextButtons = document.querySelectorAll('.btn-next');
        nextButtons.forEach(function(btn) {
            btn.addEventListener('click', function() {
                const currentStep = this.closest('.form-step').getAttribute('data-step');
                const nextStep = this.getAttribute('data-next');

                // Validate current step before moving to next
                if (validateStep(currentStep)) {
                    goToStep(parseInt(nextStep));
                }
            });
        });

        // Previous buttons
        const prevButtons = document.querySelectorAll('.btn-prev');
        prevButtons.forEach(function(btn) {
            btn.addEventListener('click', function() {
                const prevStep = this.getAttribute('data-prev');
                goToStep(parseInt(prevStep));
            });
        });

        // Custom Alert Function
        function showCustomAlert(title, fields) {
            const overlay = document.createElement('div');
            overlay.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 9999;
                animation: fadeIn 0.3s ease;
            `;

            const modal = document.createElement('div');
            modal.style.cssText = `
                background: white;
                border-radius: 12px;
                padding: 30px;
                max-width: 500px;
                width: 90%;
                max-height: 80vh;
                overflow-y: auto;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
                animation: slideUp 0.3s ease;
            `;

            let fieldsHTML = '';
            fields.forEach(function(field, index) {
                fieldsHTML += `
                    <li style="
                        padding: 10px 0;
                        border-bottom: ${index < fields.length - 1 ? '1px solid #f0f0f0' : 'none'};
                        color: #666;
                    ">
                        <span style="
                            display: inline-block;
                            width: 24px;
                            height: 24px;
                            background: #ff4444;
                            color: white;
                            border-radius: 50%;
                            text-align: center;
                            line-height: 24px;
                            margin-right: 10px;
                            font-size: 12px;
                            font-weight: bold;
                        ">!</span>
                        ${field}
                    </li>
                `;
            });

            modal.innerHTML = `
                <div style="text-align: center; margin-bottom: 20px;">
                    <div style="
                        width: 60px;
                        height: 60px;
                        background: #fff3cd;
                        border-radius: 50%;
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        margin-bottom: 15px;
                    ">
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#ff9800" stroke-width="2">
                            <path d="M10.29 3.86L1.82 18a2 2<path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                            <line x1="12" y1="9" x2="12" y2="13"></line>
                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                        </svg>
                    </div>
                    <h3 style="
                        margin: 0 0 10px 0;
                        color: #333;
                        font-size: 22px;
                        font-weight: 600;
                    ">${title}</h3>
                    <p style="
                        margin: 0;
                        color: #666;
                        font-size: 14px;
                    ">Mohon lengkapi field berikut untuk melanjutkan:</p>
                </div>
                <ul style="
                    list-style: none;
                    padding: 0;
                    margin: 20px 0;
                    max-height: 300px;
                    overflow-y: auto;
                ">
                    ${fieldsHTML}
                </ul>
                <div style="text-align: center; margin-top: 25px;">
                    <button id="closeAlertBtn" style="
                        background: #007bff;
                        color: white;
                        border: none;
                        padding: 12px 40px;
                        border-radius: 6px;
                        font-size: 16px;
                        font-weight: 500;
                        cursor: pointer;
                        transition: all 0.3s ease;
                    " onmouseover="this.style.background='#0056b3'" onmouseout="this.style.background='#007bff'">
                        Mengerti
                    </button>
                </div>
            `;

            overlay.appendChild(modal);
            document.body.appendChild(overlay);

            // Add animations
            const style = document.createElement('style');
            style.textContent = `
                @keyframes fadeIn {
                    from { opacity: 0; }
                    to { opacity: 1; }
                }
                @keyframes slideUp {
                    from { transform: translateY(50px); opacity: 0; }
                    to { transform: translateY(0); opacity: 1; }
                }
            `;
            document.head.appendChild(style);

            // Close button event
            document.getElementById('closeAlertBtn').addEventListener('click', function() {
                overlay.style.animation = 'fadeIn 0.3s ease reverse';
                setTimeout(function() {
                    document.body.removeChild(overlay);
                    document.head.removeChild(style);
                }, 300);
            });

            // Close on overlay click
            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) {
                    overlay.style.animation = 'fadeIn 0.3s ease reverse';
                    setTimeout(function() {
                        document.body.removeChild(overlay);
                        document.head.removeChild(style);
                    }, 300);
                }
            });
        }

        function validateStep(step) {
            const currentStepElement = document.querySelector('.form-step[data-step="' + step + '"]');
            const requiredInputs = currentStepElement.querySelectorAll('input[required], select[required], textarea[required]');
            const emptyFields = [];
            const processedRadioGroups = [];
            const processedCheckboxGroups = [];

            requiredInputs.forEach(function(input) {
                const label = currentStepElement.querySelector('label[for="' + input.id + '"]');
                let fieldName = label ? label.textContent.replace('*', '').trim() : input.name;

                if (input.type === 'radio') {
                    const radioName = input.name;

                    // Skip if we've already processed this radio group
                    if (processedRadioGroups.indexOf(radioName) !== -1) {
                        return;
                    }

                    processedRadioGroups.push(radioName);

                    const radioGroup = currentStepElement.querySelectorAll('input[name="' + radioName + '"]');
                    const isChecked = Array.from(radioGroup).some(function(radio) {
                        return radio.checked;
                    });

                    if (!isChecked) {
                        // Find the label for radio group
                        const radioContainer = input.closest('.form-group');
                        const radioLabel = radioContainer ? radioContainer.querySelector('label:not(.radio-label)') : null;
                        const radioFieldName = radioLabel ? radioLabel.textContent.replace('*', '').trim() : radioName;
                        emptyFields.push(radioFieldName);

                        // Add visual feedback
                        if (radioContainer) {
                            radioContainer.style.border = '2px solid #ff4444';
                            radioContainer.style.borderRadius = '8px';
                            radioContainer.style.padding = '10px';
                        }
                    } else {
                        // Remove visual feedback if checked
                        const radioContainer = input.closest('.form-group');
                        if (radioContainer) {
                            radioContainer.style.border = '';
                            radioContainer.style.borderRadius = '';
                            radioContainer.style.padding = '';
                        }
                    }
                } else if (input.type === 'checkbox') {
                    // For checkbox groups, check if at least one is checked
                    const checkboxName = input.name;
                    
                    // Skip if we've already processed this checkbox group
                    if (processedCheckboxGroups.indexOf(checkboxName) !== -1) {
                        return;
                    }

                    processedCheckboxGroups.push(checkboxName);

                    const checkboxGroup = currentStepElement.querySelectorAll('input[name="' + checkboxName + '"]');
                    const isAnyChecked = Array.from(checkboxGroup).some(function(checkbox) {
                        return checkbox.checked;
                    });

                    if (!isAnyChecked) {
                        const checkboxContainer = input.closest('.form-group');
                        const checkboxLabel = checkboxContainer ? checkboxContainer.querySelector('label:not(.checkbox-label)') : null;
                        const checkboxFieldName = checkboxLabel ? checkboxLabel.textContent.replace('*', '').trim() : checkboxName;
                        emptyFields.push(checkboxFieldName);

                        if (checkboxContainer) {
                            checkboxContainer.style.border = '2px solid #ff4444';
                            checkboxContainer.style.borderRadius = '8px';
                            checkboxContainer.style.padding = '10px';
                        }
                    } else {
                        const checkboxContainer = input.closest('.form-group');
                        if (checkboxContainer) {
                            checkboxContainer.style.border = '';
                            checkboxContainer.style.borderRadius = '';
                            checkboxContainer.style.padding = '';
                        }
                    }
                } else if (input.tagName === 'SELECT') {
                    if (!input.value || input.value === '') {
                        emptyFields.push(fieldName);
                        input.classList.add('field-invalid');
                    } else {
                        input.classList.remove('field-invalid');
                    }
                } else {
                    if (!input.value || !input.value.trim()) {
                        emptyFields.push(fieldName);
                        input.classList.add('field-invalid');
                    } else {
                        input.classList.remove('field-invalid');
                    }
                }
            });

            if (emptyFields.length > 0) {
                const stepTitles = {
                    '1': 'Data Usaha Belum Lengkap',
                    '2': 'Data Pribadi Belum Lengkap',
                    '3': 'Data Lainnya Belum Lengkap'
                };

                showCustomAlert(stepTitles[step] || 'Form Belum Lengkap', emptyFields);

                // Scroll to first invalid field
                const firstInvalid = currentStepElement.querySelector('.field-invalid, [style*="border: 2px solid #ff4444"]');
                if (firstInvalid) {
                    firstInvalid.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }

                return false;
            }

            // Remove all visual feedback if validation passes
            currentStepElement.querySelectorAll('.field-invalid').forEach(function(el) {
                el.classList.remove('field-invalid');
            });
            currentStepElement.querySelectorAll('[style*="border: 2px solid"]').forEach(function(el) {
                el.style.border = '';
                el.style.borderRadius = '';
                el.style.padding = '';
            });

            return true;
        }

        function goToStep(stepNumber) {
            // Hide all steps
            const allSteps = document.querySelectorAll('.form-step');
            allSteps.forEach(function(step) {
                step.classList.remove('active');
            });

            // Show target step
            const targetStep = document.querySelector('.form-step[data-step="' + stepNumber + '"]');
            if (targetStep) {
                targetStep.classList.add('active');
            }

            // Update stepper
            updateStepper(stepNumber);

            // Scroll to top
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function updateStepper(currentStep) {
            // Update stepper items
            const stepperItems = document.querySelectorAll('.stepper-item');
            stepperItems.forEach(function(item, index) {
                const stepNumber = index + 1;
                item.classList.remove('active', 'completed');

                if (stepNumber < currentStep) {
                    item.classList.add('completed');
                } else if (stepNumber === currentStep) {
                    item.classList.add('active');
                }
            });

            // Update stepper lines
            const stepperLines = document.querySelectorAll('.stepper-line');
            stepperLines.forEach(function(line, index) {
                const lineNumber = index + 1;
                line.classList.remove('completed');

                if (lineNumber < currentStep) {
                    line.classList.add('completed');
                }
            });
        }
    });
</script>
@endsection