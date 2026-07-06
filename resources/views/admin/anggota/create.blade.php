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
@endsection

@push('scripts')
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
</script>
@endpush