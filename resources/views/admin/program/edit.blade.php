@extends('admin.layouts.admin-layout')

@section('title', 'Edit Program')
@section('page-title', 'Edit Program')

@push('styles')
<style>
    .page-header {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
        font-size: 0.875rem;
    }

    .breadcrumb a {
        color: #6b7280;
        text-decoration: none;
        transition: color 0.2s;
    }

    .breadcrumb a:hover {
        color: #0a2540;
    }

    .breadcrumb-separator {
        color: #d1d5db;
    }

    .breadcrumb-current {
        color: #0a2540;
        font-weight: 600;
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #0a2540;
        margin-bottom: 0.5rem;
    }

    .page-desc {
        color: #6b7280;
        font-size: 0.9375rem;
    }

    .form-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .form-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .form-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #0a2540;
        margin-bottom: 0.25rem;
    }

    .form-subtitle {
        font-size: 0.875rem;
        color: #6b7280;
    }

    .form-body {
        padding: 2rem;
    }

    .form-section {
        margin-bottom: 2rem;
    }

    .form-section:last-child {
        margin-bottom: 0;
    }

    .section-title {
        font-size: 1rem;
        font-weight: 700;
        color: #0a2540;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #f3f4f6;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .form-label.required::after {
        content: " *";
        color: #dc2626;
    }

    .form-input {
        padding: 0.75rem 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.9375rem;
        font-family: 'Montserrat', sans-serif;
        transition: all 0.2s;
        width: 100%;
    }

    .form-input:focus {
        outline: none;
        border-color: #ffd700;
        box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.1);
    }

    .form-input.error {
        border-color: #dc2626;
    }

    .form-select {
        padding: 0.75rem 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.9375rem;
        font-family: 'Montserrat', sans-serif;
        transition: all 0.2s;
        background: white;
        cursor: pointer;
        width: 100%;
    }

    .form-select:focus {
        outline: none;
        border-color: #ffd700;
        box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.1);
    }

    .form-help {
        font-size: 0.8125rem;
        color: #6b7280;
        margin-top: 0.375rem;
    }

    .form-error {
        font-size: 0.8125rem;
        color: #dc2626;
        margin-top: 0.375rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        padding: 1.5rem 2rem;
        border-top: 1px solid #e5e7eb;
        background: #f9fafb;
    }

    .btn-cancel {
        background: white;
        color: #374151;
        border: 1px solid #e5e7eb;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.2s;
        font-family: 'Montserrat', sans-serif;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }

    .btn-cancel:hover {
        background: #f3f4f6;
    }

    .btn-submit {
        background: #0a2540;
        color: white;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.2s;
        font-family: 'Montserrat', sans-serif;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-submit:hover {
        background: #ffd700;
        color: #0a2540;
    }

    .btn-submit svg {
        width: 18px;
        height: 18px;
        stroke: currentColor;
        fill: none;
        stroke-width: 2;
    }

    /* Style for Dynamic Sections */
    .dynamic-section {
        background: #f9fafb;
        padding: 1.5rem;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        margin-top: 1.5rem;
        grid-column: 1 / -1;
    }
    /* Custom Select2 Styling to match form-input */
    .select2-container .select2-selection--single,
    .select2-container .select2-selection--multiple {
        height: auto !important;
        min-height: 42px !important;
        padding: 0.35rem 1rem !important;
        border: 1px solid #e5e7eb !important;
        border-radius: 8px !important;
        font-size: 0.9375rem !important;
        font-family: 'Montserrat', sans-serif !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered,
    .select2-container--default .select2-selection--multiple .select2-selection__rendered {
        line-height: 1.5 !important;
        color: #374151 !important;
        padding-left: 0 !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 100% !important;
        right: 10px !important;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span class="breadcrumb-separator">›</span>
        <a href="{{ route('admin.program.index') }}">Program</a>
        <span class="breadcrumb-separator">›</span>
        <span class="breadcrumb-current">Edit Program</span>
    </div>
    <h1 class="page-title">Edit Program: {{ $program->nama_program }}</h1>
    <p class="page-desc">Ubah detail program kerja CSR atau Bidang Karang Taruna.</p>
</div>

<form action="{{ route('admin.program.update', $program->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="form-container">
        <div class="form-header">
            <h3 class="form-title">Detail Program</h3>
            <p class="form-subtitle">Data yang ditandai dengan (*) wajib diisi</p>
        </div>

        <div class="form-body">
            <div class="form-section">
                <h4 class="section-title">Informasi Dasar</h4>
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label class="form-label required">Nama Program</label>
                        <input type="text" name="nama_program" class="form-input @error('nama_program') error @enderror" value="{{ old('nama_program', $program->nama_program) }}" placeholder="Contoh: Khitanan Massal" required>
                        @error('nama_program')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Kategori</label>
                        <select name="kategori" id="kategori" class="form-select select2-basic @error('kategori') error @enderror" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="CSR" {{ old('kategori', $program->kategori) == 'CSR' ? 'selected' : '' }}>Program CSR (Eksternal)</option>
                            <option value="Bidang" {{ old('kategori', $program->kategori) == 'Bidang' ? 'selected' : '' }}>Program Bidang (Internal)</option>
                        </select>
                        @error('kategori')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>



                    {{-- Dinamis based on Kategori --}}
                    <div id="csr-section" class="dynamic-section" style="display: none;">
                        <div class="form-group">
                            <label class="form-label required">Nama Mitra / Sponsor</label>
                            <input type="text" name="mitra" id="mitra" class="form-input @error('mitra') error @enderror" value="{{ old('mitra', $program->mitra) }}" placeholder="Contoh: PT. Pertamina (Persero)">
                            <span class="form-help">Ketik manual nama pihak luar/donor yang bekerjasama.</span>
                            @error('mitra')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div id="bidang-section" class="dynamic-section" style="display: none;">
                        <div class="form-group">
                            <label class="form-label required">Pilih Bidang (Master Jabatan)</label>
                            <select name="jabatan_id" id="jabatan_id" class="form-select select2-basic @error('jabatan_id') error @enderror" style="width: 100%;">
                                <option value="">-- Pilih Jabatan/Bidang --</option>
                                @foreach($jabatans as $jabatan)
                                    <option value="{{ $jabatan->id }}" {{ old('jabatan_id', $program->jabatan_id) == $jabatan->id ? 'selected' : '' }}>{{ $jabatan->nama_jabatan }}</option>
                                @endforeach
                            </select>
                            <span class="form-help">Pilih dari daftar struktur organisasi yang sudah ada.</span>
                            @error('jabatan_id')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group" style="margin-top: 1.5rem;">
                            <label class="form-label required">Status Pelaksanaan</label>
                            <select name="status" id="status" class="form-select select2-basic @error('status') error @enderror">
                                <option value="Perencanaan" {{ old('status', $program->status) == 'Perencanaan' ? 'selected' : '' }}>Perencanaan</option>
                                <option value="Berjalan" {{ old('status', $program->status) == 'Berjalan' ? 'selected' : '' }}>Berjalan</option>
                                <option value="Selesai" {{ old('status', $program->status) == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                            @error('status')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group" style="margin-top: 1.5rem;">
                            <label class="form-label">Anggaran (Opsional)</label>
                            <input type="number" name="anggaran" class="form-input @error('anggaran') error @enderror" value="{{ old('anggaran', $program->anggaran > 0 ? (int)$program->anggaran : '') }}" min="0" step="1000" placeholder="Contoh: 10000000">
                            <span class="form-help">Masukkan angka saja tanpa titik/koma (Contoh: 10000000 untuk 10 Juta).</span>
                            @error('anggaran')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-section" id="pelaksanaan-section" style="display: none;">
                <h4 class="section-title">Pelaksanaan & Target</h4>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label required">Periode Mulai</label>
                        <input type="text" name="periode_mulai" class="form-input datepicker @error('periode_mulai') error @enderror" value="{{ old('periode_mulai', \Carbon\Carbon::parse($program->periode_mulai)->format('Y-m-d')) }}" required placeholder="Pilih tanggal">
                        @error('periode_mulai')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Periode Selesai</label>
                        <input type="text" name="periode_selesai" class="form-input datepicker @error('periode_selesai') error @enderror" value="{{ old('periode_selesai', \Carbon\Carbon::parse($program->periode_selesai)->format('Y-m-d')) }}" required placeholder="Pilih tanggal">
                        @error('periode_selesai')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label required">PIC / Penanggung Jawab</label>
                        <select name="pic" id="pic-select" class="form-select select2-tags @error('pic') error @enderror" style="width: 100%;" required>
                            <option value="">-- Pilih atau Ketik Nama PIC --</option>
                            @foreach($picOptions as $pic)
                                <option value="{{ $pic }}" {{ old('pic', $program->pic) == $pic ? 'selected' : '' }}>{{ $pic }}</option>
                            @endforeach
                            @if(old('pic', $program->pic) && !$picOptions->contains(old('pic', $program->pic)))
                                <option value="{{ old('pic', $program->pic) }}" selected>{{ old('pic', $program->pic) }}</option>
                            @endif
                        </select>
                        <span class="form-help">Pilih dari daftar anggota, ATAU ketik nama baru lalu tekan Enter (jika PIC orang luar).</span>
                        @error('pic')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label required">Target Output / Deskripsi</label>
                        <textarea name="target_output" rows="4" class="form-input @error('target_output') error @enderror" required placeholder="Jelaskan tujuan dan output yang diharapkan...">{{ old('target_output', $program->target_output) }}</textarea>
                        @error('target_output')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Thumbnail / Gambar Kegiatan</label>
                        
                        @if($program->gambar)
                        <div style="margin-bottom: 1rem;">
                            <p style="font-size: 0.8125rem; color: #6b7280; margin-bottom: 0.5rem;">Gambar saat ini:</p>
                            <img src="{{ $program->gambar_url }}" alt="Current Image" style="max-width: 300px; border-radius: 8px; border: 2px solid #e5e7eb;">
                        </div>
                        @endif

                        <input type="file" name="gambar" class="form-input" accept="image/jpeg,image/jpg,image/png,image/webp" onchange="previewImage(event)">
                        @error('gambar')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                        <div class="form-help">Format: JPG, JPEG, PNG, WEBP. Maksimal 5MB. Kosongkan jika tidak ingin mengubah gambar.</div>
                        <div class="image-preview" id="imagePreview" style="display: none; margin-top: 1rem;">
                            <p style="font-size: 0.8125rem; color: #6b7280; margin-bottom: 0.5rem;">Preview gambar baru:</p>
                            <img src="" alt="Preview" id="previewImg" style="max-width: 300px; border-radius: 8px; border: 2px solid #e5e7eb;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.program.index') }}" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-submit">
                <svg viewBox="0 0 24 24">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
                Perbarui Program
            </button>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize Select2 standard
        $('.select2-basic').select2({
            placeholder: "-- Pilih Data --",
            allowClear: true
        });

        // Initialize Select2 with Tags (Bisa ngetik manual)
        $('.select2-tags').select2({
            placeholder: "-- Pilih atau Ketik Baru --",
            tags: true,
            createTag: function (params) {
                var term = $.trim(params.term);
                if (term === '') {
                    return null;
                }
                return {
                    id: term,
                    text: term,
                    newTag: true
                }
            }
        });

        // Logic memunculkan input dinamis berdasarkan Kategori
        const csrSection = document.getElementById('csr-section');
        const bidangSection = document.getElementById('bidang-section');
        const pelaksanaanSection = document.getElementById('pelaksanaan-section');
        const mitraInput = document.getElementById('mitra');
        const jabatanInput = document.getElementById('jabatan_id');
        
        function toggleSections() {
            const selected = $('#kategori').val();
            if (selected === 'CSR') {
                csrSection.style.display = 'block';
                bidangSection.style.display = 'none';
                if(pelaksanaanSection) pelaksanaanSection.style.display = 'block';
                
                mitraInput.setAttribute('required', 'required');
                jabatanInput.removeAttribute('required');
            } else if (selected === 'Bidang') {
                csrSection.style.display = 'none';
                bidangSection.style.display = 'block';
                if(pelaksanaanSection) pelaksanaanSection.style.display = 'block';
                
                mitraInput.removeAttribute('required');
                jabatanInput.setAttribute('required', 'required');
            } else {
                csrSection.style.display = 'none';
                bidangSection.style.display = 'none';
                if(pelaksanaanSection) pelaksanaanSection.style.display = 'none';
                
                mitraInput.removeAttribute('required');
                jabatanInput.removeAttribute('required');
            }
        }

        const picSelect = $('#pic-select');
        let currentPic = "{{ old('pic', $program->pic) }}";

        function fetchPics(jabatanId = '') {
            $.ajax({
                url: "{{ route('admin.program.get-pics') }}",
                type: "GET",
                data: { jabatan_id: jabatanId },
                success: function(res) {
                    picSelect.empty();
                    picSelect.append('<option value="">-- Pilih atau Ketik Nama PIC --</option>');
                    
                    let found = false;
                    res.forEach(function(name) {
                        let selected = (currentPic == name) ? 'selected' : '';
                        if(currentPic == name) found = true;
                        picSelect.append('<option value="'+name+'" '+selected+'>'+name+'</option>');
                    });
                    
                    if(currentPic && !found) {
                        picSelect.append('<option value="'+currentPic+'" selected>'+currentPic+'</option>');
                    }
                }
            });
        }

        $('#jabatan_id').on('change', function() {
            if ($('#kategori').val() === 'Bidang') {
                fetchPics($(this).val());
            }
        });

        $('#kategori').on('change', function() {
            toggleSections();
            if($(this).val() === 'CSR') {
                fetchPics(''); 
            } else if ($(this).val() === 'Bidang') {
                fetchPics($('#jabatan_id').val());
            }
        });

        toggleSections(); // run on load
    });

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
