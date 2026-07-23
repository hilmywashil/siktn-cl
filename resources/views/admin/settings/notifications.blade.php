@extends('admin.layouts.admin-layout')

@section('title', 'Pengaturan Notifikasi - SIKTN Admin')
@section('page-title', 'Pengaturan - Preferensi Notifikasi')

@push('styles')
<style>
    .card-box {
        background: white;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        padding: 1.75rem;
        max-width: 800px;
    }

    .setting-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.25rem 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .setting-item:last-child {
        border-bottom: none;
    }

    .setting-info {
        flex: 1;
        padding-right: 1.5rem;
    }

    .setting-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #0a2540;
        margin-bottom: 0.25rem;
    }

    .setting-desc {
        font-size: 0.8125rem;
        color: #6b7280;
        line-height: 1.5;
    }

    /* Modern Toggle Switch */
    .switch {
        position: relative;
        display: inline-block;
        width: 48px;
        height: 26px;
        flex-shrink: 0;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #d1d5db;
        transition: .3s;
        border-radius: 34px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .3s;
        border-radius: 50%;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }

    input:checked + .slider {
        background-color: #0a2540;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #0a2540;
    }

    input:checked + .slider:before {
        transform: translateX(22px);
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s;
        border: 1px solid transparent;
        text-decoration: none;
    }

    .btn-primary {
        background: #0a2540;
        color: white;
    }

    .btn-primary:hover {
        background: #0d3154;
    }
</style>
@endpush

@section('content')
<div style="padding: 0.5rem 0;">
    <div class="card-box">
        <div style="margin-bottom: 1.5rem; border-bottom: 2px solid #f3f4f6; padding-bottom: 1rem;">
            <h3 style="color: #0a2540; margin: 0 0 0.375rem 0; font-weight: 700;">Pengaturan Preferensi Notifikasi</h3>
            <p style="color: #6b7280; margin: 0; font-size: 0.875rem;">
                Tentukan jenis notifikasi yang ingin Anda terima pada Lonceng Header Topbar SIKTN.
            </p>
        </div>

        <form action="{{ route('admin.settings.notifications.update') }}" method="POST">
            @csrf

            {{-- 1. Surat Pending TTD --}}
            <div class="setting-item">
                <div class="setting-info">
                    <div class="setting-title">Pemberitahuan Surat Pending TTD</div>
                    <div class="setting-desc">Tampilkan notifikasi saat ada dokumen surat baru yang membutuhkan tanda tangan atau persetujuan Pimpinan.</div>
                </div>
                <label class="switch">
                    <input type="checkbox" name="surat_pending" {{ ($settings['surat_pending'] ?? true) ? 'checked' : '' }}>
                    <span class="slider"></span>
                </label>
            </div>

            {{-- 2. Pengingat Masa Berlaku SK --}}
            <div class="setting-item">
                <div class="setting-info">
                    <div class="setting-title">Pengingat Masa Berlaku SK (H-180 / 6 Bulan)</div>
                    <div class="setting-desc">Tampilkan peringatan otomatis 6 bulan sebelum masa berlaku Surat Keputusan (SK) berakhir.</div>
                </div>
                <label class="switch">
                    <input type="checkbox" name="sk_expired" {{ ($settings['sk_expired'] ?? true) ? 'checked' : '' }}>
                    <span class="slider"></span>
                </label>
            </div>

            {{-- 3. Notifikasi Anggota Baru --}}
            <div class="setting-item">
                <div class="setting-info">
                    <div class="setting-title">Notifikasi Pendaftaran Anggota Baru</div>
                    <div class="setting-desc">Tampilkan pemberitahuan ketika ada pendaftaran atau pembaruan profil anggota Karang Taruna baru.</div>
                </div>
                <label class="switch">
                    <input type="checkbox" name="new_anggota" {{ ($settings['new_anggota'] ?? true) ? 'checked' : '' }}>
                    <span class="slider"></span>
                </label>
            </div>

            {{-- 4. Notifikasi E-Katalog Baru --}}
            <div class="setting-item">
                <div class="setting-info">
                    <div class="setting-title">Notifikasi Pengajuan E-Katalog Baru</div>
                    <div class="setting-desc">Tampilkan pemberitahuan saat ada pengajuan produk baru di E-Katalog Karang Taruna.</div>
                </div>
                <label class="switch">
                    <input type="checkbox" name="new_katalog" {{ ($settings['new_katalog'] ?? true) ? 'checked' : '' }}>
                    <span class="slider"></span>
                </label>
            </div>

            {{-- 5. Auto-Open Dropdown saat Login --}}
            <div class="setting-item">
                <div class="setting-info">
                    <div class="setting-title">Pop-up Meluncur Otomatis Saat Login</div>
                    <div class="setting-desc">Biarkan dropdown notifikasi meluncur terbuka secara otomatis selama 5 detik ketika Anda baru pertama kali login.</div>
                </div>
                <label class="switch">
                    <input type="checkbox" name="auto_open_login" {{ ($settings['auto_open_login'] ?? true) ? 'checked' : '' }}>
                    <span class="slider"></span>
                </label>
            </div>

            <div style="margin-top: 2rem; display: flex; justify-content: flex-end;">
                <button type="submit" class="btn btn-primary" onclick="Toast.fire({ icon: 'success', title: 'Menyimpan preferensi notifikasi...' })">
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
