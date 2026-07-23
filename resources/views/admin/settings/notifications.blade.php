@extends('admin.layouts.admin-layout')

@section('title', 'Pengaturan Notifikasi - SIKTN Admin')
@section('page-title', 'Pengaturan - Preferensi Notifikasi')

@push('styles')
<style>
    .admin-ui-scope {
        --navy: #022648;
        --navy-dark: #01162f;
        --navy-light: #0a3a6b;
        --gold: #b7830f;
        --green: #059669;
        --blue: #2563eb;
        --red: #dc2626;
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-300: #d1d5db;
        --gray-500: #6b7280;
        --gray-700: #374151;
        --gray-900: #111827;
        --radius-sm: 4px;
        --radius-md: 6px;
        --radius-lg: 8px;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    /* Summary Stat Cards */
    .stat-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
        gap: 1.25rem;
        margin-bottom: 1.75rem;
    }

    .stat-card {
        background: #ffffff;
        border-radius: var(--radius-lg);
        padding: 1.25rem;
        border: 1px solid var(--gray-200);
        box-shadow: 0 1px 3px rgba(2, 38, 72, 0.05);
        display: flex;
        align-items: center;
        gap: 1rem;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 4px;
        background: var(--navy);
    }
    .stat-card.alert::before { background: var(--gold); }
    .stat-card.active::before { background: var(--green); }

    .stat-icon {
        width: 44px; height: 44px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        background: var(--gray-100);
        color: var(--navy);
    }

    .stat-info h4 {
        margin: 0;
        font-size: 0.75rem;
        color: var(--gray-500);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-info .value {
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--navy);
        margin-top: 0.2rem;
        font-family: 'Montserrat', sans-serif;
    }

    /* Container Box */
    .card-box {
        background: white;
        border-radius: var(--radius-lg);
        border: 1px solid var(--gray-200);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        padding: 1.75rem;
        width: 100%;
    }

    .setting-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.25rem 0;
        border-bottom: 1px solid var(--gray-100);
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
        color: var(--navy);
        margin-bottom: 0.25rem;
    }

    .setting-desc {
        font-size: 0.8125rem;
        color: var(--gray-500);
        line-height: 1.5;
    }

    /* Modern Toggle Switch */
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
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
        background-color: var(--gray-300);
        transition: .3s cubic-bezier(0.4, 0, 0.2, 1);
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
        transition: .3s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 50%;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }

    input:checked + .slider {
        background-color: var(--navy);
    }

    input:focus + .slider {
        box-shadow: 0 0 0 3px rgba(2, 38, 72, 0.15);
    }

    input:checked + .slider:before {
        transform: translateX(24px);
    }

    .btn-solid-navy {
        background: var(--navy);
        color: white;
        padding: 0.65rem 1.35rem;
        border-radius: var(--radius-md);
        font-weight: 600;
        font-size: 0.875rem;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 4px rgba(2, 38, 72, 0.12);
    }

    .btn-solid-navy:hover {
        background: var(--navy-light);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(2, 38, 72, 0.2);
    }
</style>
@endpush

@section('content')
<div class="admin-ui-scope" style="padding-top: 0.5rem;">

    <!-- Stat Cards Top Benchmark -->
    <div class="stat-cards-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
            </div>
            <div class="stat-info">
                <h4>Total Saluran Notifikasi</h4>
                <div class="value">5</div>
            </div>
        </div>

        <div class="stat-card alert">
            <div class="stat-icon" style="background: #fffbeb; color: #b7830f;">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            </div>
            <div class="stat-info">
                <h4>Alert SK (H-180)</h4>
                <div class="value">Otomatis</div>
            </div>
        </div>

        <div class="stat-card active">
            <div class="stat-icon" style="background: #ecfdf5; color: #059669;">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            </div>
            <div class="stat-info">
                <h4>Lonceng Topbar SIKTN</h4>
                <div class="value">Aktif</div>
            </div>
        </div>
    </div>

    <!-- Main Setting Box Card -->
    <div class="card-box">
        <div style="margin-bottom: 1.5rem; border-bottom: 2px solid var(--gray-100); padding-bottom: 1rem; display: flex; align-items: center; gap: 10px;">
            <div style="width: 36px; height: 36px; border-radius: 8px; background: var(--gray-100); color: var(--navy); display: flex; align-items: center; justify-content: center;">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
            </div>
            <div>
                <h3 style="color: var(--navy); margin: 0 0 0.2rem 0; font-weight: 700;">Pengaturan Preferensi Notifikasi</h3>
                <p style="color: var(--gray-500); margin: 0; font-size: 0.85rem;">
                    Tentukan jenis pemberitahuan yang ingin Anda terima pada Lonceng Header Topbar SIKTN.
                </p>
            </div>
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
                <button type="submit" class="btn-solid-navy" onclick="Toast.fire({ icon: 'success', title: 'Preferensi notifikasi berhasil disimpan...' })">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
