@extends('admin.layouts.admin-layout')

@section('title', 'Pengaturan Halaman Program')

@push('styles')
<style>
    .settings-container {
        display: flex;
        gap: 1.5rem;
        align-items: flex-start;
    }
    .settings-tabs {
        width: 280px;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        background: white;
        padding: 1.25rem;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e5e7eb;
    }
    .tab-btn {
        padding: 1rem 1.5rem;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        text-align: left;
        cursor: pointer;
        font-weight: 600;
        color: #4b5563;
        transition: all 0.2s;
    }
    .tab-btn.active {
        background: #0a2540;
        color: white;
        border-color: #0a2540;
    }
    .tab-btn:hover:not(.active) {
        background: #f9fafb;
    }
    .settings-content {
        flex-grow: 1;
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e5e7eb;
        padding: 2.5rem;
    }
    .btn-primary {
        background: #0a2540;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
        width: 100%;
    }
    .btn-primary:hover {
        background: #164e63;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .tab-pane {
        display: none;
    }
    .tab-pane.active {
        display: block;
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    .form-label {
        display: block;
        font-weight: 600;
        font-size: 0.9rem;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }
    .form-input {
        width: 100%;
        padding: 0.75rem 1rem;
        background-color: #f9fafb;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-family: inherit;
        font-size: 0.95rem;
        transition: all 0.2s;
    }
    .form-input:hover {
        border-color: #9ca3af;
    }
    textarea.form-input {
        min-height: 120px;
        resize: vertical;
    }
    .form-input:focus {
        outline: none;
        background-color: white;
        border-color: #0ea5e9;
        box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.1);
    }
    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0a2540;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #e2e8f0;
    }
    .json-hint {
        font-size: 0.875rem;
        color: #6b7280;
        margin-top: 0.5rem;
        font-style: italic;
    }
    .repeater-item {
        background: #fefefe;
        border: 1px solid #e2e8f0;
        padding: 1.5rem;
        border-radius: 10px;
        margin-bottom: 1.25rem;
        position: relative;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        transition: all 0.2s;
    }
    .repeater-item:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 6px rgba(0,0,0,0.04);
    }
    .btn-remove {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: #fee2e2;
        color: #dc2626;
        border: none;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.8125rem;
        font-weight: 600;
        transition: all 0.2s;
    }
    .btn-remove:hover {
        background: #fca5a5;
    }
    .btn-add {
        background: #f0fdf4;
        color: #16a34a;
        border: 1px dashed #86efac;
        padding: 0.875rem 1.5rem;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
        width: 100%;
        margin-bottom: 2rem;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    .btn-add:hover {
        background: #dcfce7;
        border-color: #4ade80;
    }
    .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
    }
    /* Summernote customization */
    .note-editor.note-frame {
        border-color: #d1d5db;
        border-radius: 8px;
        overflow: hidden;
    }
    .note-toolbar {
        background-color: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="page-header" style="margin-bottom: 2rem;">
    <h1 class="page-title">Pengaturan Halaman Program</h1>
    <p class="page-desc">Kelola teks statis untuk halaman publik Program CSR dan Program Bidang.</p>
</div>



<form action="{{ route('admin.program.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="settings-container">
        <!-- Tabs -->
        <div class="settings-tabs">
            <button type="button" class="tab-btn active" onclick="switchTab('csr')">Halaman CSR</button>
            <button type="button" class="tab-btn" onclick="switchTab('bidang')">Halaman Bidang</button>
            
            <hr style="border: 0; border-top: 1px solid #e5e7eb; margin: 1rem 0;">
            
            <button type="submit" class="btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                    <polyline points="7 3 7 8 15 8"></polyline>
                </svg>
                Simpan Perubahan
            </button>
        </div>

        <!-- Content -->
        <div class="settings-content">
            
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
                    <span class="json-hint">Biarkan kosong jika tidak ingin mengubah gambar saat ini.</span>
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
                        <button type="button" class="btn-remove" onclick="this.parentElement.remove()">Hapus</button>
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
                <button type="button" class="btn-add" onclick="addRepeaterItem('csr-tujuan-container', 'csr_tujuan_json')">+ Tambah Tujuan CSR</button>

                <h2 class="section-title" style="margin-top: 2.5rem;">Bagian Fokus Program (CSR)</h2>
                <div id="csr-fokus-container">
                    @php
                        $csrFokus = json_decode(\App\Models\PageSetting::getVal('csr_fokus_json', '[]'), true) ?? [];
                    @endphp
                    @foreach($csrFokus as $index => $item)
                    <div class="repeater-item">
                        <button type="button" class="btn-remove" onclick="this.parentElement.remove()">Hapus</button>
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
                                <span class="json-hint">Format PNG/SVG. Biarkan kosong jika tidak ingin mengubah icon saat ini.</span>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Deskripsi (HTML Supported)</label>
                            <textarea name="csr_fokus_json[{{ $index }}][desc]" class="form-input summernote-mini">{{ $item['desc'] ?? '' }}</textarea>
                        </div>
                    </div>
                    @endforeach
                </div>
                <button type="button" class="btn-add" onclick="addRepeaterItemWithIcon('csr-fokus-container', 'csr_fokus_json')">+ Tambah Fokus CSR</button>
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
                    <span class="json-hint">Biarkan kosong jika tidak ingin mengubah gambar saat ini.</span>
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
                        <button type="button" class="btn-remove" onclick="this.parentElement.remove()">Hapus</button>
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
                <button type="button" class="btn-add" onclick="addRepeaterItem('bidang-tujuan-container', 'bidang_tujuan_json')">+ Tambah Tujuan Bidang</button>

                <h2 class="section-title" style="margin-top: 2.5rem;">Bagian Fokus Program (Bidang)</h2>
                <div id="bidang-fokus-container">
                    @php
                        $bidangFokus = json_decode(\App\Models\PageSetting::getVal('bidang_fokus_json', '[]'), true) ?? [];
                    @endphp
                    @foreach($bidangFokus as $index => $item)
                    <div class="repeater-item">
                        <button type="button" class="btn-remove" onclick="this.parentElement.remove()">Hapus</button>
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
                                <span class="json-hint">Format PNG/SVG. Biarkan kosong jika tidak ingin mengubah icon saat ini.</span>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Deskripsi (HTML Supported)</label>
                            <textarea name="bidang_fokus_json[{{ $index }}][desc]" class="form-input summernote-mini">{{ $item['desc'] ?? '' }}</textarea>
                        </div>
                    </div>
                    @endforeach
                </div>
                <button type="button" class="btn-add" onclick="addRepeaterItemWithIcon('bidang-fokus-container', 'bidang_fokus_json')">+ Tambah Fokus Bidang</button>
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
        document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
        
        document.getElementById('tab-' + tabId).classList.add('active');
        event.currentTarget.classList.add('active');
    }

    function addRepeaterItem(containerId, inputName) {
        const container = document.getElementById(containerId);
        const index = container.children.length;
        
        const html = `
            <div class="repeater-item">
                <button type="button" class="btn-remove" onclick="this.parentElement.remove()">Hapus</button>
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
                <button type="button" class="btn-remove" onclick="this.parentElement.remove()">Hapus</button>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Judul Fokus</label>
                        <input type="text" name="${inputName}[${index}][title]" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Upload Icon</label>
                        <input type="hidden" name="${inputName}[${index}][old_icon]" value="">
                        <input type="file" name="${inputName}[${index}][icon]" class="form-input" accept="image/png, image/svg+xml">
                        <span class="json-hint">Upload file icon PNG atau SVG (64x64 disarankan).</span>
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
@endsection
