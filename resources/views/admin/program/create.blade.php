@extends('admin.layouts.admin-layout')

@section('title', 'Tambah Program')

@section('content')
<div class="header">
    <div class="header-text">
        <h1>Tambah Program</h1>
        <p>Tambahkan data program kerja CSR atau Bidang Karang Taruna.</p>
    </div>
</div>

<div class="card" style="margin-top: 20px;">
    <div class="card-body">
        <form action="{{ route('admin.program.store') }}" method="POST">
            @csrf

            <div class="form-group" style="margin-bottom: 20px;">
                <label for="nama_program" style="display:block; margin-bottom: 8px; font-weight: 500;">Nama Program <span class="text-danger">*</span></label>
                <input type="text" name="nama_program" id="nama_program" class="form-control" value="{{ old('nama_program') }}" required placeholder="Contoh: Khitanan Massal">
                @error('nama_program')
                    <span class="text-danger" style="font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-row" style="display: flex; gap: 20px; margin-bottom: 20px;">
                <div class="form-group" style="flex: 1;">
                    <label for="kategori" style="display:block; margin-bottom: 8px; font-weight: 500;">Kategori <span class="text-danger">*</span></label>
                    <select name="kategori" id="kategori" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="CSR" {{ old('kategori') == 'CSR' ? 'selected' : '' }}>Program CSR (Eksternal)</option>
                        <option value="Bidang" {{ old('kategori') == 'Bidang' ? 'selected' : '' }}>Program Bidang (Internal)</option>
                    </select>
                    @error('kategori')
                        <span class="text-danger" style="font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group" style="flex: 1;">
                    <label for="status" style="display:block; margin-bottom: 8px; font-weight: 500;">Status <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="Perencanaan" {{ old('status') == 'Perencanaan' ? 'selected' : '' }}>Perencanaan</option>
                        <option value="Berjalan" {{ old('status') == 'Berjalan' ? 'selected' : '' }}>Berjalan</option>
                        <option value="Selesai" {{ old('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    @error('status')
                        <span class="text-danger" style="font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Dinamis based on Kategori --}}
            <div id="csr-section" style="display: none; margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 8px; background-color: #f9f9f9;">
                <label for="mitra" style="display:block; margin-bottom: 8px; font-weight: 500;">Nama Mitra / Sponsor (Khusus CSR) <span class="text-danger">*</span></label>
                <input type="text" name="mitra" id="mitra" class="form-control" value="{{ old('mitra') }}" placeholder="Contoh: PT. Pertamina (Persero)">
                <small style="color: #666; display: block; margin-top: 5px;">Ketik manual nama pihak luar/donor yang bekerjasama.</small>
                @error('mitra')
                    <span class="text-danger" style="font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>

            <div id="bidang-section" style="display: none; margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 8px; background-color: #f9f9f9;">
                <label for="jabatan_id" style="display:block; margin-bottom: 8px; font-weight: 500;">Pilih Bidang (Khusus Program Bidang) <span class="text-danger">*</span></label>
                <select name="jabatan_id" id="jabatan_id" class="form-control select2" style="width: 100%;">
                    <option value="">-- Pilih Jabatan/Bidang --</option>
                    @foreach($jabatans as $jabatan)
                        <option value="{{ $jabatan->id }}" {{ old('jabatan_id') == $jabatan->id ? 'selected' : '' }}>{{ $jabatan->nama_jabatan }}</option>
                    @endforeach
                </select>
                <small style="color: #666; display: block; margin-top: 5px;">Pilih dari master jabatan organisasi.</small>
                @error('jabatan_id')
                    <span class="text-danger" style="font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>
            {{-- End Dinamis --}}

            <div class="form-row" style="display: flex; gap: 20px; margin-bottom: 20px;">
                <div class="form-group" style="flex: 1;">
                    <label for="periode_mulai" style="display:block; margin-bottom: 8px; font-weight: 500;">Periode Mulai <span class="text-danger">*</span></label>
                    <input type="date" name="periode_mulai" id="periode_mulai" class="form-control" value="{{ old('periode_mulai') }}" required>
                    @error('periode_mulai')
                        <span class="text-danger" style="font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group" style="flex: 1;">
                    <label for="periode_selesai" style="display:block; margin-bottom: 8px; font-weight: 500;">Periode Selesai <span class="text-danger">*</span></label>
                    <input type="date" name="periode_selesai" id="periode_selesai" class="form-control" value="{{ old('periode_selesai') }}" required>
                    @error('periode_selesai')
                        <span class="text-danger" style="font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label for="pic" style="display:block; margin-bottom: 8px; font-weight: 500;">PIC / Penanggung Jawab <span class="text-danger">*</span></label>
                <select name="pic" id="pic" class="form-control select2-tags" style="width: 100%;" required>
                    <option value="">-- Pilih atau Ketik Nama PIC --</option>
                    @foreach($anggotas as $anggota)
                        <option value="{{ $anggota->nama_lengkap }}" {{ old('pic') == $anggota->nama_lengkap ? 'selected' : '' }}>{{ $anggota->nama_lengkap }}</option>
                    @endforeach
                    @if(old('pic') && !$anggotas->contains('nama_lengkap', old('pic')))
                        <option value="{{ old('pic') }}" selected>{{ old('pic') }}</option>
                    @endif
                </select>
                <small style="color: #666; display: block; margin-top: 5px;">Pilih dari daftar anggota, ATAU ketik nama baru lalu tekan Enter (jika PIC orang luar).</small>
                @error('pic')
                    <span class="text-danger" style="font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label for="target_output" style="display:block; margin-bottom: 8px; font-weight: 500;">Target Output / Deskripsi Singkat <span class="text-danger">*</span></label>
                <textarea name="target_output" id="target_output" rows="4" class="form-control" required placeholder="Jelaskan tujuan dan output yang diharapkan dari program ini...">{{ old('target_output') }}</textarea>
                @error('target_output')
                    <span class="text-danger" style="font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: 30px;">
                <label for="anggaran" style="display:block; margin-bottom: 8px; font-weight: 500;">Anggaran (Opsional)</label>
                <input type="number" name="anggaran" id="anggaran" class="form-control" value="{{ old('anggaran') }}" min="0" step="1000" placeholder="Contoh: 10000000">
                <small style="color: #666; display: block; margin-top: 5px;">Masukkan angka saja tanpa titik/koma (Contoh: 10000000 untuk 10 Juta).</small>
                @error('anggaran')
                    <span class="text-danger" style="font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="display: flex; gap: 15px;">
                <button type="submit" class="btn-yellow-border-black" style="padding: 12px 25px;">
                    <i class="fa fa-save"></i> Simpan Program
                </button>
                <a href="{{ route('admin.program.index') }}" class="btn-transparent-border-black" style="padding: 12px 25px; text-decoration: none;">
                    Batal
                </a>
            </div>

        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize Select2 standard
        $('.select2').select2({
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
                    newTag: true // add additional parameters
                }
            }
        });

        // Logic memunculkan input dinamis berdasarkan Kategori
        const kategoriSelect = document.getElementById('kategori');
        const csrSection = document.getElementById('csr-section');
        const bidangSection = document.getElementById('bidang-section');
        
        function toggleSections() {
            const selected = kategoriSelect.value;
            if (selected === 'CSR') {
                csrSection.style.display = 'block';
                bidangSection.style.display = 'none';
                
                // Toggle required
                document.getElementById('mitra').setAttribute('required', 'required');
                document.getElementById('jabatan_id').removeAttribute('required');
            } else if (selected === 'Bidang') {
                csrSection.style.display = 'none';
                bidangSection.style.display = 'block';
                
                // Toggle required
                document.getElementById('mitra').removeAttribute('required');
                document.getElementById('jabatan_id').setAttribute('required', 'required');
            } else {
                csrSection.style.display = 'none';
                bidangSection.style.display = 'none';
                
                document.getElementById('mitra').removeAttribute('required');
                document.getElementById('jabatan_id').removeAttribute('required');
            }
        }

        kategoriSelect.addEventListener('change', toggleSections);
        
        // Panggil saat page load pertama kali (untuk handle validasi failed / old value)
        toggleSections();
    });
</script>
@endpush
