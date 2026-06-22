@extends('admin.layouts.admin-layout', ['activeMenu' => 'misi'])

@section('title', 'Tambah Misi')
@section('page-title', 'Tambah Misi')

@push('styles')
<style>
    .form-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        max-width: 800px;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.875rem;
        transition: all 0.2s;
        font-family: 'Montserrat', sans-serif;
    }

    .form-control:focus {
        outline: none;
        border-color: #ffd700;
        box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.1);
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    .form-check {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-check input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .image-preview {
        margin-top: 1rem;
        border: 2px dashed #d1d5db;
        border-radius: 8px;
        padding: 1rem;
        text-align: center;
    }

    .image-preview img {
        max-width: 300px;
        max-height: 200px;
        border-radius: 8px;
        margin-top: 0.5rem;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        font-family: 'Montserrat', sans-serif;
    }

    .btn-primary {
        background: #ffd700;
        color: #0a2540;
    }

    .btn-primary:hover {
        background: #ffed4e;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(255, 215, 0, 0.3);
    }

    .btn-secondary {
        background: #e5e7eb;
        color: #374151;
    }

    .btn-secondary:hover {
        background: #d1d5db;
    }

    .error-message {
        color: #dc2626;
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="form-container">
        <form action="{{ route('admin.misi.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="form-label">Judul Misi *</label>
                <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                @error('title')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi *</label>
                <textarea name="description" class="form-control" required>{{ old('description') }}</textarea>
                @error('description')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Gambar</label>
                <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(event)">
                <div class="image-preview" id="imagePreview" style="display: none;">
                    <img id="preview" src="" alt="Preview">
                </div>
                @error('image')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Urutan *</label>
                <input type="number" name="order" class="form-control" value="{{ old('order', 0) }}" min="0" required>
                <small style="color: #6b7280; font-size: 0.75rem;">Urutan tampilan misi (0, 1, 2, dst)</small>
                @error('order')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label for="is_active" class="form-label" style="margin-bottom: 0;">Aktifkan Misi</label>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Simpan Misi</button>
                <a href="{{ route('admin.misi.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewImage(event) {
    const preview = document.getElementById('preview');
    const previewContainer = document.getElementById('imagePreview');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endpush