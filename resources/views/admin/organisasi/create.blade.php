{{-- resources/views/admin/organisasi/create.blade.php --}}
@extends('admin.layouts.admin-layout')

@section('title', 'Tambah Anggota Organisasi')

@section('page-title', 'Tambah Anggota Organisasi')

@push('styles')
<style>
    .form-card {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        max-width: 800px;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .form-label.required::after {
        content: '*';
        color: #dc2626;
        margin-left: 0.25rem;
    }

    .form-input, .form-select {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.875rem;
        font-family: 'Montserrat', sans-serif;
        transition: all 0.2s;
    }

    .form-input:focus, .form-select:focus {
        outline: none;
        border-color: #0a2540;
        box-shadow: 0 0 0 3px rgba(10, 37, 64, 0.1);
    }

    .form-input.error, .form-select.error {
        border-color: #dc2626;
    }

    .error-message {
        color: #dc2626;
        font-size: 0.75rem;
        margin-top: 0.375rem;
    }

    .file-input-wrapper {
        position: relative;
        display: inline-block;
        width: 100%;
    }

    .file-input {
        display: none;
    }

    .file-label {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        border: 2px dashed #d1d5db;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        background: #f9fafb;
    }

    .file-label:hover {
        border-color: #0a2540;
        background: #f3f4f6;
    }

    .file-label-content {
        text-align: center;
    }

    .file-icon {
        width: 48px;
        height: 48px;
        margin: 0 auto 1rem;
        color: #6b7280;
    }

    .image-preview {
        margin-top: 1rem;
        text-align: center;
    }

    .preview-img {
        max-width: 200px;
        max-height: 200px;
        border-radius: 8px;
        border: 2px solid #e5e7eb;
    }

    .checkbox-wrapper {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-checkbox {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid #f3f4f6;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-family: 'Montserrat', sans-serif;
    }

    .btn-primary {
        background: #0a2540;
        color: white;
    }

    .btn-primary:hover {
        background: #164e63;
    }

    .btn-secondary {
        background: #f3f4f6;
        color: #374151;
    }

    .btn-secondary:hover {
        background: #e5e7eb;
    }

    .form-helper {
        font-size: 0.75rem;
        color: #6b7280;
        margin-top: 0.375rem;
    }
</style>
@endpush

@section('content')
    <div class="form-card">
        <form action="{{ route('admin.organisasi.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="nama" class="form-label required">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" class="form-input @error('nama') error @enderror" 
                       value="{{ old('nama') }}" placeholder="Masukkan nama lengkap">
                @error('nama')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="jabatan" class="form-label required">Nama Jabatan</label>
                <input type="text" id="jabatan" name="jabatan" class="form-input @error('jabatan') error @enderror" 
                       value="{{ old('jabatan', request('jabatan')) }}" list="jabatan-list" placeholder="Pilih atau Ketik Jabatan Baru" required>
                <datalist id="jabatan-list">
                    @foreach($jabatans->unique('nama_jabatan') as $jab)
                        <option value="{{ $jab->nama_jabatan }}"></option>
                    @endforeach
                </datalist>
                @error('jabatan')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group" id="atasan-group">
                <label for="atasan_id" class="form-label required">Pilih Atasan (Induk)</label>
                <select id="atasan_id" name="atasan_id" class="form-select select2 @error('atasan_id') error @enderror">
                    <option value="">-- Tidak Ada (Jadi Pimpinan Tertinggi) --</option>
                    @foreach($jabatans as $jab)
                        <option value="{{ $jab->id }}" {{ old('atasan_id', request('atasan_id')) == $jab->id ? 'selected' : '' }}>
                            {{ $jab->urutan }} - {{ $jab->nama_jabatan }}
                        </option>
                    @endforeach
                </select>
                <div class="form-helper">Pilih jabatan induk untuk menentukan urutan secara otomatis.</div>
                @error('atasan_id')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="foto" class="form-label">Foto</label>
                <div class="file-input-wrapper">
                    <input type="file" id="foto" name="foto" class="file-input" accept="image/*" onchange="previewImage(event)">
                    <label for="foto" class="file-label">
                        <div class="file-label-content">
                            <svg class="file-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                <polyline points="21 15 16 10 5 21"></polyline>
                            </svg>
                            <div style="font-weight: 600; margin-bottom: 0.25rem;">Klik untuk upload foto</div>
                            <div style="font-size: 0.75rem; color: #6b7280;">JPG, PNG (Max. 2MB)</div>
                        </div>
                    </label>
                </div>
                <div id="imagePreview" class="image-preview" style="display: none;">
                    <img id="preview" class="preview-img" src="" alt="Preview">
                </div>
                @error('foto')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <div class="checkbox-wrapper">
                    <input type="checkbox" id="aktif" name="aktif" class="form-checkbox" value="1" {{ old('aktif', true) ? 'checked' : '' }}>
                    <label for="aktif" class="form-label" style="margin-bottom: 0;">Aktif</label>
                </div>
                <div class="form-helper">Centang jika anggota ini aktif dalam organisasi</div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="16" height="16">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                        <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                    Simpan Data
                </button>
                <a href="{{ route('admin.organisasi.index') }}" class="btn btn-secondary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="16" height="16">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2 tanpa placeholder agar opsi pertama (kosong) tetap muncul di list
        $('.select2').select2({
            theme: 'default',
            width: '100%'
        });
    });

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