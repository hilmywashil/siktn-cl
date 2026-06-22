@extends('admin.layouts.admin-layout')

@section('title', 'Profil Admin')
@section('page-title', 'Profil Admin')

@push('styles')
<style>
    .profile-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .profile-grid {
        display: grid;
        grid-template-columns: 350px 1fr;
        gap: 2rem;
        margin-top: 2rem;
    }

    .profile-sidebar {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        height: fit-content;
    }

    .profile-avatar-section {
        text-align: center;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .profile-avatar-wrapper {
        position: relative;
        width: 120px;
        height: 120px;
        margin: 0 auto 1rem;
    }

    .profile-avatar-large {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0a2540;
        font-weight: 800;
        font-size: 2.5rem;
        box-shadow: 0 4px 12px rgba(255, 215, 0, 0.3);
        overflow: hidden;
    }

    .profile-avatar-large img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-avatar-edit {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 36px;
        height: 36px;
        background: #ffd700;
        border-radius: 50%;
        border: 3px solid white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .profile-avatar-edit:hover {
        background: #ffed4e;
        transform: scale(1.1);
    }

    .profile-avatar-edit svg {
        width: 16px;
        height: 16px;
        stroke: #0a2540;
    }

    .photo-upload-input {
        display: none;
    }

    .profile-name {
        font-size: 1.5rem;
        font-weight: 700;
        color: #0a2540;
        margin-bottom: 0.25rem;
    }

    .profile-category {
        display: inline-block;
        padding: 0.375rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
        margin-top: 0.5rem;
    }

    .profile-category.super-admin {
        background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
        color: white;
    }

    .profile-category.bpc {
        background: #fef3c7;
        color: #92400e;
    }

    .profile-category.bpd {
        background: #dbeafe;
        color: #1e40af;
    }

    .profile-photo-actions {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        margin-top: 1rem;
    }

    .btn-photo {
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.8125rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        font-family: 'Montserrat', sans-serif;
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .btn-photo svg {
        width: 14px;
        height: 14px;
    }

    .btn-photo-upload {
        background: #ffd700;
        color: #0a2540;
    }

    .btn-photo-upload:hover {
        background: #ffed4e;
    }

    .btn-photo-delete {
        background: #fee2e2;
        color: #dc2626;
    }

    .btn-photo-delete:hover {
        background: #fecaca;
    }

    .profile-info {
        padding-top: 1.5rem;
    }

    .profile-info-item {
        display: flex;
        align-items: start;
        gap: 0.75rem;
        padding: 0.75rem 0;
    }

    .profile-info-icon {
        width: 20px;
        height: 20px;
        color: #6b7280;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .profile-info-content {
        flex: 1;
    }

    .profile-info-label {
        font-size: 0.75rem;
        color: #6b7280;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .profile-info-value {
        font-size: 0.9375rem;
        color: #0a2540;
        font-weight: 600;
        margin-top: 0.25rem;
        word-break: break-word;
    }

    .profile-main {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .profile-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .profile-card-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .profile-card-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #fef3c7;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .profile-card-icon svg {
        width: 20px;
        height: 20px;
        stroke: #92400e;
    }

    .profile-card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0a2540;
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

    .form-label-required::after {
        content: ' *';
        color: #dc2626;
    }

    .form-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.9375rem;
        font-family: 'Montserrat', sans-serif;
        transition: all 0.2s;
    }

    .form-input:focus {
        outline: none;
        border-color: #ffd700;
        box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.1);
    }

    .form-input:disabled {
        background: #f9fafb;
        color: #6b7280;
        cursor: not-allowed;
    }

    .form-error {
        color: #dc2626;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e5e7eb;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-size: 0.9375rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
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
        background: #f3f4f6;
        color: #374151;
    }

    .btn-secondary:hover {
        background: #e5e7eb;
    }

    .alert {
        padding: 1rem 1.25rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #6ee7b7;
    }

    .alert svg {
        width: 20px;
        height: 20px;
        flex-shrink: 0;
    }

    .photo-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    .photo-modal.active {
        display: flex;
    }

    .photo-modal-content {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        max-width: 400px;
        width: 90%;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        animation: modalSlide 0.3s ease;
    }

    @keyframes modalSlide {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .photo-preview-wrapper {
        width: 200px;
        height: 200px;
        margin: 0 auto 1.5rem;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid #e5e7eb;
    }

    .photo-preview {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: none;
    }

    .photo-preview-placeholder {
        width: 100%;
        height: 100%;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #9ca3af;
    }

    .photo-preview-placeholder svg {
        width: 48px;
        height: 48px;
    }

    @media (max-width: 1024px) {
        .profile-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .profile-card {
            padding: 1.5rem;
        }
        
        .profile-grid {
            gap: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="profile-container">
    @if(session('success'))
    <div class="alert alert-success">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <div class="profile-grid">
        {{-- Sidebar --}}
        <div class="profile-sidebar">
            <div class="profile-avatar-section">
                <div class="profile-avatar-wrapper">
                    <div class="profile-avatar-large" id="avatarDisplay">
                        @if($admin->photo)
                            <img src="{{ $admin->photo_url }}" alt="{{ $admin->name }}" id="sidebarAvatar">
                        @else
                            <span id="sidebarInitials">{{ $admin->initials }}</span>
                        @endif
                    </div>
                    <label for="photoInput" class="profile-avatar-edit" title="Ubah foto profil">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </label>
                </div>
                <h2 class="profile-name">{{ $admin->name }}</h2>
                <span class="profile-category {{ $admin->category }}">
                    @if($admin->isSuperAdmin())
                        Super Admin
                    @elseif($admin->isBPC())
                        BPC
                    @else
                        BPD
                    @endif
                </span>

                @if($admin->photo)
                <div class="profile-photo-actions">
                    <form action="{{ route('admin.profile.photo.delete') }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus foto profil?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-photo btn-photo-delete">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus Foto
                        </button>
                    </form>
                </div>
                @endif
            </div>
            
            <div class="profile-info">
                <div class="profile-info-item">
                    <svg class="profile-info-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <div class="profile-info-content">
                        <div class="profile-info-label">Username</div>
                        <div class="profile-info-value">{{ $admin->username }}</div>
                    </div>
                </div>

                <div class="profile-info-item">
                    <svg class="profile-info-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <div class="profile-info-content">
                        <div class="profile-info-label">Email</div>
                        <div class="profile-info-value">{{ $admin->email }}</div>
                    </div>
                </div>

                @if($admin->domisili)
                <div class="profile-info-item">
                    <svg class="profile-info-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <div class="profile-info-content">
                        <div class="profile-info-label">Domisili</div>
                        <div class="profile-info-value">{{ $admin->domisili }}</div>
                    </div>
                </div>
                @endif

                <div class="profile-info-item">
                    <svg class="profile-info-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="profile-info-content">
                        <div class="profile-info-label">Bergabung Sejak</div>
                        <div class="profile-info-value">{{ $admin->created_at->locale('id')->isoFormat('D MMMM YYYY') }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="profile-main">
            {{-- Update Profile Form --}}
            <div class="profile-card">
                <div class="profile-card-header">
                    <div class="profile-card-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <h3 class="profile-card-title">Edit Profil</h3>
                </div>

                <form action="{{ route('admin.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="form-label form-label-required">Nama Lengkap</label>
                        <input type="text" name="name" class="form-input" value="{{ old('name', $admin->name) }}" required>
                        @error('name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label form-label-required">Username</label>
                        <input type="text" name="username" class="form-input" value="{{ old('username', $admin->username) }}" required>
                        @error('username')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label form-label-required">Email</label>
                        <input type="email" name="email" class="form-input" value="{{ old('email', $admin->email) }}" required>
                        @error('email')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    @if($admin->isBPC())
                    <div class="form-group">
                        <label class="form-label form-label-required">Domisili</label>
                        <input type="text" name="domisili" class="form-input" value="{{ old('domisili', $admin->domisili) }}" required>
                        <small style="color: #6b7280; font-size: 0.8125rem; margin-top: 0.25rem; display: block;">Wilayah kerja BPC Anda</small>
                        @error('domisili')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    @endif

                    <div class="form-group">
                        <label class="form-label">Kategori</label>
                        <input type="text" class="form-input" 
                               value="{{ $admin->isSuperAdmin() ? 'Super Admin' : ($admin->isBPC() ? 'BPC (Badan Pengurus Cabang)' : 'BPD (Badan Pengurus Daerah)') }}" 
                               disabled>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>

            {{-- Change Password Form --}}
            <div class="profile-card">
                <div class="profile-card-header">
                    <div class="profile-card-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h3 class="profile-card-title">Ganti Password</h3>
                </div>

                <form action="{{ route('admin.profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="form-label form-label-required">Password Saat Ini</label>
                        <input type="password" name="current_password" class="form-input" required>
                        @error('current_password')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label form-label-required">Password Baru</label>
                        <input type="password" name="password" class="form-input" required>
                        @error('password')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label form-label-required">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-input" required>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Perbarui Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Photo Upload Modal --}}
<div class="photo-modal" id="photoModal">
    <div class="photo-modal-content">
        <h3 class="profile-card-title" style="margin-bottom: 1.5rem; text-align: center;">Upload Foto Profil</h3>
        
        <div class="photo-preview-wrapper">
            <img id="photoPreview" class="photo-preview" alt="Preview">
            <div class="photo-preview-placeholder" id="photoPlaceholder">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
        </div>

        <form action="{{ route('admin.profile.photo') }}" method="POST" enctype="multipart/form-data" id="photoForm">
            @csrf
            <input type="file" name="photo" id="photoInput" class="photo-upload-input" accept="image/jpeg,image/png,image/jpg">
            
            @error('photo')
                <div class="form-error" style="text-align: center; margin-bottom: 1rem;">{{ $message }}</div>
            @enderror

            <div class="form-actions" style="margin-top: 0; padding-top: 0; border-top: none;">
                <button type="button" class="btn btn-secondary" onclick="closePhotoModal()">Batal</button>
                <button type="submit" class="btn btn-primary" id="uploadBtn" disabled>Upload Foto</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const photoInput = document.getElementById('photoInput');
    const photoModal = document.getElementById('photoModal');
    const photoPreview = document.getElementById('photoPreview');
    const photoPlaceholder = document.getElementById('photoPlaceholder');
    const uploadBtn = document.getElementById('uploadBtn');

    photoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!allowedTypes.includes(file.type)) {
                alert('Format foto harus jpeg, png, atau jpg.');
                photoInput.value = '';
                return;
            }

            if (file.size > 2048000) {
                alert('Ukuran foto maksimal 2MB.');
                photoInput.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                photoPreview.src = e.target.result;
                photoPreview.style.display = 'block';
                photoPlaceholder.style.display = 'none';
                uploadBtn.disabled = false;
                photoModal.classList.add('active');
            };
            reader.readAsDataURL(file);
        }
    });

    function closePhotoModal() {
        photoModal.classList.remove('active');
        photoInput.value = '';
        photoPreview.style.display = 'none';
        photoPlaceholder.style.display = 'flex';
        uploadBtn.disabled = true;
    }

    photoModal.addEventListener('click', function(e) {
        if (e.target === photoModal) {
            closePhotoModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && photoModal.classList.contains('active')) {
            closePhotoModal();
        }
    });

    @error('photo')
        photoModal.classList.add('active');
    @enderror
</script>
@endpush
@endsection