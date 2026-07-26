@extends('layouts.app')

@section('title', 'Daftar Akun Member - Karang Taruna')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    :root {
        --navy: #022648;
        --navy-dark: #01162f;
        --navy-light: #0a3a6b;
        --gold: #b7830f;
        --gold-light: #ffd700;
        --green: #059669;
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-300: #d1d5db;
        --gray-600: #4b5563;
        --gray-800: #1f2937;
        --radius-md: 8px;
        --radius-lg: 12px;
    }

    @keyframes select2DropdownFadeIn {
        from { opacity: 0; transform: translateY(-6px) scale(0.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .member-register-hero {
        background: linear-gradient(135deg, var(--navy-dark) 0%, var(--navy) 100%);
        padding: 50px 20px 40px;
        text-align: center;
        color: #ffffff;
        position: relative;
        overflow: hidden;
    }

    .member-register-hero::before {
        content: '';
        position: absolute;
        top: -50%; right: -20%;
        width: 400px; height: 400px;
        background: radial-gradient(circle, rgba(183, 131, 15, 0.15) 0%, rgba(0,0,0,0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .member-register-hero h1 {
        font-size: 2rem;
        font-weight: 800;
        color: #ffffff;
        margin: 0 0 8px;
        letter-spacing: -0.5px;
    }

    .member-register-hero p {
        font-size: 0.95rem;
        color: rgba(255, 255, 255, 0.8);
        max-width: 600px;
        margin: 0 auto;
    }

    .register-card-container {
        max-width: 680px;
        margin: -30px auto 60px;
        padding: 0 20px;
        position: relative;
        z-index: 10;
    }

    .register-card {
        background: #ffffff;
        border-radius: var(--radius-lg);
        box-shadow: 0 12px 32px rgba(2, 38, 72, 0.12);
        border: 1px solid var(--gray-200);
        overflow: hidden;
    }

    .register-card-header {
        background: var(--navy);
        padding: 20px 28px;
        display: flex;
        align-items: center;
        gap: 16px;
        border-bottom: 3px solid var(--gold-light);
    }

    .register-header-icon {
        width: 48px; height: 48px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        color: var(--gold-light);
        font-size: 22px;
        flex-shrink: 0;
    }

    .register-header-text h3 {
        color: #ffffff;
        font-size: 1.1rem;
        font-weight: 700;
        margin: 0 0 3px;
    }

    .register-header-text p {
        color: rgba(255, 255, 255, 0.75);
        font-size: 0.8125rem;
        margin: 0;
    }

    .register-card-body {
        padding: 30px;
    }

    .invite-banner {
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        border-radius: var(--radius-md);
        padding: 14px 18px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
        color: #065f46;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .invite-banner svg {
        flex-shrink: 0;
        color: var(--green);
    }

    .form-group-title {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--navy);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 0 0 16px;
        padding-bottom: 8px;
        border-bottom: 2px solid var(--gray-100);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
        margin-bottom: 18px;
    }

    @media (max-width: 640px) {
        .form-row { grid-template-columns: 1fr; }
    }

    .form-label {
        display: block;
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--gray-800);
        margin-bottom: 6px;
    }

    .form-label span.req {
        color: #dc2626;
    }

    .form-input {
        width: 100%;
        padding: 0.65rem 0.85rem;
        font-size: 0.875rem;
        border: 1px solid var(--gray-300);
        border-radius: var(--radius-md);
        background: #ffffff;
        color: var(--gray-800);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        box-sizing: border-box;
    }

    .form-input:focus {
        outline: none;
        border-color: var(--navy);
        box-shadow: 0 0 0 3px rgba(2, 38, 72, 0.12);
    }

    /* Select2 Customization */
    .select2-container--default .select2-selection--single {
        height: 44px;
        padding: 0.4rem 0.75rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--navy);
        background-color: #ffffff;
        border: 1px solid var(--gray-300);
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        transition: all 0.2s ease;
    }

    .select2-container--default .select2-selection--single:hover {
        border-color: var(--navy);
    }

    .select2-dropdown {
        border: 1px solid var(--gray-300);
        border-radius: var(--radius-md);
        font-size: 0.875rem;
        z-index: 9999;
        box-shadow: 0 10px 25px rgba(2, 38, 72, 0.15);
        margin-top: 4px;
        overflow: hidden;
    }

    .select2-container--open .select2-dropdown {
        animation: select2DropdownFadeIn 0.2s ease forwards;
    }

    .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
        background-color: var(--navy) !important;
        color: #ffffff !important;
        font-weight: 600 !important;
    }

    .btn-submit-register {
        width: 100%;
        padding: 0.85rem;
        background: var(--navy);
        color: #ffffff;
        border: none;
        border-radius: var(--radius-md);
        font-size: 0.95rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 12px rgba(2, 38, 72, 0.2);
        margin-top: 10px;
    }

    .btn-submit-register:hover {
        background: var(--navy-light);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(2, 38, 72, 0.3);
    }

    /* Invalid Invite Screen */
    .invalid-invite-card {
        padding: 40px 30px;
        text-align: center;
    }

    .invalid-invite-icon {
        width: 72px; height: 72px;
        background: #fef2f2;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        color: #dc2626;
        margin: 0 auto 20px;
    }

    .invalid-invite-card h2 {
        font-size: 1.35rem;
        font-weight: 800;
        color: var(--navy);
        margin: 0 0 10px;
    }

    .invalid-invite-card p {
        font-size: 0.9rem;
        color: var(--gray-600);
        margin: 0 0 24px;
        line-height: 1.5;
    }

    .invalid-actions {
        display: flex;
        gap: 12px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-outline-navy {
        padding: 0.65rem 1.25rem;
        border: 1.5px solid var(--navy);
        color: var(--navy);
        border-radius: var(--radius-md);
        font-weight: 600;
        font-size: 0.875rem;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-outline-navy:hover {
        background: var(--navy);
        color: #ffffff;
    }
</style>
@endpush

@section('content')
<div class="member-register-hero">
    <h1>Pendaftaran Akun Anggota</h1>
    <p>Portal Sistem Informasi & Keanggotaan Karang Taruna Indonesia</p>
</div>

<div class="register-card-container">
    <div class="register-card">
        @if(!$isValidInvite)
            {{-- Tampilan Ketika Link Undangan Tidak Valid / Kadaluarsa --}}
            <div class="invalid-invite-card">
                <div class="invalid-invite-icon">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                </div>
                <h2>Link Pendaftaran Memerlukan Undangan Resmi</h2>
                <p>
                    Pendaftaran akun anggota Karang Taruna memerlukan **link undangan resmi** yang dibuat oleh Admin PNKT / Superadmin.
                    Link undangan yang Anda gunakan mungkin tidak valid, sudah kadaluarsa, atau telah mencapai batas penggunaan.
                </p>
                <div class="invalid-actions">
                    <a href="{{ route('anggota.login') }}" class="btn-submit-register" style="width: auto; padding: 0.65rem 1.5rem;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg>
                        Login Anggota
                    </a>
                    <a href="{{ route('home') }}" class="btn-outline-navy">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        @else
            {{-- Tampilan Form Pendaftaran Jika Token Undangan Valid --}}
            <div class="register-card-header">
                <div class="register-header-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                        <line x1="20" y1="8" x2="20" y2="14"></line>
                        <line x1="17" y1="11" x2="23" y2="11"></line>
                    </svg>
                </div>
                <div class="register-header-text">
                    <h3>Formulir Registrasi Akun Anggota</h3>
                    <p>Lengkapi data pendaftaran Anda dengan benar dan valid.</p>
                </div>
            </div>

            <div class="register-card-body">
                <div class="invite-banner">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <span>
                        Link Undangan Resmi Aktif (Berlaku s/d {{ $invite->expires_at ? $invite->expires_at->translatedFormat('d M Y H:i') : 'Aktif' }})
                    </span>
                </div>

                @if(session('error'))
                    <div style="background: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; padding: 12px 16px; border-radius: 8px; font-size: 0.85rem; margin-bottom: 20px; font-weight: 600;">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div style="background: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; padding: 12px 16px; border-radius: 8px; font-size: 0.85rem; margin-bottom: 20px;">
                        <ul style="margin: 0; padding-left: 18px;">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('member-register.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="token" value="{{ $invite->token }}">

                    <div class="form-group-title">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        Informasi Akun
                    </div>

                    <div class="form-row">
                        <div>
                            <label class="form-label" for="username">Username <span class="req">*</span></label>
                            <input type="text" id="username" name="username" class="form-input" value="{{ old('username') }}" placeholder="Masukkan username unik" required>
                        </div>
                        <div>
                            <label class="form-label" for="nama_lengkap">Nama Lengkap <span class="req">*</span></label>
                            <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-input" value="{{ old('nama_lengkap') }}" placeholder="Nama lengkap sesuai KTP" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div>
                            <label class="form-label" for="email">E-mail <span class="req">*</span></label>
                            <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}" placeholder="email@domain.com" required>
                        </div>
                        <div>
                            <label class="form-label" for="password">Password <span class="req">*</span></label>
                            <input type="password" id="password" name="password" class="form-input" placeholder="Minimal 6 karakter" required>
                        </div>
                    </div>

                    <div style="margin-top: 24px; margin-bottom: 24px;">
                        <label class="form-label" for="domisili">Pilih Wilayah / Domisili (Provinsi / Kab / Kota) <span class="req">*</span></label>
                        <select id="domisili" name="domisili" class="select2-basic" style="width: 100%;" required>
                            <option value="">-- Ketik untuk mencari provinsi / kabupaten / kota --</option>
                            @foreach($regencies as $reg)
                                <option value="{{ $reg }}" {{ old('domisili') == $reg ? 'selected' : '' }}>{{ $reg }}</option>
                            @endforeach
                        </select>
                        <span style="display: block; font-size: 0.78rem; color: var(--text-grey); margin-top: 6px;">
                            Verifikasi pendaftaran Anda akan diproses oleh Admin PPKT/PKKT Wilayah ini.
                        </span>
                    </div>

                    <button type="submit" class="btn-submit-register">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                        Daftarkan & Kirim Verifikasi
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof $ !== 'undefined' && $.fn.select2) {
            $('.select2-basic').select2({
                placeholder: '-- Ketik untuk mencari provinsi / kabupaten --',
                allowClear: true,
                width: '100%'
            });
        }
    });
</script>
@endpush
