{{-- resources/views/admin/create-admin.blade.php --}}
@extends('admin.layouts.admin-layout')

@section('title', 'Tambah Anggota')
@section('page-title', 'Tambah Anggota')

@php
    $activeMenu = 'anggota';
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
        <a href="{{ route('admin.anggota.list') }}">Kelola Anggota</a>
        <span class="breadcrumb-separator">›</span>
        <span class="breadcrumb-current">Tambah Anggota</span>
    </div>
    <h1 class="page-title">Tambah Anggota Baru</h1>
    <p class="page-desc">Generate akun awal (username) agar anggota dapat login dan melengkapi profil mereka.</p>
</div>

{{-- Tombol Import --}}
<div style="margin-bottom: 1.5rem; display: flex; gap: 0.75rem; justify-content: flex-end;">
    <a href="{{ route('admin.anggota.download-template') }}" onclick="Toast.fire({ icon: 'success', title: 'Template berhasil diunduh!' })" style="background: #059669; color: white; padding: 0.625rem 1.25rem; border-radius: 8px; font-weight: 600; font-size: 0.875rem; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='#047857'" onmouseout="this.style.background='#059669'">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
        Download Template
    </a>
    <button type="button" onclick="openImportModal()" style="background: #0a2540; color: white; padding: 0.625rem 1.25rem; border-radius: 8px; font-weight: 600; font-size: 0.875rem; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; border: none; transition: all 0.2s;" onmouseover="this.style.background='#ffd700'; this.style.color='#0a2540'" onmouseout="this.style.background='#0a2540'; this.style.color='white'">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
        Import Excel
    </button>
</div>

{{-- Import Credentials Alert (dari import success) --}}
@if(session('import_credentials'))
<div style="margin-bottom: 2rem; padding: 1.5rem; background: #ffffff; border: 2px solid #059669; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <h3 style="color: #059669; margin: 0; font-weight: 700; font-size: 16px; display: flex; align-items: center; gap: 8px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            {{ session('success') }}
        </h3>
        <a href="{{ route('admin.anggota.export-credentials') }}" class="btn-submit" style="background: #059669; border: none; padding: 0.5rem 1rem; border-radius: 6px; color: white; cursor: pointer; font-weight: 500; display: inline-flex; align-items: center; gap: 6px; font-size: 13px; text-decoration: none;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
            Export CSV
        </a>
    </div>

    <div style="max-height: 300px; overflow-y: auto; border: 1px solid #e5e7eb; border-radius: 6px;">
        <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
            <thead style="background: #f9fafb; position: sticky; top: 0;">
                <tr>
                    <th style="padding: 10px 12px; text-align: left; border-bottom: 1px solid #e5e7eb; font-weight: 600; color: #374151;">No</th>
                    <th style="padding: 10px 12px; text-align: left; border-bottom: 1px solid #e5e7eb; font-weight: 600; color: #374151;">Username</th>
                    <th style="padding: 10px 12px; text-align: left; border-bottom: 1px solid #e5e7eb; font-weight: 600; color: #374151;">Password</th>
                </tr>
            </thead>
            <tbody>
                @foreach(session('import_credentials') as $i => $cred)
                <tr>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #f3f4f6;">{{ $i + 1 }}</td>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #f3f4f6; font-family: monospace;">{{ $cred['username'] }}</td>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #f3f4f6; font-family: monospace; font-weight: 600; color: #0a2540;">{{ $cred['password'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <p style="margin-top: 1rem; font-size: 12px; color: #6b7280;">
        <strong>Catatan:</strong> Simpan kredensial ini dengan aman. Password tidak bisa ditampilkan ulang.
    </p>
</div>
@endif

{{-- Error Alert --}}
@if(session('error'))
<div style="margin-bottom: 2rem; padding: 1rem 1.5rem; background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px;">
    <p style="color: #dc2626; margin: 0; font-size: 14px; display: flex; align-items: center; gap: 8px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
        {{ session('error') }}
    </p>
</div>
@endif

{{-- Single Account Success --}}
@if(session('created_credentials'))
<div style="margin-bottom: 2rem; padding: 1.5rem; background: #ffffff; border: 1px solid #e5e7eb; border-radius: 8px;">
    <h3 style="color: #111827; margin-bottom: 0.5rem; font-weight: 600; font-size: 16px;">
        Akun Berhasil Dibuat
    </h3>
    
    <textarea id="credentialText" readonly style="width: 100%; height: 60px; padding: 1rem; border-radius: 6px; border: 1px solid #d1d5db; margin-bottom: 1rem; font-family: monospace; font-size: 14px; background: #f9fafb; color: #111827; resize: none;">
Username: {{ session('created_credentials')['username'] }}
Password: {{ session('created_credentials')['password'] }}</textarea>
    
    <button type="button" onclick="copyCredentials()" class="btn-submit" style="background: #1f2937; border: none; padding: 0.5rem 1rem; border-radius: 6px; color: white; cursor: pointer; font-weight: 500; display: inline-flex; align-items: center; gap: 6px; font-size: 14px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
        Copy
    </button>
</div>
@endif

<form action="{{ route('admin.anggota.store') }}" method="POST">
    @csrf
    
    <div class="form-container">
        <div class="form-header">
            <h3 class="form-title">Informasi Akun Anggota</h3>
            <p class="form-subtitle">Data yang ditandai dengan (*) wajib diisi. Password akan dibuatkan secara otomatis oleh sistem.</p>
        </div>

        <div class="form-body">
            <!-- Informasi Akun -->
            <div class="form-section">
                <h4 class="section-title">Informasi Dasar</h4>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label required">Username</label>
                        <input 
                            type="text" 
                            name="username" 
                            id="input_username"
                            class="form-input @error('username') error @enderror" 
                            value="{{ old('username') }}"
                            placeholder="Masukkan username"
                            required
                        >
                        <span class="form-help">Hanya huruf, angka, dan underscore (_)</span>
                        @error('username')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Input Hidden untuk Email Otomatis karena dibutuhkan Controller -->
                    <input type="hidden" name="email" id="generated_email" value="{{ old('email') }}">

                    <div class="form-group">
                        <label class="form-label">Password Sementara</label>
                        <input 
                            type="text" 
                            name="password" 
                            id="generated_password"
                            class="form-input" 
                            value="{{ old('password') }}"
                            readonly
                            style="background-color: #f3f4f6; cursor: not-allowed; font-weight: bold; color: #0a2540;"
                        >
                        <span class="form-help">Password ini otomatis dibuatkan berdasarkan username</span>
                    </div>
                </div>
            </div>
            
            {{-- 
            @if(in_array(auth()->guard('admin')->user()->category, ['super_admin', 'pnkt', 'pimpinan']))
            <!-- Pemilihan Wilayah (Hanya untuk Super Admin/Pusat) -->
            <div class="form-section">
                <h4 class="section-title">Pemilihan Wilayah (Khusus Pusat)</h4>
                <div class="form-grid">
                    <div class="form-group full-width" style="margin-bottom: 1rem;">
                        <label class="form-label required">Tingkat Wilayah Anggota</label>
                        <div style="display: flex; gap: 1rem; margin-top: 0.5rem;">
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="radio" name="tingkat_wilayah" value="provinsi" id="tingkat_provinsi">
                                <span>Tingkat Provinsi</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="radio" name="tingkat_wilayah" value="kabkota" id="tingkat_kabkota">
                                <span>Tingkat Kabupaten/Kota</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group full-width" id="anggotaProvinsiField" style="display: none;">
                        <label class="form-label required">Pilih Provinsi</label>
                        <select name="domisili_provinsi" id="anggotaProvinsiSelect" class="form-select" style="width: 100%;">
                            <option value="">-- Pilih Provinsi --</option>
                        </select>
                    </div>

                    <div class="form-group full-width" id="anggotaKabKotaField" style="display: none;">
                        <label class="form-label required">Pilih Kabupaten/Kota</label>
                        <select name="domisili_kabkota" id="anggotaKabKotaSelect" class="form-select" style="width: 100%;">
                            <option value="">-- Pilih Kabupaten/Kota --</option>
                        </select>
                    </div>
                    
                    <!-- Hidden input to store the actual selected domisili to submit -->
                    <input type="hidden" name="domisili" id="finalDomisili" value="">
                </div>
            </div>
            @endif
            --}}
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.anggota.list') }}" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-submit">
                <svg viewBox="0 0 24 24">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
                Buat Akun Anggota
            </button>
        </div>
    </div>
</form>

{{-- Import Modal --}}
<div id="importModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div style="background: white; border-radius: 12px; width: 90%; max-width: 500px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); overflow: hidden;">
        <div style="background: #0a2540; color: white; padding: 1.25rem 1.5rem; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-size: 1.125rem; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                Import Anggota dari Excel
            </h3>
            <button type="button" onclick="closeImportModal()" style="background: none; border: none; color: white; cursor: pointer; padding: 0.5rem; opacity: 0.8; transition: opacity 0.2s;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>

        <form action="{{ route('admin.anggota.import') }}" method="POST" id="importForm" style="padding: 1.5rem;">
            @csrf
            <input type="hidden" name="usernames" id="usernamesData">
            <div style="margin-bottom: 1.5rem;">
                <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 1rem;">
                    Unggah file Excel (.xls, .xlsx, .csv) yang berisi daftar username anggota.
                    Sistem akan memunculkan preview datanya sebelum Anda menyimpan.
                </p>

                <div style="border: 2px dashed #d1d5db; border-radius: 8px; padding: 2rem; text-align: center; background: #f9fafb; transition: all 0.2s;" id="dropZone">
                    <input type="file" id="fileInput" accept=".xls,.xlsx,.csv" style="display: none;" onchange="handleFileSelect(this)">
                    <div id="dropZoneContent">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.5" style="margin: 0 auto 1rem;">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" y1="3" x2="12" y2="15"></line>
                        </svg>
                        <p style="color: #374151; font-size: 0.9375rem; font-weight: 500; margin: 0 0 0.5rem;">Drag & drop file di sini</p>
                        <p style="color: #9ca3af; font-size: 0.8125rem; margin: 0;">atau</p>
                        <button type="button" onclick="document.getElementById('fileInput').click()" style="background: #0a2540; color: white; border: none; padding: 0.5rem 1.25rem; border-radius: 6px; cursor: pointer; font-size: 0.875rem; font-weight: 600; margin-top: 0.75rem; transition: background 0.2s;">
                            Pilih File
                        </button>
                    </div>
                    <div id="filePreview" style="display: none;">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="1.5" style="margin: 0 auto 0.5rem;">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                        </svg>
                        <p id="fileName" style="color: #0a2540; font-size: 0.875rem; font-weight: 600; margin: 0;"></p>
                        
                        <!-- PREVIEW TABLE -->
                        <div style="margin-top: 1rem; max-height: 200px; overflow-y: auto; border: 1px solid #e5e7eb; border-radius: 6px; text-align: left;">
                            <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                                <thead style="background: #f9fafb; position: sticky; top: 0;">
                                    <tr>
                                        <th style="padding: 8px 12px; border-bottom: 1px solid #e5e7eb; font-weight: 600; color: #374151;">No</th>
                                        <th style="padding: 8px 12px; border-bottom: 1px solid #e5e7eb; font-weight: 600; color: #374151;">Username Ditemukan</th>
                                    </tr>
                                </thead>
                                <tbody id="previewTableBody">
                                </tbody>
                            </table>
                        </div>
                        <p id="previewError" style="color: #dc2626; font-size: 0.8125rem; margin-top: 0.5rem; display: none;"></p>
                        <button type="button" onclick="clearFile()" style="background: none; border: none; color: #dc2626; cursor: pointer; font-size: 0.8125rem; margin-top: 0.75rem; font-weight: 600;">Ganti File</button>
                    </div>
                </div>

                <p style="color: #9ca3af; font-size: 0.75rem; margin-top: 0.75rem;">
                    Format didukung: .xls, .xlsx, .csv (maks. 5MB)
                </p>

                @error('usernames')
                    <p style="color: #dc2626; font-size: 0.8125rem; margin-top: 0.5rem;">{{ $message }}</p>
                @enderror
            </div>

            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" onclick="closeImportModal()" style="background: white; color: #374151; border: 1px solid #d1d5db; padding: 0.625rem 1.25rem; border-radius: 6px; cursor: pointer; font-size: 0.875rem; font-weight: 600; transition: all 0.2s;">
                    Batal
                </button>
                <button type="submit" id="importBtn" disabled style="background: #0a2540; color: white; border: none; padding: 0.625rem 1.5rem; border-radius: 6px; cursor: pointer; font-size: 0.875rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; transition: all 0.2s; opacity: 0.5;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="8 17 12 21 16 17"></polyline><line x1="12" y1="12" x2="12" y2="21"></line><path d="M20.88 18.09A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.29"></path></svg>
                    Simpan ke Database
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const usernameInput = document.getElementById('input_username');
        const emailInput = document.getElementById('generated_email');
        const passwordInput = document.getElementById('generated_password');
        
        // Buat 4 angka acak yang nggak berubah waktu ngetik
        const randomSuffix = Math.floor(1000 + Math.random() * 9000);

        usernameInput.addEventListener('input', function(e) {
            // Hapus karakter yang bukan huruf (besar/kecil), angka, atau underscore
            let val = e.target.value.replace(/[^a-zA-Z0-9_]/g, '');
            e.target.value = val; 
            
            if (val) {
                emailInput.value = val.toLowerCase() + '@karangtaruna.org';
                
                // Gunakan username untuk generate password sementara
                let passBase = val.replace(/[^a-zA-Z0-9]/g, '');
                passwordInput.value = passBase + randomSuffix;
            } else {
                emailInput.value = '';
                passwordInput.value = '';
            }
        });
        
        /*
        @if(in_array(auth()->guard('admin')->user()->category, ['super_admin', 'pnkt', 'pimpinan']))
            // Initialize Select2
            $('#anggotaProvinsiSelect, #anggotaKabKotaSelect').select2({
                allowClear: true,
                width: '100%'
            });
            
            // Load Data
            fetch("{{ asset('provinces.json') }}").then(res => res.json()).then(data => {
                data.forEach(prov => $('#anggotaProvinsiSelect').append(`<option value="${prov}">${prov}</option>`));
            });
            fetch("{{ asset('regencies.json') }}").then(res => res.json()).then(data => {
                data.forEach(reg => $('#anggotaKabKotaSelect').append(`<option value="${reg}">${reg}</option>`));
            });
            
            // Toggle Logic
            $('input[name="tingkat_wilayah"]').change(function() {
                if (this.value === 'provinsi') {
                    $('#anggotaProvinsiField').show();
                    $('#anggotaKabKotaField').hide();
                    $('#anggotaKabKotaSelect').val('').trigger('change');
                } else {
                    $('#anggotaProvinsiField').hide();
                    $('#anggotaKabKotaField').show();
                    $('#anggotaProvinsiSelect').val('').trigger('change');
                }
            });
            
            // Set Final Domisili Input before submit
            $('#anggotaProvinsiSelect').change(function() {
                if($('input[name="tingkat_wilayah"]:checked').val() === 'provinsi') {
                    $('#finalDomisili').val($(this).val());
                }
            });
            $('#anggotaKabKotaSelect').change(function() {
                if($('input[name="tingkat_wilayah"]:checked').val() === 'kabkota') {
                    $('#finalDomisili').val($(this).val());
                }
            });
        @endif
        */
    });

    function copyCredentials() {
        var copyText = document.getElementById("credentialText");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value).then(function() {
            Swal.fire({
                title: 'Tersalin!',
                text: 'Kredensial berhasil disalin ke clipboard.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        }).catch(function() {
            document.execCommand("copy");
            Swal.fire({
                title: 'Tersalin!',
                text: 'Kredensial berhasil disalin.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        });
    }

    // Import Modal Functions
    function openImportModal() {
        document.getElementById('importModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeImportModal() {
        document.getElementById('importModal').style.display = 'none';
        document.body.style.overflow = 'auto';
        clearFile();
    }

    function handleFileSelect(input) {
        const file = input.files[0];
        if (file) {
            const btn = document.getElementById('importBtn');
            btn.disabled = true;
            btn.style.opacity = '0.5';
            document.getElementById('previewError').style.display = 'none';
            showFilePreview(file.name);
            
            const reader = new FileReader();
            reader.onload = function(e) {
                try {
                    const data = new Uint8Array(e.target.result);
                    const workbook = XLSX.read(data, {type: 'array'});
                    const firstSheetName = workbook.SheetNames[0];
                    const worksheet = workbook.Sheets[firstSheetName];
                    const json = XLSX.utils.sheet_to_json(worksheet, {header: 1});
                    
                    let usernames = [];
                    
                    // Super Aggressive Scanner: Scan SEMUA sel, abaikan struktur tabel
                    for (let i = 0; i < json.length; i++) {
                        let row = json[i];
                        if (!row) continue;
                        
                        for (let j = 0; j < row.length; j++) {
                            let val = String(row[j] || '').trim();
                            // Bersihkan karakter selain huruf, angka, dan underscore
                            val = val.replace(/[^a-zA-Z0-9_]/g, '');
                            
                            // Validasi:
                            // 1. Harus lebih dari 2 huruf (menghindari error bacaan "1t", dll)
                            // 2. Tidak boleh angka saja (menghindari No urut)
                            // 3. Bukan teks bawaan template
                            if (
                                val.length > 2 && 
                                isNaN(val) && 
                                val.toLowerCase() !== 'username' && 
                                val.toLowerCase().indexOf('template') === -1 &&
                                val.toLowerCase().indexOf('petunjuk') === -1 &&
                                val.toLowerCase().indexOf('isikolom') === -1 &&
                                val.toLowerCase().indexOf('password') === -1
                            ) {
                                usernames.push(val);
                            }
                        }
                    }
                    
                    // Hilangkan duplikat jika ada
                    usernames = [...new Set(usernames)];
                    
                    if (usernames.length === 0) {
                        document.getElementById('previewError').textContent = 'Tidak ada data username yang valid ditemukan dalam file.';
                        document.getElementById('previewError').style.display = 'block';
                        return;
                    }
                    
                    // Populate Table
                    const tbody = document.getElementById('previewTableBody');
                    tbody.innerHTML = '';
                    usernames.forEach((u, idx) => {
                        tbody.innerHTML += `<tr>
                            <td style="padding: 8px 12px; border-bottom: 1px solid #f3f4f6; text-align: center;">${idx + 1}</td>
                            <td style="padding: 8px 12px; border-bottom: 1px solid #f3f4f6; font-family: monospace;">${u}</td>
                        </tr>`;
                    });
                    
                    // Set hidden input
                    document.getElementById('usernamesData').value = JSON.stringify(usernames);
                    
                    // Enable submit button
                    btn.disabled = false;
                    btn.style.opacity = '1';
                    btn.innerHTML = `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="8 17 12 21 16 17"></polyline><line x1="12" y1="12" x2="12" y2="21"></line><path d="M20.88 18.09A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.29"></path></svg> Simpan ke Database (${usernames.length})`;
                    
                } catch (err) {
                    console.error(err);
                    document.getElementById('previewError').textContent = 'Gagal membaca file. Pastikan formatnya benar (.xls, .xlsx, .csv).';
                    document.getElementById('previewError').style.display = 'block';
                }
            };
            reader.readAsArrayBuffer(file);
        }
    }

    function showFilePreview(fileName) {
        document.getElementById('dropZoneContent').style.display = 'none';
        document.getElementById('filePreview').style.display = 'block';
        document.getElementById('fileName').textContent = fileName;
        document.getElementById('previewTableBody').innerHTML = '';
    }

    function clearFile() {
        document.getElementById('fileInput').value = '';
        document.getElementById('usernamesData').value = '';
        document.getElementById('dropZoneContent').style.display = 'block';
        document.getElementById('filePreview').style.display = 'none';
        
        const btn = document.getElementById('importBtn');
        btn.disabled = true;
        btn.style.opacity = '0.5';
        btn.innerHTML = `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="8 17 12 21 16 17"></polyline><line x1="12" y1="12" x2="12" y2="21"></line><path d="M20.88 18.09A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.29"></path></svg> Simpan ke Database`;
    }

    // Close modal when clicking outside
    document.getElementById('importModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeImportModal();
        }
    });

    // Drag and drop support
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.style.borderColor = '#0a2540';
            dropZone.style.background = '#f0f9ff';
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.style.borderColor = '#d1d5db';
            dropZone.style.background = '#f9fafb';
        }, false);
    });

    dropZone.addEventListener('drop', (e) => {
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            showFilePreview(files[0].name);
        }
    }, false);

    // Tampilkan notifikasi jika akun berhasil dibuat
    @if(session('created_credentials'))
        Swal.fire({
            title: 'Berhasil!',
            text: 'Akun anggota berhasil dibuat. Jangan lupa copy kredensialnya!',
            icon: 'success',
            confirmButtonText: 'OK',
            confirmButtonColor: '#0a2540'
        });
    @endif

    // Tampilkan notifikasi jika import berhasil
    @if(session('import_credentials'))
        Swal.fire({
            title: 'Import Berhasil!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonText: 'OK',
            confirmButtonColor: '#059669'
        });
    @endif
</script>
@endpush