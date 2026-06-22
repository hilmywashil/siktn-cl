{{-- resources/views/admin/strategic-plan/create.blade.php --}}
@extends('admin.layouts.admin-layout', ['activeMenu' => 'strategic-plan'])

@section('title', 'Tambah Strategic Plan')
@section('page-title', 'Tambah Strategic Plan')

@push('styles')
<style>
    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #6b7280;
        text-decoration: none;
        font-weight: 500;
        margin-bottom: 1.5rem;
        transition: color 0.2s;
    }

    .back-button:hover {
        color: #0a2540;
    }

    .back-button svg {
        width: 20px;
        height: 20px;
        stroke: currentColor;
        fill: none;
        stroke-width: 2;
    }

    .form-card {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        border: 1px solid #e5e7eb;
        max-width: 800px;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #0a2540;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .form-group label .required {
        color: #ef4444;
    }

    .form-input, .form-textarea, .form-select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.875rem;
        transition: all 0.2s;
        font-family: 'Montserrat', sans-serif;
    }

    .form-input:focus, .form-textarea:focus, .form-select:focus {
        outline: none;
        border-color: #ffd700;
        box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.1);
    }

    .form-textarea {
        min-height: 120px;
        resize: vertical;
    }

    .form-help {
        font-size: 0.8125rem;
        color: #6b7280;
        margin-top: 0.375rem;
    }

    .error-message {
        color: #ef4444;
        font-size: 0.8125rem;
        margin-top: 0.375rem;
    }

    .checkbox-wrapper {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .checkbox-wrapper input[type="checkbox"] {
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
        padding: 0.875rem 1.75rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn svg {
        width: 18px;
        height: 18px;
        stroke: currentColor;
        fill: none;
        stroke-width: 2;
    }

    .btn-primary {
        background: #ffd700;
        color: #0a2540;
    }

    .btn-primary:hover {
        background: #ffed4e;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 215, 0, 0.3);
    }

    .btn-secondary {
        background: #f3f4f6;
        color: #374151;
    }

    .btn-secondary:hover {
        background: #e5e7eb;
    }

    @media (max-width: 768px) {
        .form-card {
            padding: 1.5rem;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<a href="{{ route('admin.strategic-plan.index') }}" class="back-button">
    <svg viewBox="0 0 24 24">
        <line x1="19" y1="12" x2="5" y2="12"></line>
        <polyline points="12 19 5 12 12 5"></polyline>
    </svg>
    Kembali
</a>

<div class="form-card">
    <form action="{{ route('admin.strategic-plan.store') }}" 
          method="POST"
          id="strategicPlanForm">
        @csrf

        {{-- Judul --}}
        <div class="form-group">
            <label>
                Judul <span class="required">*</span>
            </label>
            <input type="text" 
                   name="title" 
                   class="form-input" 
                   value="{{ old('title') }}"
                   placeholder="Masukkan judul strategic plan"
                   required>
            @error('title')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        {{-- Deskripsi --}}
        <div class="form-group">
            <label>
                Deskripsi <span class="required">*</span>
            </label>
            <textarea name="description" 
                      class="form-textarea" 
                      placeholder="Masukkan deskripsi strategic plan"
                      required>{{ old('description') }}</textarea>
            @error('description')
                <div class="error-message">{{ $message }}</div>
            @enderror
            <div class="form-help">Deskripsi singkat mengenai strategic plan ini</div>
        </div>

        {{-- Kategori --}}
        <div class="form-group">
            <label>
                Kategori <span class="required">*</span>
            </label>
            <select name="category" class="form-select" required>
                <option value="">Pilih Kategori</option>
                <option value="tata_kelola" {{ old('category') === 'tata_kelola' ? 'selected' : '' }}>
                    Tata Kelola
                </option>
                <option value="program_layanan" {{ old('category') === 'program_layanan' ? 'selected' : '' }}>
                    Program & Layanan
                </option>
            </select>
            @error('category')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        {{-- Urutan --}}
        <div class="form-group">
            <label>Urutan</label>
            <input type="number" 
                   name="order" 
                   class="form-input" 
                   value="{{ old('order') }}"
                   placeholder="Masukkan urutan tampilan"
                   min="1">
            @error('order')
                <div class="error-message">{{ $message }}</div>
            @enderror
            <div class="form-help">Kosongkan untuk otomatis di urutan terakhir</div>
        </div>

        {{-- Status Aktif --}}
        <div class="form-group">
            <div class="checkbox-wrapper">
                <input type="checkbox" 
                       name="is_active" 
                       id="is_active"
                       value="1"
                       {{ old('is_active', true) ? 'checked' : '' }}>
                <label for="is_active" style="margin: 0; font-weight: 500;">Status Aktif</label>
            </div>
            <div class="form-help">Centang untuk menampilkan di halaman publik</div>
        </div>

        {{-- Form Actions --}}
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <svg viewBox="0 0 24 24">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                Simpan Strategic Plan
            </button>
            <a href="{{ route('admin.strategic-plan.index') }}" class="btn btn-secondary">
                <svg viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
                Batal
            </a>
        </div>
    </form>
</div>
@endsection