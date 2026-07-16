{{-- resources/views/admin/berita/edit.blade.php --}}
@extends('admin.layouts.admin-layout')

@section('title', 'Edit Berita')
@section('page-title', 'Edit Berita')

@php
$activeMenu = 'berita';
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
        color: #374151;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .required {
        color: #dc2626;
    }

    .form-input,
    .form-textarea,
    .form-select {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        font-size: 0.9375rem;
        transition: all 0.2s;
        font-family: 'Montserrat', sans-serif;
    }

    .form-input:focus,
    .form-textarea:focus,
    .form-select:focus {
        outline: none;
        border-color: #022648;
        box-shadow: 0 0 0 3px rgba(11, 19, 84, 0.1);
    }

    .form-textarea {
        min-height: 200px;
        resize: vertical;
    }

    .form-help {
        font-size: 0.8125rem;
        color: #6b7280;
        margin-top: 0.375rem;
    }

    .error-message {
        color: #dc2626;
        font-size: 0.8125rem;
        margin-top: 0.375rem;
    }

    .current-image {
        margin-top: 0.5rem;
        margin-bottom: 1rem;
    }

    .current-image img {
        max-width: 300px;
        border-radius: 4px;
        border: 2px solid #e5e7eb;
    }

    .current-image p {
        margin-top: 0.5rem;
        font-size: 0.8125rem;
        color: #6b7280;
    }

    .image-preview {
        margin-top: 1rem;
        display: none;
    }

    .image-preview img {
        max-width: 300px;
        border-radius: 4px;
        border: 2px solid #e5e7eb;
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }

    .checkbox-group input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .checkbox-group label {
        cursor: pointer;
        user-select: none;
        font-weight: 500;
        color: #374151;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid #e5e7eb;
    }

    .btn-submit,
    .btn-cancel {
        padding: 0.75rem 2rem;
        border-radius: 4px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        font-family: 'Montserrat', sans-serif;
        border: none;
    }

    .btn-submit {
        background: #C59217;
        color: white;
    }

    .btn-submit:hover {
        background: #a3750d;
        color: white;
        transform: translateY(-1px);
    }

    .btn-cancel {
        background: #f3f4f6;
        color: #374151;
    }

    .btn-cancel:hover {
        background: #e5e7eb;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #6b7280;
        text-decoration: none;
        margin-bottom: 1.5rem;
        font-weight: 500;
        transition: color 0.2s;
    }

    .back-link:hover {
        color: #022648;
    }

    @media (max-width: 1024px) {
        .form-container {
            padding: 1.5rem;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-submit,
        .btn-cancel {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<a href="{{ route('admin.berita.index') }}" class="back-link">
    <svg viewBox="0 0 24 24" width="20" height="20" style="stroke: currentColor; fill: none; stroke-width: 2;">
        <line x1="19" y1="12" x2="5" y2="12" />
        <polyline points="12 19 5 12 12 5" />
    </svg>
    Kembali ke Daftar Berita
</a>

<div class="form-container">
    <form action="{{ route('admin.berita.update', $berita->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label">
                Judul Berita <span class="required">*</span>
            </label>
            <input type="text" name="judul" class="form-input" value="{{ old('judul', $berita->judul) }}" required
                placeholder="Masukkan judul berita">
            @error('judul')
            <div class="error-message">{{ $message }}</div>
            @enderror
            <div class="form-help">Slug saat ini: <strong>{{ $berita->slug }}</strong></div>
        </div>

        <div class="form-group">
            <label class="form-label">
                Konten Berita <span class="required">*</span>
            </label>
            <textarea name="konten" class="form-textarea" required
                placeholder="Tulis konten berita lengkap...">{{ old('konten', $berita->konten) }}</textarea>
            @error('konten')
            <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">
                Gambar Utama
            </label>

            <div class="current-image">
                <img src="{{ $berita->gambar_url }}" alt="{{ $berita->judul }}">
                <p>Gambar saat ini</p>
            </div>

            <input type="file" name="gambar" class="form-input" accept="image/jpeg,image/jpg,image/png,image/webp",
                onchange="previewImage(event)">
            @error('gambar')
            <div class="error-message">{{ $message }}</div>
            @enderror
            <div class="form-help">Format: JPG, JPEG, PNG, WEBP. Maksimal 2MB. Kosongkan jika tidak ingin mengubah gambar.</div>

            <div class="image-preview" id="imagePreview">
                <p style="margin-bottom: 0.5rem; font-size: 0.8125rem; color: #6b7280;">Preview gambar baru:</p>
                <img src="" alt="Preview" id="previewImg">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">
                Tanggal Publish <span class="required">*</span>
            </label>
            <input type="text" name="tanggal_publish" class="form-input datepicker"
                value="{{ old('tanggal_publish', $berita->tanggal_publish->format('Y-m-d')) }}" required placeholder="Pilih tanggal">
            @error('tanggal_publish')
            <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Pengaturan Berita</label>

            <div class="checkbox-group">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                    {{ old('is_active', $berita->is_active) ? 'checked' : '' }}>
                <label for="is_active">Aktifkan Berita (tampil di halaman publik)</label>
            </div>

            <div class="checkbox-group">
                <input type="checkbox" name="is_populer" id="is_populer" value="1"
                    {{ old('is_populer', $berita->is_populer) ? 'checked' : '' }}>
                <label for="is_populer">Tandai sebagai Berita Populer</label>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Statistik</label>
            <div style="padding: 1rem; background: #f9fafb; border-radius: 4px; font-size: 0.875rem; color: #6b7280;">
                <strong style="color: #022648;">{{ number_format($berita->views) }}</strong> kali dilihat
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">Update Berita</button>
            <a href="{{ route('admin.berita.index') }}" class="btn-cancel">Batal</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function previewImage(event) {
        const preview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                previewImg.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    }
</script>
@endpush
@endsection