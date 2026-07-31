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
        margin-bottom: 1.5rem;
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
    .form-select:focus {
        outline: none;
        border-color: #022648;
        box-shadow: 0 0 0 3px rgba(11, 19, 84, 0.1);
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

    .image-preview {
        margin-top: 1rem;
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

    /* Full-Screen Editor Modal Styles */
    .editor-modal-trigger-wrapper {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 0.75rem;
        flex-wrap: wrap;
    }
    .btn-open-editor {
        background: #022648;
        color: #ffffff;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.9375rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.25s ease;
        font-family: 'Montserrat', sans-serif;
        box-shadow: 0 2px 5px rgba(2, 38, 72, 0.2);
    }
    .btn-open-editor:hover {
        background: #043666;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(2, 38, 72, 0.3);
    }
    .editor-status-badge {
        font-size: 0.8125rem;
        font-weight: 600;
        color: #6b7280;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 0.75rem;
        background: #f3f4f6;
        border-radius: 20px;
    }
    .editor-status-badge.has-content {
        background: #ecfdf5;
        color: #059669;
    }
    .konten-preview-box {
        border: 1px solid #d1d5db;
        border-radius: 6px;
        padding: 1rem 1.25rem;
        background: #fafafa;
        max-height: 180px;
        overflow-y: auto;
        font-size: 0.875rem;
        color: #374151;
        line-height: 1.5;
    }
    .konten-preview-box .preview-placeholder {
        color: #9ca3af;
        font-style: italic;
    }

    /* Modal Overlay & Container */
    .editor-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(2, 38, 72, 0.75);
        backdrop-filter: blur(4px);
        z-index: 99999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        box-sizing: border-box;
    }
    .editor-modal-overlay.active {
        display: flex;
    }
    .editor-modal-container {
        background: #ffffff;
        width: 95vw;
        height: 92vh;
        border-radius: 10px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        animation: modalSlideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes modalSlideUp {
        from {
            opacity: 0;
            transform: translateY(20px) scale(0.98);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    .editor-modal-header {
        background: #022648;
        color: #ffffff;
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 3px solid #b7830f;
    }
    .editor-modal-title {
        font-weight: 700;
        font-size: 1.125rem;
        display: flex;
        align-items: center;
        gap: 0.625rem;
        font-family: 'Montserrat', sans-serif;
    }
    .editor-modal-close {
        background: transparent;
        border: none;
        color: #ffffff;
        font-size: 1.75rem;
        cursor: pointer;
        line-height: 1;
        padding: 0 0.5rem;
        opacity: 0.8;
        transition: opacity 0.2s;
    }
    .editor-modal-close:hover {
        opacity: 1;
    }
    .editor-modal-body {
        flex: 1;
        padding: 1rem 1.5rem;
        overflow-y: auto;
        background: #f8fafc;
        display: flex;
        flex-direction: column;
    }
    .editor-modal-body .ck-editor {
        display: flex;
        flex-direction: column;
        flex: 1;
        height: 100%;
    }
    .editor-modal-body .ck-editor__main {
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .editor-modal-body .ck-editor__editable_inline {
        flex: 1;
        min-height: 400px !important;
        background: #ffffff;
    }
    .editor-modal-footer {
        padding: 0.875rem 1.5rem;
        background: #ffffff;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
    }
    .btn-modal-cancel {
        padding: 0.625rem 1.25rem;
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        font-family: 'Montserrat', sans-serif;
    }
    .btn-modal-cancel:hover {
        background: #e2e8f0;
    }
    .btn-modal-save {
        padding: 0.625rem 1.5rem;
        background: #b7830f;
        color: #ffffff;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
        font-family: 'Montserrat', sans-serif;
    }
    .btn-modal-save:hover {
        background: #966b0c;
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
    <i class="fa fa-arrow-left"></i> Kembali ke Daftar Berita
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
            <div class="form-help">Judul akan otomatis dijadikan URL (slug)</div>
        </div>

        <div class="form-group">
            <label class="form-label">Kategori <span class="required">*</span></label>
            <select name="kategori" id="kategoriSelect" class="form-select" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($kategoris as $kat)
                    <option value="{{ $kat }}" {{ old('kategori', $berita->kategori) == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                @endforeach
            </select>
            @error('kategori')
            <div class="error-message">{{ $message }}</div>
            @enderror
            <div class="form-help">Pilih kategori yang ada atau ketik langsung kategori baru lalu tekan Enter</div>
        </div>

        <div class="form-group">
            <label class="form-label">Tags (Opsional)</label>
            <input type="text" name="tags" class="form-input" value="{{ old('tags', is_array($berita->tags) ? implode(', ', $berita->tags) : $berita->tags) }}"
                placeholder="Misal: karang taruna, pemuda, kegiatan">
            <div class="form-help">Pisahkan dengan koma (,)</div>
        </div>

        <div class="form-group">
            <label class="form-label">
                Konten Berita <span class="required">*</span>
            </label>
            
            <div class="editor-modal-trigger-wrapper">
                <button type="button" class="btn-open-editor" onclick="openEditorModal()">
                    <i class="fa fa-pencil-square-o"></i> Tulis / Edit Konten Berita (Layar Penuh)
                </button>
                <span class="editor-status-badge" id="editorStatusBadge">
                    <i class="fa fa-circle-o"></i> Konten belum diisi
                </span>
            </div>

            <div class="konten-preview-box" id="kontenPreviewBox">
                <div class="preview-placeholder">Klik tombol di atas untuk membuka editor konten berita layar penuh.</div>
            </div>

            <textarea name="konten" id="editorKontenHidden" style="display:none;" required>{{ old('konten', $berita->konten) }}</textarea>
            @error('konten')
            <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">
                Gambar Utama
            </label>
            <input type="file" name="gambar" class="form-input" accept="image/jpeg,image/jpg,image/png,image/webp"
                onchange="previewImage(event)">
            @error('gambar')
            <div class="error-message">{{ $message }}</div>
            @enderror
            <div class="form-help">Biarkan kosong jika tidak ingin mengubah gambar. Format: JPG, JPEG, PNG, WEBP. Maksimal 2MB.</div>

            @if($berita->gambar)
                <div class="image-preview" id="imagePreview" style="display: block;">
                    <img src="{{ asset('storage/' . $berita->gambar) }}" alt="Preview" id="previewImg">
                </div>
            @else
                <div class="image-preview" id="imagePreview" style="display: none;">
                    <img src="" alt="Preview" id="previewImg">
                </div>
            @endif
        </div>

        <div class="form-group">
            <label class="form-label">
                Jadwal Tayang <span class="required">*</span>
            </label>
            <input type="text" name="tanggal_publish" class="form-input datetimepicker"
                value="{{ old('tanggal_publish', $berita->tanggal_publish ? $berita->tanggal_publish->format('Y-m-d H:i') : date('Y-m-d H:i')) }}" required placeholder="Pilih tanggal & jam tayang">
            @error('tanggal_publish')
            <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Status Post <span class="required">*</span></label>
            <select name="status" id="statusSelect" class="form-select" required>
                <option value="Draft" {{ old('status', $berita->status) == 'Draft' ? 'selected' : '' }}>Draft (Simpan sementara)</option>
                <option value="Published" {{ old('status', $berita->status) == 'Published' ? 'selected' : '' }}>Published (Publikasikan sesuai jadwal)</option>
                <option value="Archived" {{ old('status', $berita->status) == 'Archived' ? 'selected' : '' }}>Archived (Arsipkan)</option>
            </select>
            @error('status')
            <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Tingkat Wilayah Berita <span class="required">*</span></label>
            @if($admin->isPPKT())
                <input type="text" class="form-input" value="{{ $admin->domisili }}" readonly style="background: #f3f4f6; color: #022648; font-weight: 700;">
                <input type="hidden" name="wilayah" value="{{ $admin->domisili }}">
                <div class="form-help">Wilayah otomatis dikunci sesuai Domisili Sekretariat Provinsi Anda</div>
            @else
                <input type="text" name="wilayah" class="form-input" value="{{ old('wilayah', $berita->wilayah ?? 'Nasional') }}" placeholder="Contoh: Nasional / Jawa Barat / DKI Jakarta">
                <div class="form-help">Ketik 'Nasional' atau nama Provinsi spesifik</div>
            @endif
        </div>

        <div class="form-group">
            <label class="form-label">Pengaturan Berita</label>
            <div class="checkbox-group">
                @if($admin->isPPKT())
                    <input type="checkbox" disabled id="is_populer_disabled">
                    <label for="is_populer_disabled" style="color: #9ca3af; cursor: not-allowed;">
                        Tandai sebagai Berita Populer <span style="font-size: 0.75rem; color: #dc2626;">(Khusus Tingkat Nasional)</span>
                    </label>
                @else
                    <input type="checkbox" name="is_populer" id="is_populer" value="1"
                        {{ old('is_populer', $berita->is_populer) ? 'checked' : '' }}>
                    <label for="is_populer">Tandai sebagai Berita Populer / Sematkan ke Berita Nasional</label>
                @endif
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

<!-- Full-Screen Editor Modal -->
<div class="editor-modal-overlay" id="editorModalOverlay">
    <div class="editor-modal-container">
        <div class="editor-modal-header">
            <div class="editor-modal-title">
                <i class="fa fa-edit"></i> Editor Konten Berita (Mode Layar Penuh)
            </div>
            <button type="button" class="editor-modal-close" onclick="closeEditorModal()">&times;</button>
        </div>
        <div class="editor-modal-body">
            <textarea id="editorKontenModal">{{ old('konten', $berita->konten) }}</textarea>
        </div>
        <div class="editor-modal-footer">
            <button type="button" class="btn-modal-cancel" onclick="closeEditorModal()">Batal</button>
            <button type="button" class="btn-modal-save" onclick="saveEditorContent()">
                <i class="fa fa-check"></i> Selesai & Terapkan Konten
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    let modalEditorInstance = null;

    function updatePreviewAndStatus() {
        const rawContent = $('#editorKontenHidden').val();
        const $previewBox = $('#kontenPreviewBox');
        const $statusBadge = $('#editorStatusBadge');
        
        if (rawContent && rawContent.trim() !== '' && rawContent.trim() !== '<p></p>') {
            $previewBox.html(rawContent);
            const textLen = rawContent.replace(/<[^>]*>?/gm, '').length;
            $statusBadge.addClass('has-content').html('<i class="fa fa-check-circle"></i> Konten terisi (' + textLen + ' Karakter)');
        } else {
            $previewBox.html('<div class="preview-placeholder">Klik tombol di atas untuk membuka editor konten berita layar penuh.</div>');
            $statusBadge.removeClass('has-content').html('<i class="fa fa-circle-o"></i> Konten belum diisi');
        }
    }

    function openEditorModal() {
        $('#editorModalOverlay').addClass('active');
        $('body').css('overflow', 'hidden');
        
        if (modalEditorInstance) {
            modalEditorInstance.setData($('#editorKontenHidden').val());
        }
    }

    function closeEditorModal() {
        $('#editorModalOverlay').removeClass('active');
        $('body').css('overflow', '');
    }

    function saveEditorContent() {
        if (modalEditorInstance) {
            const content = modalEditorInstance.getData();
            $('#editorKontenHidden').val(content);
            updatePreviewAndStatus();
        }
        closeEditorModal();
        if (typeof Toast !== 'undefined') {
            Toast.fire({ icon: 'success', title: 'Konten berita diterapkan ke form!' });
        }
    }

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

    class MyUploadAdapter {
        constructor(loader) {
            this.loader = loader;
        }

        upload() {
            return this.loader.file
                .then(file => new Promise((resolve, reject) => {
                    const data = new FormData();
                    data.append('file', file);
                    data.append('_token', '{{ csrf_token() }}');

                    $.ajax({
                        url: "{{ route('admin.berita.upload_image') }}",
                        type: "POST",
                        data: data,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            if (response.url) {
                                resolve({ default: response.url });
                            } else {
                                reject(response.error || 'Upload gagal');
                            }
                        },
                        error: function() {
                            reject('Gagal mengunggah gambar ke server');
                        }
                    });
                }));
        }

        abort() {}
    }

    function MyCustomUploadAdapterPlugin(editor) {
        editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
            return new MyUploadAdapter(loader);
        };
    }

    document.addEventListener('DOMContentLoaded', function() {
        if(typeof flatpickr !== 'undefined') {
            flatpickr(".datetimepicker", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true
            });
        }
        
        if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
            $('#kategoriSelect').select2({
                tags: true,
                placeholder: 'Pilih atau ketik kategori baru...',
                width: '100%',
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

            $('#statusSelect').select2({
                minimumResultsForSearch: Infinity,
                width: '100%'
            });
        }

        // Initialize CKEditor 5 on Modal Textarea
        ClassicEditor
            .create(document.querySelector('#editorKontenModal'), {
                extraPlugins: [MyCustomUploadAdapterPlugin],
                toolbar: {
                    items: [
                        'heading', '|',
                        'bold', 'italic', 'underline', 'strikethrough', 'link', '|',
                        'bulletedList', 'numberedList', 'outdent', 'indent', '|',
                        'imageUpload', 'blockQuote', 'insertTable', 'mediaEmbed', 'undo', 'redo'
                    ]
                }
            })
            .then(editor => {
                modalEditorInstance = editor;
                updatePreviewAndStatus();
            })
            .catch(error => {
                console.error('CKEditor error:', error);
            });
    });
</script>
@endpush
@endsection