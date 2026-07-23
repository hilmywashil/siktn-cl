@extends('admin.layouts.admin-layout')

@section('title', 'Pengaturan Halaman Program')

@push('styles')
<style>
    /* ============================================
       SETTINGS PAGE - SIKTN Admin UI Benchmark
       ============================================ */
    .admin-ui-scope {
        --navy: #022648;
        --navy-dark: #01162f;
        --navy-light: #0a3a6b;
        --gold: #b7830f;
        --green: #059669;
        --green-light: #dcfce7;
        --blue: #2563eb;
        --blue-light: #e0f2fe;
        --red: #dc2626;
        --red-light: #fef2f2;
        --amber: #d97706;
        --amber-light: #fef3c7;
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
        --radius-xl: 12px;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    /* ============================================
       PAGE HEADER
       ============================================ */
    .admin-ui-scope .page-header {
        background: white;
        padding: 2rem;
        border-radius: var(--radius-xl);
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        margin-bottom: 1.5rem;
    }

    .admin-ui-scope .page-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--navy);
        margin-bottom: 0.25rem;
        font-family: 'Montserrat', sans-serif;
    }

    .admin-ui-scope .page-desc {
        color: var(--gray-500);
        font-size: 0.9375rem;
        margin: 0;
    }

    /* ============================================
       STAT CARDS - Benchmark Standard
       ============================================ */
    .admin-ui-scope .stat-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .admin-ui-scope .stat-card {
        background: white;
        border-radius: var(--radius-xl);
        padding: 1.125rem 1.25rem;
        border: 1px solid var(--gray-200);
        box-shadow: 0 1px 3px rgba(2, 38, 72, 0.05);
        display: flex;
        align-items: center;
        gap: 0.875rem;
        position: relative;
        overflow: hidden;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .admin-ui-scope .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(2, 38, 72, 0.1);
    }

    /* Left accent border - Benchmark: 4px */
    .admin-ui-scope .stat-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
    }

    .admin-ui-scope .stat-card.total::before { background: var(--navy); }
    .admin-ui-scope .stat-card.aktif::before { background: var(--green); }
    .admin-ui-scope .stat-card.nonaktif::before { background: var(--gray-300); }

    /* Icon Box - Benchmark: 42x42px */
    .admin-ui-scope .stat-icon {
        width: 42px;
        height: 42px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .admin-ui-scope .stat-card.total .stat-icon {
        background: var(--blue-light);
        color: var(--navy);
    }

    .admin-ui-scope .stat-card.aktif .stat-icon {
        background: var(--green-light);
        color: var(--green);
    }

    .admin-ui-scope .stat-card.nonaktif .stat-icon {
        background: var(--gray-100);
        color: var(--gray-500);
    }

    .admin-ui-scope .stat-icon svg {
        width: 20px;
        height: 20px;
        stroke: currentColor;
        fill: none;
        stroke-width: 2;
    }

    .admin-ui-scope .stat-info h4 {
        margin: 0;
        font-size: 0.7rem;
        color: var(--gray-500);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .admin-ui-scope .stat-info .value {
        font-size: 1.4rem;
        font-weight: 800;
        color: var(--navy);
        margin-top: 0.15rem;
        font-family: 'Montserrat', sans-serif;
        line-height: 1.2;
    }

    /* ============================================
       SETTINGS CONTAINER
       ============================================ */
    .admin-ui-scope .settings-wrapper {
        display: grid;
        grid-template-columns: 260px 1fr;
        gap: 1.5rem;
        align-items: start;
    }

    @media (max-width: 768px) {
        .admin-ui-scope .settings-wrapper {
            grid-template-columns: 1fr;
        }
    }

    /* ============================================
       SETTINGS TABS
       ============================================ */
    .admin-ui-scope .settings-sidebar {
        background: white;
        border-radius: var(--radius-xl);
        border: 1px solid var(--gray-200);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        padding: 1rem;
        position: sticky;
        top: 1.5rem;
    }

    .admin-ui-scope .settings-tab-btn {
        width: 100%;
        padding: 0.875rem 1rem;
        background: transparent;
        border: 1px solid transparent;
        border-radius: var(--radius-md);
        text-align: left;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.875rem;
        color: var(--gray-700);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        gap: 0.625rem;
    }

    .admin-ui-scope .settings-tab-btn svg {
        width: 18px;
        height: 18px;
        stroke: currentColor;
        fill: none;
        stroke-width: 2;
        flex-shrink: 0;
    }

    .admin-ui-scope .settings-tab-btn:hover {
        background: var(--gray-50);
        color: var(--navy);
    }

    .admin-ui-scope .settings-tab-btn.active {
        background: var(--navy);
        color: white;
        border-color: var(--navy);
    }

    .admin-ui-scope .tab-divider {
        height: 1px;
        background: var(--gray-200);
        margin: 0.75rem 0;
    }

    /* ============================================
       SETTINGS CONTENT
       ============================================ */
    .admin-ui-scope .settings-content {
        background: white;
        border-radius: var(--radius-xl);
        border: 1px solid var(--gray-200);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        padding: 2rem;
    }

    .admin-ui-scope .tab-pane {
        display: none;
    }

    .admin-ui-scope .tab-pane.active {
        display: block;
        animation: fadeIn 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ============================================
       SECTION TITLE
       ============================================ */
    .admin-ui-scope .section-title {
        font-size: 1.125rem;
        font-weight: 800;
        color: var(--navy);
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--gray-200);
        font-family: 'Montserrat', sans-serif;
    }

    /* ============================================
       FORM ELEMENTS - Benchmark Standard
       ============================================ */
    .admin-ui-scope .form-group {
        margin-bottom: 1.5rem;
    }

    .admin-ui-scope .form-label {
        display: block;
        font-weight: 600;
        font-size: 0.875rem;
        color: var(--gray-900);
        margin-bottom: 0.5rem;
    }

    .admin-ui-scope .form-input {
        width: 100%;
        padding: 0.75rem 1rem;
        background-color: var(--gray-50);
        border: 1px solid var(--gray-200);
        border-radius: var(--radius-md);
        font-family: inherit;
        font-size: 0.9375rem;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        color: var(--gray-900);
    }

    .admin-ui-scope .form-input:hover {
        border-color: var(--gray-300);
        background: white;
    }

    .admin-ui-scope .form-input:focus {
        outline: none;
        background-color: white;
        border-color: var(--blue);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .admin-ui-scope textarea.form-input {
        min-height: 120px;
        resize: vertical;
    }

    .admin-ui-scope .form-hint {
        font-size: 0.8125rem;
        color: var(--gray-500);
        margin-top: 0.5rem;
        font-style: italic;
    }

    /* Image Preview */
    .admin-ui-scope .image-preview {
        margin-bottom: 0.75rem;
    }

    .admin-ui-scope .image-preview img {
        max-width: 180px;
        border-radius: var(--radius-md);
        border: 1px solid var(--gray-200);
    }

    /* ============================================
       REPEATER ITEMS
       ============================================ */
    .admin-ui-scope .repeater-item {
        background: var(--gray-50);
        border: 1px solid var(--gray-200);
        padding: 1.5rem;
        border-radius: var(--radius-lg);
        margin-bottom: 1.25rem;
        position: relative;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .admin-ui-scope .repeater-item:hover {
        border-color: var(--gray-300);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .admin-ui-scope .btn-remove-item {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: var(--red-light);
        color: var(--red);
        border: none;
        padding: 0.5rem 0.875rem;
        border-radius: var(--radius-md);
        cursor: pointer;
        font-size: 0.8125rem;
        font-weight: 600;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
    }

    .admin-ui-scope .btn-remove-item:hover {
        background: var(--red);
        color: white;
        transform: scale(1.02);
    }

    .admin-ui-scope .btn-remove-item svg {
        width: 14px;
        height: 14px;
        stroke: currentColor;
        fill: none;
        stroke-width: 2;
    }

    .admin-ui-scope .btn-add-item {
        background: var(--green-light);
        color: var(--green);
        border: 2px dashed #86efac;
        padding: 1rem 1.5rem;
        border-radius: var(--radius-lg);
        cursor: pointer;
        font-weight: 600;
        font-size: 0.875rem;
        width: 100%;
        margin-bottom: 2rem;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .admin-ui-scope .btn-add-item:hover {
        background: #bbf7d0;
        border-color: #4ade80;
        transform: translateY(-1px);
    }

    .admin-ui-scope .btn-add-item svg {
        width: 18px;
        height: 18px;
        stroke: currentColor;
        fill: none;
        stroke-width: 2;
    }

    .admin-ui-scope .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
    }

    @media (max-width: 640px) {
        .admin-ui-scope .grid-2 {
            grid-template-columns: 1fr;
        }
    }

    /* ============================================
       BUTTONS - Benchmark Standard
       ============================================ */
    .admin-ui-scope .btn-primary {
        background: var(--navy);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: var(--radius-md);
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        border: none;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(2, 38, 72, 0.12);
    }

    .admin-ui-scope .btn-primary:hover {
        background: var(--navy-light);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(2, 38, 72, 0.2);
        color: white;
    }

    .admin-ui-scope .btn-primary svg {
        width: 18px;
        height: 18px;
        stroke: currentColor;
        fill: none;
        stroke-width: 2;
    }

    /* ============================================
       SUMMERNOTE CUSTOMIZATION
       ============================================ */
    .admin-ui-scope .note-editor.note-frame {
        border-color: var(--gray-200);
        border-radius: var(--radius-md);
        overflow: hidden;
    }

    .admin-ui-scope .note-toolbar {
        background-color: var(--gray-50);
        border-bottom: 1px solid var(--gray-200);
    }

    .admin-ui-scope .note-btn {
        border-radius: var(--radius-sm) !important;
    }

    .admin-ui-scope .note-btn:hover {
        background: var(--gray-100) !important;
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="admin-ui-scope" style="padding-top: 0.5rem;">

    <!-- PAGE HEADER -->
    <div class="page-header">
        <h1 class="page-title">Pengaturan Halaman Program</h1>
        <p class="page-desc">Kelola teks statis untuk halaman publik Program CSR dan Program Bidang.</p>
    </div>

    <!-- STAT CARDS -->
    <div class="stat-cards-grid">
        <div class="stat-card total">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
            </div>
            <div class="stat-info">
                <h4>Total Program</h4>
                <div class="value">-</div>
            </div>
        </div>
        <div class="stat-card aktif">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            </div>
            <div class="stat-info">
                <h4>Aktif</h4>
                <div class="value">-</div>
            </div>
        </div>
        <div class="stat-card nonaktif">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
            </div>
            <div class="stat-info">
                <h4>Nonaktif</h4>
                <div class="value">-</div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.program.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="settings-wrapper">
            <!-- SETTINGS TABS SIDEBAR -->
            <div class="settings-sidebar">
                <button type="button" class="settings-tab-btn active" onclick="switchTab('csr')">
                    <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
                    Halaman CSR
                </button>
                <button type="button" class="settings-tab-btn" onclick="switchTab('bidang')">
                    <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    Halaman Bidang
                </button>

                <div class="tab-divider"></div>

                <button type="submit" class="btn-primary" style="width: 100%;">
                    <svg viewBox="0 0 24 24">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                        <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
            
            <!-- CSR TAB -->
            <div id="tab-csr" class="tab-pane active">
                <h2 class="section-title">Bagian Header / Banner (CSR)</h2>
                <div class="form-group">
                    <label class="form-label">Judul Banner</label>
                    <input type="text" name="csr_banner_title" class="form-input" value="{{ \App\Models\PageSetting::getVal('csr_banner_title') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi Banner</label>
                    <textarea name="csr_banner_desc" class="form-input">{{ \App\Models\PageSetting::getVal('csr_banner_desc') }}</textarea>
                </div>

                <h2 class="section-title" style="margin-top: 2.5rem;">Bagian Tentang Program (CSR)</h2>
                <div class="form-group">
                    <label class="form-label">Foto / Gambar (Maks 5MB)</label>
                    @if(\App\Models\PageSetting::getVal('csr_about_image'))
                        <div style="margin-bottom: 0.5rem;">
                            <img src="{{ asset('storage/' . \App\Models\PageSetting::getVal('csr_about_image')) }}" alt="Preview" style="max-width: 200px; border-radius: 8px;">
                        </div>
                    @endif
                    <input type="file" name="csr_about_image" class="form-input" accept="image/png, image/jpeg, image/webp">
                    <span class="form-hint">Biarkan kosong jika tidak ingin mengubah gambar saat ini.</span>
                </div>
                <div class="form-group">
                    <label class="form-label">Judul Tentang</label>
                    <input type="text" name="csr_about_title" class="form-input" value="{{ \App\Models\PageSetting::getVal('csr_about_title') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi Tentang (Paragraf 1)</label>
                    <textarea name="csr_about_desc1" class="form-input">{{ \App\Models\PageSetting::getVal('csr_about_desc1') }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi Tentang (Paragraf Lanjutan) HTML Supported</label>
                    <textarea name="csr_about_desc2" class="form-input summernote">{{ \App\Models\PageSetting::getVal('csr_about_desc2') }}</textarea>
                </div>

                <h2 class="section-title" style="margin-top: 2.5rem;">Bagian Tujuan Program (CSR)</h2>
                <div id="csr-tujuan-container">
                    @php
                        $csrTujuan = json_decode(\App\Models\PageSetting::getVal('csr_tujuan_json', '[]'), true) ?? [];
                    @endphp
                    @foreach($csrTujuan as $index => $item)
                    <div class="repeater-item">
                        <button type="button" class="btn-remove-item" onclick="this.parentElement.remove()">
                            <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            Hapus
                        </button>
                        <div class="form-group">
                            <label class="form-label">Judul Tujuan</label>
                            <input type="text" name="csr_tujuan_json[{{ $index }}][title]" class="form-input" value="{{ $item['title'] ?? '' }}">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="csr_tujuan_json[{{ $index }}][desc]" class="form-input" style="min-height: 80px;">{{ $item['desc'] ?? '' }}</textarea>
                        </div>
                    </div>
                    @endforeach
                </div>
                <button type="button" class="btn-add-item" onclick="addRepeaterItem('csr-tujuan-container', 'csr_tujuan_json')">
                    <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Tambah Tujuan CSR
                </button>

                <h2 class="section-title" style="margin-top: 2.5rem;">Bagian Fokus Program (CSR)</h2>
                <div id="csr-fokus-container">
                    @php
                        $csrFokus = json_decode(\App\Models\PageSetting::getVal('csr_fokus_json', '[]'), true) ?? [];
                    @endphp
                    @foreach($csrFokus as $index => $item)
                    <div class="repeater-item">
                        <button type="button" class="btn-remove-item" onclick="this.parentElement.remove()">
                            <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            Hapus
                        </button>
                        <div class="grid-2">
                            <div class="form-group">
                                <label class="form-label">Judul Fokus</label>
                                <input type="text" name="csr_fokus_json[{{ $index }}][title]" class="form-input" value="{{ $item['title'] ?? '' }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Icon (Maks 2MB, Rekomendasi: 64x64 pixel)</label>
                                @if(isset($item['icon']) && $item['icon'])
                                    <div style="margin-bottom: 0.5rem; padding: 0.5rem; background: #f3f4f6; border-radius: 6px; display: inline-block;">
                                        <img src="{{ str_starts_with($item['icon'], 'page_settings/') ? asset('storage/' . $item['icon']) : asset($item['icon']) }}" alt="Icon" style="width: 32px; height: 32px; object-fit: contain;">
                                    </div>
                                @endif
                                <input type="hidden" name="csr_fokus_json[{{ $index }}][old_icon]" value="{{ $item['icon'] ?? '' }}">
                                <input type="file" name="csr_fokus_json[{{ $index }}][icon]" class="form-input" accept="image/png, image/svg+xml">
                                <span class="form-hint">Format PNG/SVG. Biarkan kosong jika tidak ingin mengubah icon saat ini.</span>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Deskripsi (HTML Supported)</label>
                            <textarea name="csr_fokus_json[{{ $index }}][desc]" class="form-input summernote-mini">{{ $item['desc'] ?? '' }}</textarea>
                        </div>
                    </div>
                    @endforeach
                </div>
                <button type="button" class="btn-add-item" onclick="addRepeaterItemWithIcon('csr-fokus-container', 'csr_fokus_json')">
                    <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Tambah Fokus CSR
                </button>
            </div>

            <!-- BIDANG TAB -->
            <div id="tab-bidang" class="tab-pane">
                <h2 class="section-title">Bagian Header / Banner (Bidang)</h2>
                <div class="form-group">
                    <label class="form-label">Judul Banner</label>
                    <input type="text" name="bidang_banner_title" class="form-input" value="{{ \App\Models\PageSetting::getVal('bidang_banner_title') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi Banner</label>
                    <textarea name="bidang_banner_desc" class="form-input">{{ \App\Models\PageSetting::getVal('bidang_banner_desc') }}</textarea>
                </div>

                <h2 class="section-title" style="margin-top: 2.5rem;">Bagian Tentang Program (Bidang)</h2>
                <div class="form-group">
                    <label class="form-label">Foto / Gambar (Maks 5MB)</label>
                    @if(\App\Models\PageSetting::getVal('bidang_about_image'))
                        <div style="margin-bottom: 0.5rem;">
                            <img src="{{ asset('storage/' . \App\Models\PageSetting::getVal('bidang_about_image')) }}" alt="Preview" style="max-width: 200px; border-radius: 8px;">
                        </div>
                    @endif
                    <input type="file" name="bidang_about_image" class="form-input" accept="image/png, image/jpeg, image/webp">
                    <span class="form-hint">Biarkan kosong jika tidak ingin mengubah gambar saat ini.</span>
                </div>
                <div class="form-group">
                    <label class="form-label">Judul Tentang</label>
                    <input type="text" name="bidang_about_title" class="form-input" value="{{ \App\Models\PageSetting::getVal('bidang_about_title') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi Tentang (Paragraf 1)</label>
                    <textarea name="bidang_about_desc1" class="form-input">{{ \App\Models\PageSetting::getVal('bidang_about_desc1') }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi Tentang (Paragraf Lanjutan) HTML Supported</label>
                    <textarea name="bidang_about_desc2" class="form-input summernote">{{ \App\Models\PageSetting::getVal('bidang_about_desc2') }}</textarea>
                </div>
                <h2 class="section-title" style="margin-top: 2.5rem;">Bagian Tujuan Program (Bidang)</h2>
                <div id="bidang-tujuan-container">
                    @php
                        $bidangTujuan = json_decode(\App\Models\PageSetting::getVal('bidang_tujuan_json', '[]'), true) ?? [];
                    @endphp
                    @foreach($bidangTujuan as $index => $item)
                    <div class="repeater-item">
                        <button type="button" class="btn-remove-item" onclick="this.parentElement.remove()">
                            <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            Hapus
                        </button>
                        <div class="form-group">
                            <label class="form-label">Judul Tujuan</label>
                            <input type="text" name="bidang_tujuan_json[{{ $index }}][title]" class="form-input" value="{{ $item['title'] ?? '' }}">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="bidang_tujuan_json[{{ $index }}][desc]" class="form-input" style="min-height: 80px;">{{ $item['desc'] ?? '' }}</textarea>
                        </div>
                    </div>
                    @endforeach
                </div>
                <button type="button" class="btn-add-item" onclick="addRepeaterItem('bidang-tujuan-container', 'bidang_tujuan_json')">
                    <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Tambah Tujuan Bidang
                </button>

                <h2 class="section-title" style="margin-top: 2.5rem;">Bagian Fokus Program (Bidang)</h2>
                <div id="bidang-fokus-container">
                    @php
                        $bidangFokus = json_decode(\App\Models\PageSetting::getVal('bidang_fokus_json', '[]'), true) ?? [];
                    @endphp
                    @foreach($bidangFokus as $index => $item)
                    <div class="repeater-item">
                        <button type="button" class="btn-remove-item" onclick="this.parentElement.remove()">
                            <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            Hapus
                        </button>
                        <div class="grid-2">
                            <div class="form-group">
                                <label class="form-label">Judul Fokus</label>
                                <input type="text" name="bidang_fokus_json[{{ $index }}][title]" class="form-input" value="{{ $item['title'] ?? '' }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Icon (Maks 2MB, Rekomendasi: 64x64 pixel)</label>
                                @if(isset($item['icon']) && $item['icon'])
                                    <div style="margin-bottom: 0.5rem; padding: 0.5rem; background: #f3f4f6; border-radius: 6px; display: inline-block;">
                                        <img src="{{ str_starts_with($item['icon'], 'page_settings/') ? asset('storage/' . $item['icon']) : asset($item['icon']) }}" alt="Icon" style="width: 32px; height: 32px; object-fit: contain;">
                                    </div>
                                @endif
                                <input type="hidden" name="bidang_fokus_json[{{ $index }}][old_icon]" value="{{ $item['icon'] ?? '' }}">
                                <input type="file" name="bidang_fokus_json[{{ $index }}][icon]" class="form-input" accept="image/png, image/svg+xml">
                                <span class="form-hint">Format PNG/SVG. Biarkan kosong jika tidak ingin mengubah icon saat ini.</span>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Deskripsi (HTML Supported)</label>
                            <textarea name="bidang_fokus_json[{{ $index }}][desc]" class="form-input summernote-mini">{{ $item['desc'] ?? '' }}</textarea>
                        </div>
                    </div>
                    @endforeach
                </div>
                <button type="button" class="btn-add-item" onclick="addRepeaterItemWithIcon('bidang-fokus-container', 'bidang_fokus_json')">
                    <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Tambah Fokus Bidang
                </button>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    function initSummernote() {
        $('.summernote').summernote({
            height: 200,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['view', ['fullscreen', 'codeview']]
            ],
            placeholder: 'Tulis deskripsi di sini...'
        });
        $('.summernote-mini').summernote({
            height: 120,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol']],
                ['view', ['codeview']]
            ],
            placeholder: 'Gunakan bullet points untuk daftar (ul/li)...'
        });
    }

    $(document).ready(function() {
        initSummernote();
    });

    function switchTab(tabId) {
        document.querySelectorAll('.tab-pane').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.settings-tab-btn').forEach(el => el.classList.remove('active'));

        document.getElementById('tab-' + tabId).classList.add('active');
        event.currentTarget.classList.add('active');
    }

    function addRepeaterItem(containerId, inputName) {
        const container = document.getElementById(containerId);
        const index = container.children.length;

        const html = `
            <div class="repeater-item">
                <button type="button" class="btn-remove-item" onclick="this.parentElement.remove()">
                    <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    Hapus
                </button>
                <div class="form-group">
                    <label class="form-label">Judul Tujuan</label>
                    <input type="text" name="${inputName}[${index}][title]" class="form-input">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="${inputName}[${index}][desc]" class="form-input" style="min-height: 80px;"></textarea>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    }

    function addRepeaterItemWithIcon(containerId, inputName) {
        const container = document.getElementById(containerId);
        const index = container.children.length;

        const html = `
            <div class="repeater-item">
                <button type="button" class="btn-remove-item" onclick="this.parentElement.remove()">
                    <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    Hapus
                </button>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Judul Fokus</label>
                        <input type="text" name="${inputName}[${index}][title]" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Upload Icon</label>
                        <input type="hidden" name="${inputName}[${index}][old_icon]" value="">
                        <input type="file" name="${inputName}[${index}][icon]" class="form-input" accept="image/png, image/svg+xml">
                        <span class="form-hint">Upload file icon PNG atau SVG (64x64 disarankan).</span>
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Deskripsi (HTML Supported)</label>
                    <textarea name="${inputName}[${index}][desc]" class="form-input summernote-mini"></textarea>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        initSummernote(); // Re-initialize for the newly added textarea
    }
</script>
@endpush
</div>
@endsection
