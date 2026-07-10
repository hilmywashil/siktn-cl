@extends('admin.layouts.admin-layout')

@section('title', 'Edit Master Jabatan')
@section('page-title', 'Edit Master Jabatan')

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

    .form-input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.875rem;
        font-family: 'Montserrat', sans-serif;
        transition: all 0.2s;
    }

    .form-input:focus {
        outline: none;
        border-color: #0a2540;
        box-shadow: 0 0 0 3px rgba(10, 37, 64, 0.1);
    }

    .form-input.error {
        border-color: #dc2626;
    }

    .error-message {
        color: #dc2626;
        font-size: 0.75rem;
        margin-top: 0.375rem;
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

    .btn-secondary {
        background: #f3f4f6;
        color: #4b5563;
    }

    .btn-secondary:hover {
        background: #e5e7eb;
    }

    .btn-primary {
        background: #0a2540;
        color: white;
    }

    .btn-primary:hover {
        background: #164e63;
        transform: translateY(-1px);
    }
</style>
@endpush

@section('content')
<div class="content-header" style="margin-bottom: 24px;">
    <h2 style="font-size: 1.5rem; font-weight: 700; color: #0a2540;">Edit Jabatan</h2>
    <p class="text-muted" style="margin-top: 5px; color: #6b7280; font-size: 0.875rem;">Perbarui nama jabatan yang sudah ada.</p>
</div>

<div class="form-card">
    <form action="{{ route('admin.jabatan.update', $jabatan) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="nama_jabatan" class="form-label required">Nama Jabatan</label>
            <input type="text" 
                   id="nama_jabatan" 
                   name="nama_jabatan" 
                   class="form-input @error('nama_jabatan') error @enderror" 
                   value="{{ old('nama_jabatan', $jabatan->nama_jabatan) }}" 
                   placeholder="Contoh: Dewan Penasihat" 
                   required 
                   autocomplete="off">
            @error('nama_jabatan')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="urutan" class="form-label required">Urutan (Hierarki)</label>
            <input type="number" 
                   id="urutan" 
                   name="urutan" 
                   class="form-input @error('urutan') error @enderror" 
                   value="{{ old('urutan', $jabatan->urutan) }}" 
                   placeholder="1 untuk posisi tertinggi" 
                   min="1"
                   required>
            <p class="text-muted" style="margin-top: 5px; font-size: 0.75rem; color: #6b7280;">Semakin kecil angkanya, semakin tinggi posisinya di struktur organisasi.</p>
            @error('urutan')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.jabatan.index') }}" class="btn btn-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="16" height="16">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Batal
            </a>
            <button type="submit" class="btn btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="16" height="16">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                    <polyline points="7 3 7 8 15 8"></polyline>
                </svg>
                Perbarui
            </button>
        </div>
    </form>
</div>
@endsection
