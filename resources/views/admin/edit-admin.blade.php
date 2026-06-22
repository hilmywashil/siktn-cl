{{-- resources/views/admin/edit-admin.blade.php --}}
@extends('admin.layouts.admin-layout')

@section('title', 'Edit Admin')
@section('page-title', 'Edit Admin')

@php
$activeMenu = 'info-admin';
@endphp

@push('styles')
<style>
    .page-header {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
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
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
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

    .category-radio:checked+.category-label {
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
        <span class="breadcrumb-current">Edit Admin</span>
    </div>
    <h1 class="page-title">Edit Admin</h1>
    <p class="page-desc">Perbarui informasi administrator {{ $admin->name }}</p>
</div>

<form action="{{ route('admin.update-admin', $admin) }}" method="POST">
    @csrf
    @method('PUT')

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
                            value="{{ old('name', $admin->name) }}"
                            placeholder="Masukkan nama lengkap"
                            required>
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
                            value="{{ old('email', $admin->email) }}"
                            placeholder="contoh@email.com"
                            required>
                        @error('email')
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
                            value="{{ old('username', $admin->username) }}"
                            placeholder="Masukkan username"
                            required>
                        <span class="form-help">Username akan digunakan untuk login</span>
                        @error('username')
                        <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
<div class="form-group full-width">
    <label class="form-label required">Kategori Admin</label>
    <div class="category-options" style="grid-template-columns: 1fr;">
        <div class="category-option">
            <input 
                type="radio" 
                id="category-super-admin" 
                name="category" 
                value="super_admin" 
                class="category-radio"
                checked
                required
            >
            <label for="category-super-admin" class="category-label">
                <span class="category-name">Super Admin</span>
                <span class="category-desc">Admin Pusat (Akses Penuh ke Semua Fitur)</span>
            </label>
        </div>
    </div>
    @error('category')
        <span class="form-error">{{ $message }}</span>
    @enderror
    
    {{-- Hidden input untuk memastikan value super_admin terkirim --}}
    <input type="hidden" name="category" value="super_admin">
</div>

{{-- HAPUS/HIDE BAGIAN DOMISILI KARENA TIDAK DIPAKAI --}}
                    <!-- ========================================== -->
                    <!-- FORM EDIT ADMIN - Domisili Field -->
                    <!-- ========================================== -->
                    <div class="form-group full-width" id="domisiliField">
                        <label class="form-label required">Domisili</label>
                        <select
                            name="domisili"
                            class="form-select @error('domisili') error @enderror">
                            <option value="">Pilih Domisili</option>

                            {{-- KABUPATEN (18) --}}
                            <optgroup label="Kabupaten">
                                <option value="Bandung" {{ old('domisili', $admin->domisili) == 'Bandung' ? 'selected' : '' }}>Kabupaten Bandung</option>
                                <option value="Bandung Barat" {{ old('domisili', $admin->domisili) == 'Bandung Barat' ? 'selected' : '' }}>Kabupaten Bandung Barat</option>
                                <option value="Bekasi" {{ old('domisili', $admin->domisili) == 'Bekasi' ? 'selected' : '' }}>Kabupaten Bekasi</option>
                                <option value="Bogor" {{ old('domisili', $admin->domisili) == 'Bogor' ? 'selected' : '' }}>Kabupaten Bogor</option>
                                <option value="Ciamis" {{ old('domisili', $admin->domisili) == 'Ciamis' ? 'selected' : '' }}>Kabupaten Ciamis</option>
                                <option value="Cianjur" {{ old('domisili', $admin->domisili) == 'Cianjur' ? 'selected' : '' }}>Kabupaten Cianjur</option>
                                <option value="Cirebon" {{ old('domisili', $admin->domisili) == 'Cirebon' ? 'selected' : '' }}>Kabupaten Cirebon</option>
                                <option value="Garut" {{ old('domisili', $admin->domisili) == 'Garut' ? 'selected' : '' }}>Kabupaten Garut</option>
                                <option value="Indramayu" {{ old('domisili', $admin->domisili) == 'Indramayu' ? 'selected' : '' }}>Kabupaten Indramayu</option>
                                <option value="Karawang" {{ old('domisili', $admin->domisili) == 'Karawang' ? 'selected' : '' }}>Kabupaten Karawang</option>
                                <option value="Kuningan" {{ old('domisili', $admin->domisili) == 'Kuningan' ? 'selected' : '' }}>Kabupaten Kuningan</option>
                                <option value="Majalengka" {{ old('domisili', $admin->domisili) == 'Majalengka' ? 'selected' : '' }}>Kabupaten Majalengka</option>
                                <option value="Pangandaran" {{ old('domisili', $admin->domisili) == 'Pangandaran' ? 'selected' : '' }}>Kabupaten Pangandaran</option>
                                <option value="Purwakarta" {{ old('domisili', $admin->domisili) == 'Purwakarta' ? 'selected' : '' }}>Kabupaten Purwakarta</option>
                                <option value="Subang" {{ old('domisili', $admin->domisili) == 'Subang' ? 'selected' : '' }}>Kabupaten Subang</option>
                                <option value="Sukabumi" {{ old('domisili', $admin->domisili) == 'Sukabumi' ? 'selected' : '' }}>Kabupaten Sukabumi</option>
                                <option value="Sumedang" {{ old('domisili', $admin->domisili) == 'Sumedang' ? 'selected' : '' }}>Kabupaten Sumedang</option>
                                <option value="Tasikmalaya" {{ old('domisili', $admin->domisili) == 'Tasikmalaya' ? 'selected' : '' }}>Kabupaten Tasikmalaya</option>
                            </optgroup>

                            {{-- KOTA (9) --}}
                            <optgroup label="Kota">
                                <option value="Kota Bandung" {{ old('domisili', $admin->domisili) == 'Kota Bandung' ? 'selected' : '' }}>Kota Bandung</option>
                                <option value="Kota Banjar" {{ old('domisili', $admin->domisili) == 'Kota Banjar' ? 'selected' : '' }}>Kota Banjar</option>
                                <option value="Kota Bekasi" {{ old('domisili', $admin->domisili) == 'Kota Bekasi' ? 'selected' : '' }}>Kota Bekasi</option>
                                <option value="Kota Bogor" {{ old('domisili', $admin->domisili) == 'Kota Bogor' ? 'selected' : '' }}>Kota Bogor</option>
                                <option value="Kota Cimahi" {{ old('domisili', $admin->domisili) == 'Kota Cimahi' ? 'selected' : '' }}>Kota Cimahi</option>
                                <option value="Kota Cirebon" {{ old('domisili', $admin->domisili) == 'Kota Cirebon' ? 'selected' : '' }}>Kota Cirebon</option>
                                <option value="Kota Depok" {{ old('domisili', $admin->domisili) == 'Kota Depok' ? 'selected' : '' }}>Kota Depok</option>
                                <option value="Kota Sukabumi" {{ old('domisili', $admin->domisili) == 'Kota Sukabumi' ? 'selected' : '' }}>Kota Sukabumi</option>
                                <option value="Kota Tasikmalaya" {{ old('domisili', $admin->domisili) == 'Kota Tasikmalaya' ? 'selected' : '' }}>Kota Tasikmalaya</option>
                            </optgroup>
                        </select>
                        <span class="form-help">Pilih domisili untuk admin BPC</span>
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
                        <label class="form-label">Password Baru</label>
                        <div class="password-wrapper">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-input @error('password') error @enderror"
                                placeholder="Kosongkan jika tidak ingin mengubah">
                            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                <svg id="eye-password" viewBox="0 0 24 24">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </button>
                        </div>
                        <span class="form-help">Kosongkan jika tidak ingin mengubah password</span>
                        @error('password')
                        <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <div class="password-wrapper">
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="form-input"
                                placeholder="Ulangi password baru">
                            <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                                <svg id="eye-password_confirmation" viewBox="0 0 24 24">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                    <circle cx="12" cy="12" r="3" />
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
                    <polyline points="20 6 9 17 4 12" />
                </svg>
                Simpan Perubahan
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
        const categoryBPC = document.getElementById('category-bpc');
        const domisiliField = document.getElementById('domisiliField');

        if (categoryBPC && categoryBPC.checked) {
            domisiliField.style.display = 'flex';
            domisiliField.querySelector('select').required = true;
        } else {
            domisiliField.style.display = 'none';
            domisiliField.querySelector('select').required = false;
            domisiliField.querySelector('select').value = '';
        }
    }

    // Jalankan saat halaman load untuk handle old() values
    document.addEventListener('DOMContentLoaded', function() {
        toggleDomisili();
    });
</script>
@endpush