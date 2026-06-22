@extends('admin.layouts.admin-layout')

@section('title', 'Detail Katalog - ' . $katalog->company_name)
@section('page-title', 'Detail Katalog')

@php
    $activeMenu = 'katalog';
@endphp

@push('styles')
<style>
    .detail-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        background: #f3f4f6;
        color: #374151;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.875rem;
        margin-bottom: 1.5rem;
        transition: all 0.2s;
    }

    .back-button:hover {
        background: #e5e7eb;
    }

    .detail-card {
        background: white;
        border-radius: 6px;
        padding: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 1.5rem;
    }

    .detail-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 2px solid #f3f4f6;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .company-info h2 {
        font-size: 1.75rem;
        font-weight: 800;
        color: #111827;
        margin-bottom: 0.5rem;
    }

    .company-field {
        color: #6b7280;
        font-size: 1rem;
        font-weight: 500;
    }

    .status-badge {
        padding: 0.625rem 1.25rem;
        border-radius: 4px;
        font-size: 0.875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border: 1px solid transparent;
    }

    .status-pending {
        background: rgba(197, 146, 23, 0.1);
        color: #C59217;
        border-color: rgba(197, 146, 23, 0.2);
    }

    .status-approved {
        background: rgba(16, 185, 129, 0.1);
        color: #10B981;
        border-color: rgba(16, 185, 129, 0.2);
    }

    .status-rejected {
        background: rgba(214, 11, 28, 0.1);
        color: #D60B1C;
        border-color: rgba(214, 11, 28, 0.2);
    }

    .rejection-box {
        background: rgba(214, 11, 28, 0.05);
        border: 1px solid rgba(214, 11, 28, 0.15);
        border-left: 4px solid #D60B1C;
        border-radius: 4px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .rejection-title {
        font-weight: 700;
        color: #D60B1C;
        margin-bottom: 0.75rem;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .rejection-text {
        color: #a80816;
        line-height: 1.6;
    }

    .detail-section {
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .detail-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .section-title {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #6b7280;
        letter-spacing: 0.5px;
        margin-bottom: 1rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }

    .info-item label {
        display: block;
        font-size: 0.75rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
        letter-spacing: 0.5px;
    }

    .info-item .value {
        font-size: 0.9375rem;
        color: #111827;
        font-weight: 500;
    }

    .logo-display {
        width: 200px;
        height: 200px;
        object-fit: contain;
        border-radius: 4px;
        border: 2px solid #e5e7eb;
        background: #f9fafb;
        padding: 1rem;
    }

    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 1rem;
    }

    .gallery-item {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 4px;
        border: 1px solid #e5e7eb;
    }

    .map-display {
        border: 1px solid #e5e7eb;
        border-radius: 4px;
        overflow: hidden;
    }

    .map-display iframe {
        width: 100%;
        height: 350px;
        border: none;
    }

    .description-text {
        line-height: 1.8;
        color: #374151;
        white-space: pre-wrap;
    }

    .action-bar {
        display: flex;
        gap: 1rem;
        padding: 2rem;
        background: #f9fafb;
        border-radius: 6px;
        flex-wrap: wrap;
        border: 1px solid #e5e7eb;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 4px;
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-approve {
        background: #10B981;
        color: white;
    }

    .btn-approve:hover {
        background: #059669;
        transform: translateY(-1px);
    }

    .btn-reject {
        background: #D60B1C;
        color: white;
    }

    .btn-reject:hover {
        background: #a80816;
        transform: translateY(-1px);
    }

    .btn-edit {
        background: #C59217;
        color: white;
    }

    .btn-edit:hover {
        background: #a3750d;
        transform: translateY(-1px);
    }

    .btn-delete {
        background: rgba(214, 11, 28, 0.1);
        color: #D60B1C;
        margin-left: auto;
    }

    .btn-delete:hover {
        background: rgba(214, 11, 28, 0.2);
    }

    .alert {
        padding: 1rem 1.25rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border-left: 4px solid #059669;
    }

    .alert svg {
        width: 20px;
        height: 20px;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        z-index: 50;
        align-items: center;
        justify-content: center;
    }

    .modal.show {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 6px;
        padding: 2rem;
        max-width: 500px;
        width: 90%;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: #111827;
    }

    .modal-body {
        color: #6b7280;
        margin-bottom: 1.5rem;
    }

    .modal-body strong {
        color: #111827;
    }

    .form-textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        font-family: inherit;
        resize: vertical;
        min-height: 120px;
    }

    .form-textarea:focus {
        outline: none;
        border-color: #022648;
        box-shadow: 0 0 0 3px rgba(11, 19, 84, 0.1);
    }

    .modal-actions {
        display: flex;
        gap: 0.75rem;
        margin-top: 1.5rem;
    }

    .btn-modal-primary {
        flex: 1;
        background: #D60B1C;
        color: white;
        padding: 0.75rem;
        border-radius: 4px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-modal-primary:hover {
        background: #a80816;
    }

    .btn-modal-secondary {
        flex: 1;
        background: #f3f4f6;
        color: #374151;
        padding: 0.75rem;
        border-radius: 4px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-modal-secondary:hover {
        background: #e5e7eb;
    }

    @media (max-width: 768px) {
        .detail-header {
            flex-direction: column;
        }

        .action-bar {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }

        .btn-delete {
            margin-left: 0;
        }
    }
</style>
@endpush

@section('content')
<div class="detail-container">
    <a href="{{ route('admin.katalog.index') }}" class="back-button">
        <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" fill="none" stroke-width="2">
            <path d="M19 12H5M12 19l-7-7 7-7"></path>
        </svg>
        Kembali ke Daftar
    </a>

    @if(session('success'))
        <div class="alert alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="detail-card">
        <div class="detail-header">
            <div class="company-info">
                <h2>{{ $katalog->company_name }}</h2>
                <div class="company-field">{{ $katalog->business_field }}</div>
            </div>
            <div>
                @if($katalog->status === 'pending')
                    <span class="status-badge status-pending">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
                            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/>
                        </svg>
                        Pending
                    </span>
                @elseif($katalog->status === 'approved')
                    <span class="status-badge status-approved">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                        </svg>
                        Approved
                    </span>
                @else
                    <span class="status-badge status-rejected">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                        </svg>
                        Rejected
                    </span>
                @endif
            </div>
        </div>

        @if($katalog->status === 'rejected' && $katalog->rejection_reason)
            <div class="rejection-box">
                <div class="rejection-title">Alasan Penolakan</div>
                <div class="rejection-text">{{ $katalog->rejection_reason }}</div>
            </div>
        @endif

        <!-- Informasi Kontak -->
        <div class="detail-section">
            <div class="section-title">Informasi Kontak</div>
            <div class="info-grid">
                <div class="info-item">
                    <label>Email</label>
                    <div class="value">{{ $katalog->email }}</div>
                </div>
                <div class="info-item">
                    <label>Telepon</label>
                    <div class="value">{{ $katalog->phone }}</div>
                </div>
                <div class="info-item">
                    <label>Anggota</label>
                    <div class="value">{{ $katalog->anggota->nama_pimpinan ?? 'N/A' }}</div>
                </div>
                <div class="info-item">
                    <label>Tanggal Dibuat</label>
                    <div class="value">{{ $katalog->created_at->format('d M Y H:i') }}</div>
                </div>
            </div>
        </div>

        <!-- Alamat -->
        <div class="detail-section">
            <div class="section-title">Alamat</div>
            <div class="value">{{ $katalog->address }}</div>
        </div>

        <!-- Deskripsi -->
        @if($katalog->description)
        <div class="detail-section">
            <div class="section-title">Deskripsi Perusahaan</div>
            <div class="description-text">{{ $katalog->description }}</div>
        </div>
        @endif

        <!-- Logo -->
        @if($katalog->logo)
        <div class="detail-section">
            <div class="section-title">Logo Perusahaan</div>
            <img src="{{ Storage::url($katalog->logo) }}" alt="Logo {{ $katalog->company_name }}" class="logo-display">
        </div>
        @endif

        <!-- Galeri -->
        @if($katalog->images && count($katalog->images) > 0)
        <div class="detail-section">
            <div class="section-title">Galeri Gambar ({{ count($katalog->images) }})</div>
            <div class="gallery-grid">
                @foreach($katalog->images as $image)
                    <img src="{{ Storage::url($image) }}" alt="Gallery" class="gallery-item">
                @endforeach
            </div>
        </div>
        @endif

        <!-- Google Maps -->
        @if($katalog->map_embed_url)
        <div class="detail-section">
            <div class="section-title">Lokasi Google Maps</div>
            <div class="map-display">
                <iframe src="{{ $katalog->map_embed_url }}" allowfullscreen loading="lazy"></iframe>
            </div>
        </div>
        @endif

        <!-- Approval Info -->
        @if($katalog->approved_at)
        <div class="detail-section">
            <div class="section-title">Informasi Approval</div>
            <div class="info-grid">
                <div class="info-item">
                    <label>Disetujui Oleh</label>
                    <div class="value">{{ $katalog->approvedBy->name ?? 'N/A' }}</div>
                </div>
                <div class="info-item">
                    <label>Tanggal Disetujui</label>
                    <div class="value">{{ $katalog->approved_at->format('d M Y H:i') }}</div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Action Bar -->
    <div class="action-bar">
        @if($katalog->status === 'pending')
            <form action="{{ route('admin.katalog.approve', $katalog) }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-approve" onclick="return confirm('Setujui katalog ini untuk tampil di website?')">
                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" fill="none" stroke-width="2">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Setujui Katalog
                </button>
            </form>
            
            <button type="button" class="btn btn-reject" onclick="openRejectModal()">
                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" fill="none" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
                Tolak Katalog
            </button>
        @endif

        <a href="{{ route('admin.katalog.edit', $katalog) }}" class="btn btn-edit">
            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" fill="none" stroke-width="2">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
            </svg>
            Edit
        </a>

        <form action="{{ route('admin.katalog.destroy', $katalog) }}" method="POST" style="display: inline; margin-left: auto;" onsubmit="return confirm('Hapus katalog ini? Tindakan tidak dapat dibatalkan!')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-delete">
                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" fill="none" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                </svg>
                Hapus
            </button>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="modal">
    <div class="modal-content">
        <h3 class="modal-header">Tolak Katalog</h3>
        <div class="modal-body">
            Berikan alasan penolakan untuk <strong>{{ $katalog->company_name }}</strong>
        </div>
        
        <form action="{{ route('admin.katalog.reject', $katalog) }}" method="POST">
            @csrf
            <textarea 
                name="rejection_reason" 
                class="form-textarea"
                placeholder="Contoh: Logo kurang jelas, deskripsi terlalu singkat, informasi kontak tidak lengkap, gambar produk tidak sesuai..."
                required></textarea>
            
            <div class="modal-actions">
                <button type="submit" class="btn-modal-primary">
                    Tolak Katalog
                </button>
                <button type="button" onclick="closeRejectModal()" class="btn-modal-secondary">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openRejectModal() {
    document.getElementById('rejectModal').classList.add('show');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.remove('show');
}

// Close modal when clicking outside
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});
</script>
@endsection