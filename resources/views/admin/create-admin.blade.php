{{-- resources/views/admin/create-admin.blade.php --}}
@extends('admin.layouts.admin-layout')

@section('title', 'Tambah Admin')
@section('page-title', 'Tambah Admin')

@php
    $activeMenu = 'info-admin';
@endphp

@push('styles')
<style>
    .page-header {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
        font-size: 0.875rem;
    }

    .breadcrumb a {
        color: #6b7280;
        text-decoration: none;
        transition: color 0.2s;
    }

    .breadcrumb a:hover {
        color: #0a2540;
    }

    .breadcrumb-separator {
        color: #d1d5db;
    }

    .breadcrumb-current {
        color: #0a2540;
        font-weight: 600;
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #0a2540;
        margin-bottom: 0.5rem;
    }

    .page-desc {
        color: #6b7280;
        font-size: 0.9375rem;
    }

    .form-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .form-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .form-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #0a2540;
        margin-bottom: 0.25rem;
    }

    .form-subtitle {
        font-size: 0.875rem;
        color: #6b7280;
    }

    .form-body {
        padding: 2rem;
    }

    .form-section {
        margin-bottom: 2rem;
    }

    .form-section:last-child {
        margin-bottom: 0;
    }

    .section-title {
        font-size: 1rem;
        font-weight: 700;
        color: #0a2540;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #f3f4f6;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .form-label.required::after {
        content: " *";
        color: #dc2626;
    }

    .form-input {
        padding: 0.75rem 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.9375rem;
        font-family: 'Montserrat', sans-serif;
        transition: all 0.2s;
    }

    .form-input:focus {
        outline: none;
        border-color: #ffd700;
        box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.1);
    }

    .form-input.error {
        border-color: #dc2626;
    }

    .form-select {
        padding: 0.75rem 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.9375rem;
        font-family: 'Montserrat', sans-serif;
        transition: all 0.2s;
        background: white;
        cursor: pointer;
    }

    .form-select:focus {
        outline: none;
        border-color: #ffd700;
        box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.1);
    }

    .form-help {
        font-size: 0.8125rem;
        color: #6b7280;
        margin-top: 0.375rem;
    }

    .form-error {
        font-size: 0.8125rem;
        color: #dc2626;
        margin-top: 0.375rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .password-wrapper {
        position: relative;
    }

    .password-toggle {
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        padding: 0.5rem;
        color: #6b7280;
        transition: color 0.2s;
    }

    .password-toggle:hover {
        color: #374151;
    }

    .password-toggle svg {
        width: 18px;
        height: 18px;
        stroke: currentColor;
        fill: none;
        stroke-width: 2;
    }

    .category-options {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    .category-option {
        position: relative;
    }

    .category-radio {
        position: absolute;
        opacity: 0;
    }

    .category-label {
        display: flex;
        flex-direction: column;
        padding: 1.25rem;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        height: 100%;
    }

    .category-radio:checked + .category-label {
        border-color: #ffd700;
        background: rgba(255, 215, 0, 0.05);
    }

    .category-name {
        font-size: 1rem;
        font-weight: 700;
        color: #0a2540;
        margin-bottom: 0.25rem;
    }

    .category-desc {
        font-size: 0.8125rem;
        color: #6b7280;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        padding: 1.5rem 2rem;
        border-top: 1px solid #e5e7eb;
        background: #f9fafb;
    }

    .btn-cancel {
        background: white;
        color: #374151;
        border: 1px solid #e5e7eb;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.2s;
        font-family: 'Montserrat', sans-serif;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }

    .btn-cancel:hover {
        background: #f3f4f6;
    }

    .btn-submit {
        background: #0a2540;
        color: white;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.2s;
        font-family: 'Montserrat', sans-serif;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-submit:hover {
        background: #ffd700;
        color: #0a2540;
    }

    .btn-submit svg {
        width: 18px;
        height: 18px;
        stroke: currentColor;
        fill: none;
        stroke-width: 2;
    }

    #domisiliField {
        display: none;
    }

    @media (max-width: 1024px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .category-options {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .btn-cancel,
        .btn-submit {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 768px) {
        .category-options {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span class="breadcrumb-separator">›</span>
        <a href="{{ route('admin.info-admin') }}">Info Admin</a>
        <span class="breadcrumb-separator">›</span>
        <span class="breadcrumb-current">Tambah Admin</span>
    </div>
    <h1 class="page-title">Tambah Admin Baru</h1>
    <p class="page-desc">Lengkapi formulir di bawah untuk menambahkan administrator baru ke sistem Karang Taruna</p>
</div>

<form action="{{ route('admin.store-admin') }}" method="POST">
    @csrf
    
    <div class="form-container">
        <div class="form-header">
            <h3 class="form-title">Informasi Admin</h3>
            <p class="form-subtitle">Data yang ditandai dengan (*) wajib diisi</p>
        </div>

        <div class="form-body">
            <!-- Informasi Pribadi -->
            <div class="form-section">
                <h4 class="section-title">Informasi Pribadi</h4>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label required">Nama Lengkap</label>
                        <input 
                            type="text" 
                            name="name" 
                            class="form-input @error('name') error @enderror" 
                            value="{{ old('name') }}"
                            placeholder="Masukkan nama lengkap"
                            required
                        >
                        @error('name')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Email</label>
                        <input 
                            type="email" 
                            name="email" 
                            class="form-input @error('email') error @enderror" 
                            value="{{ old('email') }}"
                            placeholder="contoh@email.com"
                            required
                        >
                        @error('email')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group full-width" style="margin-top: 1rem;">
                        <label class="form-label">No. WhatsApp / HP</label>
                        <input 
                            type="text" 
                            name="no_hp" 
                            class="form-input @error('no_hp') error @enderror" 
                            value="{{ old('no_hp') }}"
                            placeholder="Contoh: 081234567890"
                        >
                        <span class="form-help">Digunakan untuk notifikasi WhatsApp (opsional)</span>
                        @error('no_hp')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Informasi Akun -->
            <div class="form-section">
                <h4 class="section-title">Informasi Akun</h4>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label required">Username</label>
                        <input 
                            type="text" 
                            name="username" 
                            class="form-input @error('username') error @enderror" 
                            value="{{ old('username') }}"
                            placeholder="Masukkan username"
                            required
                        >
                        <span class="form-help">Username akan digunakan untuk login</span>
                        @error('username')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group full-width">
    <label class="form-label required">Kategori Admin</label>
    <div class="category-options" style="grid-template-columns: repeat(3, 1fr);">
        <div class="category-option">
            <input type="radio" id="category-super-admin" name="category" value="super_admin" class="category-radio" onchange="toggleDomisili()" required>
            <label for="category-super-admin" class="category-label">
                <span class="category-name">Super Admin</span>
                <span class="category-desc">Akses penuh ke semua sistem</span>
            </label>
        </div>
        <div class="category-option">
            <input type="radio" id="category-pimpinan" name="category" value="pimpinan" class="category-radio" onchange="toggleDomisili()" required>
            <label for="category-pimpinan" class="category-label">
                <span class="category-name">Pimpinan</span>
                <span class="category-desc">Laporan tingkat pimpinan</span>
            </label>
        </div>
        <div class="category-option">
            <input type="radio" id="category-pnkt" name="category" value="pnkt" class="category-radio" onchange="toggleDomisili()" required>
            <label for="category-pnkt" class="category-label">
                <span class="category-name">Sekretariat Nasional</span>
                <span class="category-desc">Admin Tingkat Pusat (PNKT)</span>
            </label>
        </div>
        <div class="category-option">
            <input type="radio" id="category-ppkt" name="category" value="ppkt" class="category-radio" onchange="toggleDomisili()" required>
            <label for="category-ppkt" class="category-label">
                <span class="category-name">Sekretariat Provinsi</span>
                <span class="category-desc">Admin Tingkat Provinsi (PPKT)</span>
            </label>
        </div>
        <div class="category-option">
            <input type="radio" id="category-pkkt" name="category" value="pkkt" class="category-radio" onchange="toggleDomisili()" required>
            <label for="category-pkkt" class="category-label">
                <span class="category-name">Sekretariat Kab/Kota</span>
                <span class="category-desc">Admin Tingkat Daerah (PKKT)</span>
            </label>
        </div>
    </div>
    @error('category')
        <span class="form-error">{{ $message }}</span>
    @enderror
</div>

<!-- ========================================== -->
<!-- FORM CREATE ADMIN - Domisili Field -->
<!-- ========================================== -->
<div class="form-group full-width" id="provinsiField" style="display: none;">
    <label class="form-label required">Pilih Provinsi *</label>
    <select 
        name="domisili" 
        id="provinsiSelect"
        class="form-select @error('domisili') error @enderror"
        style="width: 100%;"
        data-old-value="{{ old('domisili') }}"
    >
        <option value="">-- Pilih Provinsi --</option>
    </select>
    <span class="form-help">Pilih wilayah untuk admin Sekretariat Provinsi</span>
    @error('domisili')
        <span class="form-error">{{ $message }}</span>
    @enderror
</div>

<div class="form-group full-width" id="kabKotaField" style="display: none;">
    <label class="form-label required">Pilih Kabupaten/Kota *</label>
    <select 
        name="domisili" 
        id="kabKotaSelect"
        class="form-select @error('domisili') error @enderror"
        style="width: 100%;"
        data-old-value="{{ old('domisili') }}"
    >
        <option value="">-- Pilih Kabupaten/Kota --</option>
    </select>
    <span class="form-help">Pilih wilayah untuk admin Sekretariat Kab/Kota</span>
    @error('domisili')
        <span class="form-error">{{ $message }}</span>
    @enderror
</div>
                </div>
            </div>

            <!-- Keamanan -->
            <div class="form-section">
                <h4 class="section-title">Keamanan</h4>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label required">Password</label>
                        <div class="password-wrapper">
                            <input 
                                type="password" 
                                id="password"
                                name="password" 
                                class="form-input @error('password') error @enderror"
                                placeholder="Masukkan password"
                                required
                            >
                            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                <svg id="eye-password" viewBox="0 0 24 24">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Konfirmasi Password</label>
                        <div class="password-wrapper">
                            <input 
                                type="password" 
                                id="password_confirmation"
                                name="password_confirmation" 
                                class="form-input"
                                placeholder="Ulangi password"
                                required
                            >
                            <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                                <svg id="eye-password_confirmation" viewBox="0 0 24 24">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                        <span class="form-help">Masukkan password yang sama untuk konfirmasi</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.info-admin') }}" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-submit">
                <svg viewBox="0 0 24 24">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
                Simpan Admin
            </button>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    function togglePassword(fieldId) {
        const input = document.getElementById(fieldId);
        const eye = document.getElementById('eye-' + fieldId);
        
        if (input.type === 'password') {
            input.type = 'text';
            eye.innerHTML = `
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                <line x1="1" y1="1" x2="23" y2="23"/>
            `;
        } else {
            input.type = 'password';
            eye.innerHTML = `
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                <circle cx="12" cy="12" r="3"/>
            `;
        }
    }

    function toggleDomisili() {
        const categoryPPKT = document.getElementById('category-ppkt');
        const categoryPKKT = document.getElementById('category-pkkt');
        const provinsiField = document.getElementById('provinsiField');
        const kabKotaField = document.getElementById('kabKotaField');
        const provinsiSelect = document.getElementById('provinsiSelect');
        const kabKotaSelect = document.getElementById('kabKotaSelect');
        
        if (categoryPPKT && categoryPPKT.checked) {
            provinsiField.style.display = 'flex';
            provinsiSelect.required = true;
            provinsiSelect.name = 'domisili';
            
            kabKotaField.style.display = 'none';
            kabKotaSelect.required = false;
            kabKotaSelect.name = ''; // prevent validation error & submission
            // don't clear value here so old() works if it was set
        } else if (categoryPKKT && categoryPKKT.checked) {
            kabKotaField.style.display = 'flex';
            kabKotaSelect.required = true;
            kabKotaSelect.name = 'domisili';
            
            provinsiField.style.display = 'none';
            provinsiSelect.required = false;
            provinsiSelect.name = ''; // prevent validation error & submission
            // don't clear value here so old() works if it was set
        } else {
            provinsiField.style.display = 'none';
            provinsiSelect.required = false;
            provinsiSelect.name = '';
            
            kabKotaField.style.display = 'none';
            kabKotaSelect.required = false;
            kabKotaSelect.name = '';
        }
    }

    // Jalankan saat halaman load untuk handle old() values
    document.addEventListener('DOMContentLoaded', function() {
        toggleDomisili();
        
        // Initialize Select2
        $('#provinsiSelect').select2({
            placeholder: '-- Pilih Provinsi --',
            allowClear: true,
            width: '100%'
        });
        
        $('#kabKotaSelect').select2({
            placeholder: '-- Pilih Kabupaten/Kota --',
            allowClear: true,
            width: '100%'
        });

        // Load Wilayah dari JSON
        const oldProv = $('#provinsiSelect').data('old-value');
        const oldKabKota = $('#kabKotaSelect').data('old-value');

        fetch("{{ asset('provinces.json') }}")
            .then(res => res.json())
            .then(data => {
                data.forEach(prov => {
                    const selected = (prov === oldProv && $('#category-ppkt').is(':checked')) ? 'selected' : '';
                    $('#provinsiSelect').append(`<option value="${prov}" ${selected}>${prov}</option>`);
                });
            });

        fetch("{{ asset('regencies.json') }}")
            .then(res => res.json())
            .then(data => {
                data.forEach(reg => {
                    const selected = (reg === oldKabKota && $('#category-pkkt').is(':checked')) ? 'selected' : '';
                    $('#kabKotaSelect').append(`<option value="${reg}" ${selected}>${reg}</option>`);
                });
            });
    });
</script>
@endpush