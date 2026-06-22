@extends('admin.layouts.admin-layout')

@section('title', 'Tambah E-Katalog')
@section('page-title', 'Tambah E-Katalog')

@php
    $activeMenu = 'katalog';
@endphp

@push('styles')
<style>
    .form-container {
        background: white;
        border-radius: 6px;
        padding: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        max-width: 100%;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        font-size: 0.875rem;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .form-label.required::after {
        content: '*';
        color: #dc2626;
        margin-left: 0.25rem;
    }

    .form-input, .form-textarea, .form-select {
        width: 100%;
        padding: 0.625rem 0.875rem;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        font-size: 0.875rem;
        font-family: 'Montserrat', sans-serif;
        transition: all 0.2s;
    }

    .form-input:focus, .form-textarea:focus, .form-select:focus {
        outline: none;
        border-color: #0B1354;
        box-shadow: 0 0 0 3px rgba(11, 19, 84, 0.1);
    }

    .form-textarea {
        min-height: 120px;
        resize: vertical;
    }

    .form-file-input {
        display: block;
        width: 100%;
        padding: 0.625rem;
        border: 2px dashed #d1d5db;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .form-file-input:hover {
        border-color: #0B1354;
        background: rgba(11, 19, 84, 0.02);
    }

    .file-preview {
        margin-top: 0.75rem;
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .file-preview img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 4px;
        border: 1px solid #e5e7eb;
    }

    .form-checkbox {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-checkbox input {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e5e7eb;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 4px;
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
        font-family: 'Montserrat', sans-serif;
    }

    .btn-primary {
        background: #C59217;
        color: white;
    }

    .btn-primary:hover {
        background: #a3750d;
        transform: translateY(-1px);
    }

    .btn-secondary {
        background: #f3f4f6;
        color: #374151;
    }

    .btn-secondary:hover {
        background: #e5e7eb;
    }

    .form-error {
        color: #dc2626;
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }

    .form-hint {
        font-size: 0.75rem;
        color: #6b7280;
        margin-top: 0.25rem;
    }

    .map-preview-container {
        margin-top: 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
        display: none;
    }

    .map-preview-container.show {
        display: block;
    }

    .map-preview-container iframe {
        width: 100%;
        height: 300px;
        border: none;
    }

    .map-preview-placeholder {
        width: 100%;
        height: 300px;
        background: #f9fafb;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 0.5rem;
        color: #6b7280;
    }

    .map-preview-placeholder svg {
        width: 48px;
        height: 48px;
        opacity: 0.5;
    }

    .map-helper {
        background: #f3f4f6;
        border-radius: 8px;
        padding: 1rem;
        margin-top: 0.5rem;
    }

    .map-helper-title {
        font-weight: 600;
        font-size: 0.875rem;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .map-helper-steps {
        font-size: 0.75rem;
        color: #6b7280;
        line-height: 1.6;
    }

    .map-helper-steps ol {
        margin: 0.5rem 0;
        padding-left: 1.5rem;
    }

    .map-helper-steps li {
        margin-bottom: 0.25rem;
    }

    .format-accepted {
        display: inline-block;
        background: #10b981;
        color: white;
        padding: 0.125rem 0.5rem;
        border-radius: 4px;
        font-size: 0.625rem;
        font-weight: 600;
        margin-left: 0.5rem;
    }

    .map-status {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.75rem;
        margin-top: 0.5rem;
        padding: 0.5rem;
        border-radius: 6px;
    }

    .map-status.success {
        background: #d1fae5;
        color: #065f46;
    }

    .map-status.error {
        background: #fee2e2;
        color: #991b1b;
    }

    .map-status svg {
        width: 16px;
        height: 16px;
    }

    .code-example {
        background: #1e293b;
        color: #e2e8f0;
        padding: 1rem;
        border-radius: 6px;
        font-family: 'Courier New', monospace;
        font-size: 11px;
        overflow-x: auto;
        margin-top: 0.5rem;
    }
</style>
@endpush

@section('content')
<div class="form-container">
    <form action="{{ route('admin.katalog.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label class="form-label">Link ke Anggota (Opsional)</label>
            <select name="anggota_id" class="form-select">
                <option value="">-- Tidak terhubung dengan anggota --</option>
                @foreach($anggotas as $anggota)
                    <option value="{{ $anggota->id }}" {{ old('anggota_id') == $anggota->id ? 'selected' : '' }}>
                        {{ $anggota->nama_perusahaan ?? $anggota->nama_pimpinan }}
                    </option>
                @endforeach
            </select>
            <div class="form-hint">Pilih anggota jika katalog ini untuk anggota tertentu</div>
            @error('anggota_id')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label required">Nama Perusahaan</label>
            <input type="text" name="company_name" class="form-input" value="{{ old('company_name') }}" required>
            @error('company_name')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label required">Bidang Perusahaan</label>
            <input type="text" name="business_field" class="form-input" value="{{ old('business_field') }}" required>
            @error('business_field')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label required">Deskripsi Perusahaan</label>
            <textarea name="description" class="form-textarea" required>{{ old('description') }}</textarea>
            @error('description')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Logo Perusahaan</label>
            <input type="file" name="logo" class="form-file-input" accept="image/*" onchange="previewLogo(event)">
            <div class="form-hint">Format: JPG, PNG, GIF (Max: 2MB)</div>
            <div class="file-preview" id="logoPreview"></div>
            @error('logo')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Gambar Galeri (Max 3)</label>
            <input type="file" name="images[]" class="form-file-input" accept="image/*" multiple onchange="previewImages(event)">
            <div class="form-hint">Format: JPG, PNG, GIF (Max: 2MB per file)</div>
            <div class="file-preview" id="imagesPreview"></div>
            @error('images.*')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label required">Alamat</label>
            <textarea name="address" class="form-textarea" rows="3" required>{{ old('address') }}</textarea>
            @error('address')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label required">Nomor Telepon</label>
            <input type="text" name="phone" class="form-input" value="{{ old('phone') }}" required>
            @error('phone')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label required">Email</label>
            <input type="email" name="email" class="form-input" value="{{ old('email') }}" required>
            @error('email')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">
                Lokasi Google Maps
                <span class="format-accepted">Hanya kode Embed!</span>
            </label>
            <textarea 
                name="map_embed_url" 
                class="form-textarea" 
                rows="4"
                placeholder="Paste kode iframe embed dari Google Maps di sini..."
                oninput="handleMapInput(this.value)">{{ old('map_embed_url') }}</textarea>
            <div class="form-hint">
                <strong>Yang diterima:</strong> Kode iframe embed lengkap dari Google Maps
            </div>
            
            <div id="mapStatus"></div>

            <div class="map-helper">
                <div class="map-helper-title">Cara mendapatkan kode embed:</div>
                <div class="map-helper-steps">
                    <ol>
                        <li>Buka <strong>google.com/maps</strong> di browser</li>
                        <li>Cari dan klik lokasi perusahaan Anda</li>
                        <li>Klik tombol <strong>"Share"</strong> atau <strong>"Bagikan"</strong></li>
                        <li>Pilih tab <strong>"Embed a map"</strong> atau <strong>"Sematkan peta"</strong></li>
                        <li>Klik <strong>"COPY HTML"</strong></li>
                        <li>Paste kode yang sudah dicopy ke kolom di atas ↑</li>
                    </ol>
                    <div style="margin-top: 0.75rem; padding: 0.75rem; background: #eff6ff; border-radius: 6px; border: 1px solid #bfdbfe;">
                        <strong style="color: #1e40af;">Contoh kode embed yang benar:</strong>
                        <div class="code-example">&lt;iframe src="https://www.google.com/maps/embed?pb=!1m18..."<br>width="600" height="450" style="border:0;"<br>allowfullscreen="" loading="lazy"&gt;&lt;/iframe&gt;</div>
                    </div>
                </div>
            </div>

            <div class="map-preview-container" id="mapPreviewContainer">
                <div class="map-preview-placeholder">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <p>Preview map akan muncul di sini</p>
                </div>
                <iframe id="mapPreviewFrame" src="" allowfullscreen loading="lazy" style="display: none;"></iframe>
            </div>
            
            @error('map_embed_url')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-checkbox">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                <span>Aktifkan katalog ini</span>
            </label>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan Data</button>
            <a href="{{ route('admin.katalog.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function previewLogo(event) {
        const preview = document.getElementById('logoPreview');
        preview.innerHTML = '';
        
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                preview.appendChild(img);
            }
            reader.readAsDataURL(file);
        }
    }

    function previewImages(event) {
        const preview = document.getElementById('imagesPreview');
        preview.innerHTML = '';
        
        const files = event.target.files;
        for (let i = 0; i < Math.min(files.length, 3); i++) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                preview.appendChild(img);
            }
            reader.readAsDataURL(files[i]);
        }
    }

    let debounceTimer;
    function handleMapInput(input) {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            previewMap(input);
        }, 500);
    }

    function showMapStatus(type, message) {
        const statusDiv = document.getElementById('mapStatus');
        const icons = {
            success: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>',
            error: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>'
        };
        
        statusDiv.innerHTML = `
            <div class="map-status ${type}">
                ${icons[type]}
                <span>${message}</span>
            </div>
        `;
    }

    function previewMap(input) {
        const previewContainer = document.getElementById('mapPreviewContainer');
        const previewFrame = document.getElementById('mapPreviewFrame');
        const placeholder = previewContainer.querySelector('.map-preview-placeholder');
        
        if (!input || input.trim() === '') {
            previewContainer.classList.remove('show');
            previewFrame.style.display = 'none';
            placeholder.style.display = 'flex';
            document.getElementById('mapStatus').innerHTML = '';
            return;
        }

        const embedUrl = extractEmbedUrl(input);
        
        if (embedUrl) {
            previewFrame.src = embedUrl;
            previewFrame.style.display = 'block';
            placeholder.style.display = 'none';
            previewContainer.classList.add('show');
            showMapStatus('success', 'Kode embed terdeteksi. Preview map ditampilkan di bawah.');
        } else {
            previewContainer.classList.remove('show');
            previewFrame.style.display = 'none';
            placeholder.style.display = 'flex';
            showMapStatus('error', 'Kode embed tidak valid. Pastikan Anda copy KODE IFRAME dari Google Maps (bukan URL biasa).');
        }
    }

    function extractEmbedUrl(input) {
        input = input.trim();
        
        // Extract URL dari iframe src
        const srcMatch = input.match(/src=["']([^"']+)["']/);
        if (srcMatch) {
            const url = srcMatch[1];
            // Validasi bahwa ini adalah Google Maps embed URL
            if (url.includes('google.com/maps/embed') || (url.includes('google.com/maps') && url.includes('output=embed'))) {
                return url;
            }
        }
        
        // Jika langsung URL embed tanpa iframe tags
        if (input.includes('google.com/maps/embed') || (input.includes('google.com/maps') && input.includes('output=embed'))) {
            return input;
        }
        
        return null;
    }
</script>
@endpush
@endsection