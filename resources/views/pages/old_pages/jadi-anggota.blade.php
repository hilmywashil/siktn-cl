@extends('layouts.app')

@section('title', 'Jadi Anggota - ASITA Jawa Barat')
{{-- Success/Error Messages --}}
@if(session('success'))
<div class="alert alert-success">
    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none">
        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
        <polyline points="22 4 12 14.01 9 11.01"></polyline>
    </svg>
    <span>{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div class="alert alert-error">
    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none">
        <circle cx="12" cy="12" r="10"></circle>
        <line x1="15" y1="9" x2="9" y2="15"></line>
        <line x1="9" y1="9" x2="15" y2="15"></line>
    </svg>
    <span>{{ session('error') }}</span>
</div>
@endif

@if($errors->any())
<div class="alert alert-error">
    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none">
        <circle cx="12" cy="12" r="10"></circle>
        <line x1="12" y1="8" x2="12" y2="12"></line>
        <line x1="12" y1="16" x2="12.01" y2="16"></line>
    </svg>
    <div>
        <strong>Ada beberapa kesalahan:</strong>
        <ul style="margin: 10px 0 0 20px; padding: 0;">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

@section('content')
<!-- Include Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


<section class="ja-page-banner">
    <h1>Jadi Anggota</h1>
    <p>Formulir Pendaftaran Calon Anggota Baru ASITA Jawa Barat</p>
    <div class="rules">
        <p>Data yang masuk hanya akan dipergunakan untuk kepentingan proses rekrutmen anggota baru ASITA Jawa Barat.
            Harap isi dengan benar dan sebaik-baiknya.</p>
        <h3>Syarat & Ketentuan Pendaftaran</h3>
        <p>1. Pengusaha Pria/Wanita berusia 17-40 Tahun<br>2. Usaha harus memiliki badan hukum berbentuk PT, CV, atau PT
            Perorangan<br>3. Pemilik atau Usaha berdomisili di Jawa Barat</p>
        <h3>Tahapan Seleksi</h3>
        <p>1. Tahap Administrasi<br>2. Tahap Interview<br>3. Tahap Verifikasi Lapangan</p>
    </div>

    <!-- Stepper Progress -->
    <div class="stepper-container">
        <div class="stepper-wrapper">
            <div class="stepper-item active" data-step="1">
                <div class="stepper-circle"></div>
                <div class="stepper-label">Data Pribadi</div>
            </div>
            <div class="stepper-line" data-line="1"></div>
            <div class="stepper-item" data-step="2">
                <div class="stepper-circle"></div>
                <div class="stepper-label">Profile Perusahaan</div>
            </div>
            <div class="stepper-line" data-line="2"></div>
            <div class="stepper-item" data-step="3">
                <div class="stepper-circle"></div>
                <div class="stepper-label">Organisasi</div>
            </div>
            <div class="stepper-line" data-line="3"></div>
            <div class="stepper-item" data-step="4">
                <div class="stepper-circle"></div>
                <div class="stepper-label">Daftar</div>
            </div>
        </div>
    </div>

    <!-- Form Multi-Step -->
    <div class="form-section">
        <form action="{{ route('jadi-anggota.store') }}" method="POST" enctype="multipart/form-data" id="multiStepForm">
            @csrf

            <!-- Step 1: Data Pribadi -->
            <div class="form-step active" data-step="1">
                <h2 class="form-title">Data Pribadi</h2>

                <div class="form-row">
                    <div class="form-group">
                        <label for="nama_usaha">Nama Lengkap<span class="required">*</span></label>
                        <input type="text" id="nama_usaha" name="nama_usaha" class="form-control" value="{{ old('nama_usaha') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Jenis Kelamin<span class="required">*</span></label>
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio" name="jenis_kelamin" value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'checked' : '' }} required>
                                <span>Laki-laki</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="jenis_kelamin" value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'checked' : '' }} required>
                                <span>Perempuan</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="tempat_lahir">Tempat lahir<span class="required">*</span></label>
                        <select id="tempat_lahir" name="tempat_lahir" class="form-control" required>
                            <option value="">Pilih Kota/Kabupaten</option>

                            {{-- KABUPATEN (18) --}}
                            <optgroup label="Kabupaten">
                                <option value="Bandung" {{ old('tempat_lahir') == 'Bandung' ? 'selected' : '' }}>Kabupaten Bandung</option>
                                <option value="Bandung Barat" {{ old('tempat_lahir') == 'Bandung Barat' ? 'selected' : '' }}>Kabupaten Bandung Barat</option>
                                <option value="Bekasi" {{ old('tempat_lahir') == 'Bekasi' ? 'selected' : '' }}>Kabupaten Bekasi</option>
                                <option value="Bogor" {{ old('tempat_lahir') == 'Bogor' ? 'selected' : '' }}>Kabupaten Bogor</option>
                                <option value="Ciamis" {{ old('tempat_lahir') == 'Ciamis' ? 'selected' : '' }}>Kabupaten Ciamis</option>
                                <option value="Cianjur" {{ old('tempat_lahir') == 'Cianjur' ? 'selected' : '' }}>Kabupaten Cianjur</option>
                                <option value="Cirebon" {{ old('tempat_lahir') == 'Cirebon' ? 'selected' : '' }}>Kabupaten Cirebon</option>
                                <option value="Garut" {{ old('tempat_lahir') == 'Garut' ? 'selected' : '' }}>Kabupaten Garut</option>
                                <option value="Indramayu" {{ old('tempat_lahir') == 'Indramayu' ? 'selected' : '' }}>Kabupaten Indramayu</option>
                                <option value="Karawang" {{ old('tempat_lahir') == 'Karawang' ? 'selected' : '' }}>Kabupaten Karawang</option>
                                <option value="Kuningan" {{ old('tempat_lahir') == 'Kuningan' ? 'selected' : '' }}>Kabupaten Kuningan</option>
                                <option value="Majalengka" {{ old('tempat_lahir') == 'Majalengka' ? 'selected' : '' }}>Kabupaten Majalengka</option>
                                <option value="Pangandaran" {{ old('tempat_lahir') == 'Pangandaran' ? 'selected' : '' }}>Kabupaten Pangandaran</option>
                                <option value="Purwakarta" {{ old('tempat_lahir') == 'Purwakarta' ? 'selected' : '' }}>Kabupaten Purwakarta</option>
                                <option value="Subang" {{ old('tempat_lahir') == 'Subang' ? 'selected' : '' }}>Kabupaten Subang</option>
                                <option value="Sukabumi" {{ old('tempat_lahir') == 'Sukabumi' ? 'selected' : '' }}>Kabupaten Sukabumi</option>
                                <option value="Sumedang" {{ old('tempat_lahir') == 'Sumedang' ? 'selected' : '' }}>Kabupaten Sumedang</option>
                                <option value="Tasikmalaya" {{ old('tempat_lahir') == 'Tasikmalaya' ? 'selected' : '' }}>Kabupaten Tasikmalaya</option>
                            </optgroup>

                            {{-- KOTA (9) --}}
                            <optgroup label="Kota">
                                <option value="Kota Bandung" {{ old('tempat_lahir') == 'Kota Bandung' ? 'selected' : '' }}>Kota Bandung</option>
                                <option value="Kota Banjar" {{ old('tempat_lahir') == 'Kota Banjar' ? 'selected' : '' }}>Kota Banjar</option>
                                <option value="Kota Bekasi" {{ old('tempat_lahir') == 'Kota Bekasi' ? 'selected' : '' }}>Kota Bekasi</option>
                                <option value="Kota Bogor" {{ old('tempat_lahir') == 'Kota Bogor' ? 'selected' : '' }}>Kota Bogor</option>
                                <option value="Kota Cimahi" {{ old('tempat_lahir') == 'Kota Cimahi' ? 'selected' : '' }}>Kota Cimahi</option>
                                <option value="Kota Cirebon" {{ old('tempat_lahir') == 'Kota Cirebon' ? 'selected' : '' }}>Kota Cirebon</option>
                                <option value="Kota Depok" {{ old('tempat_lahir') == 'Kota Depok' ? 'selected' : '' }}>Kota Depok</option>
                                <option value="Kota Sukabumi" {{ old('tempat_lahir') == 'Kota Sukabumi' ? 'selected' : '' }}>Kota Sukabumi</option>
                                <option value="Kota Tasikmalaya" {{ old('tempat_lahir') == 'Kota Tasikmalaya' ? 'selected' : '' }}>Kota Tasikmalaya</option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_lahir">Tanggal Lahir<span class="required">*</span></label>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir') }}" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="agama">Agama<span class="required">*</span></label>
                        <select id="agama" name="agama" class="form-control" required>
                            <option value="">Pilih Agama</option>
                            <option value="Islam" {{ old('agama') == 'Islam' ? 'selected' : '' }}>Islam</option>
                            <option value="Kristen" {{ old('agama') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                            <option value="Katolik" {{ old('agama') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                            <option value="Hindu" {{ old('agama') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                            <option value="Buddha" {{ old('agama') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                            <option value="Konghucu" {{ old('agama') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="nomor_telepon">Nomor Telepon (yang terhubung dengan WhatsApp)<span class="required">*</span></label>
                        <input type="tel" id="nomor_telepon" name="nomor_telepon" class="form-control" value="{{ old('nomor_telepon') }}" placeholder="Contoh: 081234567890" required>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label for="domisili">Domisili<span class="required">*</span></label>
                    <select id="domisili" name="domisili" class="form-control" required>
                        <option value="">Pilih Kabupaten/Kota</option>

                        {{-- KABUPATEN (18) --}}
                        <optgroup label="Kabupaten">
                            <option value="Bandung" {{ old('domisili') == 'Bandung' ? 'selected' : '' }}>Kabupaten Bandung</option>
                            <option value="Bandung Barat" {{ old('domisili') == 'Bandung Barat' ? 'selected' : '' }}>Kabupaten Bandung Barat</option>
                            <option value="Bekasi" {{ old('domisili') == 'Bekasi' ? 'selected' : '' }}>Kabupaten Bekasi</option>
                            <option value="Bogor" {{ old('domisili') == 'Bogor' ? 'selected' : '' }}>Kabupaten Bogor</option>
                            <option value="Ciamis" {{ old('domisili') == 'Ciamis' ? 'selected' : '' }}>Kabupaten Ciamis</option>
                            <option value="Cianjur" {{ old('domisili') == 'Cianjur' ? 'selected' : '' }}>Kabupaten Cianjur</option>
                            <option value="Cirebon" {{ old('domisili') == 'Cirebon' ? 'selected' : '' }}>Kabupaten Cirebon</option>
                            <option value="Garut" {{ old('domisili') == 'Garut' ? 'selected' : '' }}>Kabupaten Garut</option>
                            <option value="Indramayu" {{ old('domisili') == 'Indramayu' ? 'selected' : '' }}>Kabupaten Indramayu</option>
                            <option value="Karawang" {{ old('domisili') == 'Karawang' ? 'selected' : '' }}>Kabupaten Karawang</option>
                            <option value="Kuningan" {{ old('domisili') == 'Kuningan' ? 'selected' : '' }}>Kabupaten Kuningan</option>
                            <option value="Majalengka" {{ old('domisili') == 'Majalengka' ? 'selected' : '' }}>Kabupaten Majalengka</option>
                            <option value="Pangandaran" {{ old('domisili') == 'Pangandaran' ? 'selected' : '' }}>Kabupaten Pangandaran</option>
                            <option value="Purwakarta" {{ old('domisili') == 'Purwakarta' ? 'selected' : '' }}>Kabupaten Purwakarta</option>
                            <option value="Subang" {{ old('domisili') == 'Subang' ? 'selected' : '' }}>Kabupaten Subang</option>
                            <option value="Sukabumi" {{ old('domisili') == 'Sukabumi' ? 'selected' : '' }}>Kabupaten Sukabumi</option>
                            <option value="Sumedang" {{ old('domisili') == 'Sumedang' ? 'selected' : '' }}>Kabupaten Sumedang</option>
                            <option value="Tasikmalaya" {{ old('domisili') == 'Tasikmalaya' ? 'selected' : '' }}>Kabupaten Tasikmalaya</option>
                        </optgroup>

                        {{-- KOTA (9) --}}
                        <optgroup label="Kota">
                            <option value="Kota Bandung" {{ old('domisili') == 'Kota Bandung' ? 'selected' : '' }}>Kota Bandung</option>
                            <option value="Kota Banjar" {{ old('domisili') == 'Kota Banjar' ? 'selected' : '' }}>Kota Banjar</option>
                            <option value="Kota Bekasi" {{ old('domisili') == 'Kota Bekasi' ? 'selected' : '' }}>Kota Bekasi</option>
                            <option value="Kota Bogor" {{ old('domisili') == 'Kota Bogor' ? 'selected' : '' }}>Kota Bogor</option>
                            <option value="Kota Cimahi" {{ old('domisili') == 'Kota Cimahi' ? 'selected' : '' }}>Kota Cimahi</option>
                            <option value="Kota Cirebon" {{ old('domisili') == 'Kota Cirebon' ? 'selected' : '' }}>Kota Cirebon</option>
                            <option value="Kota Depok" {{ old('domisili') == 'Kota Depok' ? 'selected' : '' }}>Kota Depok</option>
                            <option value="Kota Sukabumi" {{ old('domisili') == 'Kota Sukabumi' ? 'selected' : '' }}>Kota Sukabumi</option>
                            <option value="Kota Tasikmalaya" {{ old('domisili') == 'Kota Tasikmalaya' ? 'selected' : '' }}>Kota Tasikmalaya</option>
                        </optgroup>
                    </select>
                </div>

                <div class="form-group full-width">
                    <label for="alamat_domisili">Alamat Domisili<span class="required">*</span></label>
                    <textarea id="alamat_domisili" name="alamat_domisili" class="form-control" rows="4" placeholder="Masukkan alamat lengkap Anda" required>{{ old('alamat_domisili') }}</textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="kode_pos">Kode Pos<span class="required">*</span></label>
                        <input type="text" id="kode_pos" name="kode_pos" class="form-control" value="{{ old('kode_pos') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email<span class="required">*</span></label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label for="nomor_ktp">Nomor KTP<span class="required">*</span></label>
                    <input type="text" id="nomor_ktp" name="nomor_ktp" class="form-control" value="{{ old('nomor_ktp') }}" maxlength="16" pattern="[0-9]*" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="Contoh: 3201234567890123" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="foto_ktp">Upload Foto KTP<span class="required">*</span></label>
                        <input type="file" id="foto_ktp" name="foto_ktp" class="form-control-file" accept="image/*" required>
                    </div>
                    <div class="form-group">
                        <label for="foto_diri">Upload Foto Diri<span class="required">*</span></label>
                        <input type="file" id="foto_diri" name="foto_diri" class="form-control-file" accept="image/*" required>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-submit btn-next" data-next="2">Berikutnya</button>
                </div>
            </div>

            <!-- Step 2: Profile Perusahaan -->
            <div class="form-step" data-step="2">
                <h2 class="form-title">Profile Perusahaan</h2>

                <div class="form-row">
                    <div class="form-group">
                        <label for="nama_usaha_perusahaan">Nama Usaha<span class="required">*</span></label>
                        <input type="text" id="nama_usaha_perusahaan" name="nama_usaha_perusahaan" class="form-control" value="{{ old('nama_usaha_perusahaan') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Nama Legalitas Usaha<span class="required">*</span></label>
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio" name="legalitas_usaha" value="PT" {{ old('legalitas_usaha') == 'PT' ? 'checked' : '' }} required>
                                <span>PT</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="legalitas_usaha" value="CV" {{ old('legalitas_usaha') == 'CV' ? 'checked' : '' }} required>
                                <span>CV</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="legalitas_usaha" value="PT Perorangan" {{ old('legalitas_usaha') == 'PT Perorangan' ? 'checked' : '' }} required>
                                <span>PT Perorangan</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label for="jabatan_usaha">Jabatan Dalam Usaha<span class="required">*</span></label>
                    <select id="jabatan_usaha" name="jabatan_usaha" class="form-control" required>
                        <option value="">Pilih Jabatan</option>
                        <option value="Direktur Utama" {{ old('jabatan_usaha') == 'Direktur Utama' ? 'selected' : '' }}>Direktur Utama</option>
                        <option value="Direktur" {{ old('jabatan_usaha') == 'Direktur' ? 'selected' : '' }}>Direktur</option>
                        <option value="Komisaris" {{ old('jabatan_usaha') == 'Komisaris' ? 'selected' : '' }}>Komisaris</option>
                        <option value="Pemilik" {{ old('jabatan_usaha') == 'Pemilik' ? 'selected' : '' }}>Pemilik</option>
                        <option value="CEO" {{ old('jabatan_usaha') == 'CEO' ? 'selected' : '' }}>CEO</option>
                        <option value="Managing Director" {{ old('jabatan_usaha') == 'Managing Director' ? 'selected' : '' }}>Managing Director</option>
                    </select>
                </div>

                <div class="form-group full-width">
                    <label for="alamat_kantor">Alamat Kantor<span class="required">*</span></label>
                    <textarea id="alamat_kantor" name="alamat_kantor" class="form-control" rows="4" required>{{ old('alamat_kantor') }}</textarea>
                </div>

                <div class="form-group full-width">
                    <label for="bidang_usaha">Bidang Usaha (Utama)<span class="required">*</span></label>
                    <textarea id="bidang_usaha" name="bidang_usaha" class="form-control" rows="4" required>{{ old('bidang_usaha') }}</textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="brand_usaha">Brand Usaha<span class="required">*</span></label>
                        <input type="text" id="brand_usaha" name="brand_usaha" class="form-control" value="{{ old('brand_usaha') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="jumlah_karyawan">Jumlah Karyawan<span class="required">*</span></label>
                        <input type="number" id="jumlah_karyawan" name="jumlah_karyawan" class="form-control" value="{{ old('jumlah_karyawan') }}" required>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label for="nomor_ktp_perusahaan">Nomor KTP<span class="required">*</span></label>
                    <input type="text" id="nomor_ktp_perusahaan" name="nomor_ktp_perusahaan" class="form-control" value="{{ old('nomor_ktp_perusahaan') }}" maxlength="16" pattern="[0-9]*" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="Contoh: 3201234567890123" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Usia Perusahaan<span class="required">*</span></label>
                        <div class="radio-group-vertical">
                            <label class="radio-label">
                                <input type="radio" name="usia_perusahaan" value="< 1 Tahun" {{ old('usia_perusahaan') == '< 1 Tahun' ? 'checked' : '' }} required>
                                <span>
                                    < 1 Tahun</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="usia_perusahaan" value="1 - 2 Tahun" {{ old('usia_perusahaan') == '1 - 2 Tahun' ? 'checked' : '' }} required>
                                <span>1 - 2 Tahun</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="usia_perusahaan" value="2 - 5 Tahun" {{ old('usia_perusahaan') == '2 - 5 Tahun' ? 'checked' : '' }} required>
                                <span>2 - 5 Tahun</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="usia_perusahaan" value="> 5 Tahun" {{ old('usia_perusahaan') == '> 5 Tahun' ? 'checked' : '' }} required>
                                <span>> 5 Tahun</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Omset Perusahaan PerTahun<span class="required">*</span></label>
                        <div class="radio-group-vertical">
                            <label class="radio-label">
                                <input type="radio" name="omset_perusahaan" value="< Rp. 1.000.000.000" {{ old('omset_perusahaan') == '< Rp. 1.000.000.000' ? 'checked' : '' }} required>
                                <span>
                                    < Rp. 1.000.000.000</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="omset_perusahaan" value="Rp. 1.000.000.000 - Rp. 4.000.000.000" {{ old('omset_perusahaan') == 'Rp. 1.000.000.000 - Rp. 4.000.000.000' ? 'checked' : '' }} required>
                                <span>Rp. 1.000.000.000 - Rp. 4.000.000.000</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="omset_perusahaan" value="> Rp. 4.000.000.000" {{ old('omset_perusahaan') == '> Rp. 4.000.000.000' ? 'checked' : '' }} required>
                                <span>> Rp. 4.000.000.000</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="npwp_perusahaan">NPWP Perusahaan<span class="required">*</span></label>
                        <input type="text" id="npwp_perusahaan" name="npwp_perusahaan" class="form-control" value="{{ old('npwp_perusahaan') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="no_nota_pendirian">No. Nota Pendirian<span class="required">*</span></label>
                        <input type="text" id="no_nota_pendirian" name="no_nota_pendirian" class="form-control" value="{{ old('no_nota_pendirian') }}" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="profile_perusahaan">Unggah Profile Perusahaan<span class="required">*</span></label>
                        <div class="file-info">
                            <p>Mohon unggah Profile Perusahaan dalam bentuk PDF.</p>
                            <p>Profile Perusahaan harus memuat minimal :</p>
                            <ol>
                                <li>Logo Perusahaan</li>
                                <li>Kontak Perusahaan</li>
                                <li>Email Perusahaan</li>
                                <li>Media Sosial Perusahaan (Jika Ada)</li>
                                <li>Website Perusahaan (Jika Ada)</li>
                                <li>Logo Brand / Produk</li>
                                <li>Deskripsi Brand / Produk</li>
                                <li>Foto kegiatan usaha</li>
                            </ol>
                        </div>
                        <input type="file" id="profile_perusahaan" name="profile_perusahaan" class="form-control-file" accept=".pdf" required>
                    </div>
                    <div class="form-group">
                        <label for="logo_perusahaan">Unggah Logo Perusahaan<span class="required">*</span></label>
                        <input type="file" id="logo_perusahaan" name="logo_perusahaan" class="form-control-file" accept="image/*" required>
                    </div>
                </div>

                <div class="form-actions-two">
                    <button type="button" class="btn-secondary btn-prev" data-prev="1">Sebelumnya</button>
                    <button type="button" class="btn-submit btn-next" data-next="3">Berikutnya</button>
                </div>
            </div>

            <!-- Step 3: Organisasi -->
            <div class="form-step" data-step="3">
                <h2 class="form-title">Organisasi</h2>

                <div class="form-group full-width">
                    <label for="sfc_hipmi">SFC ASITA<span class="required">*</span></label>
                    <input type="text" id="sfc_hipmi" name="sfc_hipmi" class="form-control" value="{{ old('sfc_hipmi') }}" required>
                </div>

                <div class="form-group full-width">
                    <label>Apakah anda referensi dari Anggota ASITA?<span class="required">*</span></label>
                    <div class="radio-group-vertical">
                        <label class="radio-label">
                            <input type="radio" name="referensi_hipmi" value="Ya" {{ old('referensi_hipmi') == 'Ya' ? 'checked' : '' }} required>
                            <span>Ya</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="referensi_hipmi" value="Tidak" {{ old('referensi_hipmi') == 'Tidak' ? 'checked' : '' }} required>
                            <span>Tidak</span>
                        </label>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label>Apakah Anda aktif di organisasi selain ASITA?<span class="required">*</span></label>
                    <div class="radio-group-vertical">
                        <label class="radio-label">
                            <input type="radio" name="organisasi_lain" value="Ya" {{ old('organisasi_lain') == 'Ya' ? 'checked' : '' }} required>
                            <span>Ya</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="organisasi_lain" value="Tidak" {{ old('organisasi_lain') == 'Tidak' ? 'checked' : '' }} required>
                            <span>Tidak</span>
                        </label>
                    </div>
                </div>

                <div class="form-actions-two">
                    <button type="button" class="btn-secondary btn-prev" data-prev="2">Sebelumnya</button>
                    <button type="button" class="btn-submit btn-next" data-next="4" id="btnNextOrganisasi">Berikutnya</button>
                </div>
            </div>

            <!-- Step 4: Daftar -->
            <div class="form-step" data-step="4">
                <h2 class="form-title">Daftar</h2>

                <div class="form-group full-width">
                    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #007bff;">
                        <label class="radio-label" style="display: flex; align-items: flex-start; gap: 12px; cursor: pointer; margin: 0;">
                            <input type="checkbox" name="pernyataan" value="1"
                                {{ old('pernyataan') ? 'checked' : '' }}
                                required
                                style="margin-top: 4px; width: 18px; height: 18px; cursor: pointer;">
                            <span style="font-size: 15px; line-height: 1.6;">
                                Dengan ini saya menyatakan bahwa data yang saya isi adalah <strong>benar dan valid</strong>,
                                serta bersedia mengikuti seluruh tahapan seleksi keanggotaan ASITA Jawa Barat.
                                <span class="required" style="color: #dc3545;">*</span>
                            </span>
                        </label>
                    </div>
                </div>

                <div class="form-actions-two">
                    <button type="button" class="btn-secondary btn-prev" data-prev="3">Sebelumnya</button>
                    <button type="submit" class="btn-submit">Daftar</button>
                </div>
            </div>
        </form>
    </div>
</section>

<style>
    /* Alert Styles */
    .alert {
        display: flex;
        align-items: flex-start;
        padding: 16px 20px;
        margin-bottom: 24px;
        border-radius: 8px;
        font-size: 14px;
        line-height: 1.5;
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert svg {
        flex-shrink: 0;
        margin-right: 12px;
        margin-top: 2px;
    }

    .alert-success {
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
    }

    .alert-success svg {
        stroke: #28a745;
    }

    .alert-error {
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }

    .alert-error svg {
        stroke: #dc3545;
    }

    .alert strong {
        font-weight: 600;
        display: block;
        margin-bottom: 8px;
    }

    .alert ul {
        margin: 0;
        padding-left: 20px;
    }

    .alert ul li {
        margin-bottom: 4px;
    }

    .alert ul li:last-child {
        margin-bottom: 0;
    }

    /* Field validation styles */
    .field-invalid {
        border: 2px solid #ff4444 !important;
        background-color: #fff5f5 !important;
    }

    .field-invalid:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(255, 68, 68, 0.1) !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Next buttons with validation
        const nextButtons = document.querySelectorAll('.btn-next');
        nextButtons.forEach(function(btn) {
            btn.addEventListener('click', function() {
                const currentStep = this.closest('.form-step').getAttribute('data-step');
                const nextStep = this.getAttribute('data-next');

                // Validate current step before moving to next
                if (validateStep(currentStep)) {
                    goToStep(parseInt(nextStep));
                }
            });
        });

        // Previous buttons
        const prevButtons = document.querySelectorAll('.btn-prev');
        prevButtons.forEach(function(btn) {
            btn.addEventListener('click', function() {
                const prevStep = this.getAttribute('data-prev');
                goToStep(parseInt(prevStep));
            });
        });

        // Custom Alert Function with better styling
        function showCustomAlert(title, fields) {
            // Create modal overlay
            const overlay = document.createElement('div');
            overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            animation: fadeIn 0.3s ease;
        `;

            // Create modal content
            const modal = document.createElement('div');
            modal.style.cssText = `
            background: white;
            border-radius: 12px;
            padding: 30px;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            animation: slideUp 0.3s ease;
        `;

            // Create content HTML
            let fieldsHTML = '';
            fields.forEach(function(field, index) {
                fieldsHTML += `
                <li style="
                    padding: 10px 0;
                    border-bottom: ${index < fields.length - 1 ? '1px solid #f0f0f0' : 'none'};
                    color: #666;
                ">
                    <span style="
                        display: inline-block;
                        width: 24px;
                        height: 24px;
                        background: #ff4444;
                        color: white;
                        border-radius: 50%;
                        text-align: center;
                        line-height: 24px;
                        margin-right: 10px;
                        font-size: 12px;
                        font-weight: bold;
                    ">!</span>
                    ${field}
                </li>
            `;
            });

            modal.innerHTML = `
            <div style="text-align: center; margin-bottom: 20px;">
                <div style="
                    width: 60px;
                    height: 60px;
                    background: #fff3cd;
                    border-radius: 50%;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    margin-bottom: 15px;
                ">
                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#ff9800" stroke-width="2">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                        <line x1="12" y1="9" x2="12" y2="13"></line>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                </div>
                <h3 style="
                    margin: 0 0 10px 0;
                    color: #333;
                    font-size: 22px;
                    font-weight: 600;
                ">${title}</h3>
                <p style="
                    margin: 0;
                    color: #666;
                    font-size: 14px;
                ">Mohon lengkapi field berikut untuk melanjutkan:</p>
            </div>
            <ul style="
                list-style: none;
                padding: 0;
                margin: 20px 0;
                max-height: 300px;
                overflow-y: auto;
            ">
                ${fieldsHTML}
            </ul>
            <div style="text-align: center; margin-top: 25px;">
                <button id="closeAlertBtn" style="
                    background: #007bff;
                    color: white;
                    border: none;
                    padding: 12px 40px;
                    border-radius: 6px;
                    font-size: 16px;
                    font-weight: 500;
                    cursor: pointer;
                    transition: all 0.3s ease;
                " onmouseover="this.style.background='#0056b3'" onmouseout="this.style.background='#007bff'">
                    Mengerti
                </button>
            </div>
        `;

            overlay.appendChild(modal);
            document.body.appendChild(overlay);

            // Add animations
            const style = document.createElement('style');
            style.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            @keyframes slideUp {
                from { transform: translateY(50px); opacity: 0; }
                to { transform: translateY(0); opacity: 1; }
            }
        `;
            document.head.appendChild(style);

            // Close button event
            document.getElementById('closeAlertBtn').addEventListener('click', function() {
                overlay.style.animation = 'fadeIn 0.3s ease reverse';
                setTimeout(function() {
                    document.body.removeChild(overlay);
                    document.head.removeChild(style);
                }, 300);
            });

            // Close on overlay click
            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) {
                    overlay.style.animation = 'fadeIn 0.3s ease reverse';
                    setTimeout(function() {
                        document.body.removeChild(overlay);
                        document.head.removeChild(style);
                    }, 300);
                }
            });
        }

        function validateStep(step) {
            const currentStepElement = document.querySelector('.form-step[data-step="' + step + '"]');
            const requiredInputs = currentStepElement.querySelectorAll('input[required], select[required], textarea[required]');
            const emptyFields = [];
            const processedRadioGroups = [];
            const processedCheckboxGroups = [];

            requiredInputs.forEach(function(input) {
                const label = currentStepElement.querySelector('label[for="' + input.id + '"]');
                let fieldName = label ? label.textContent.replace('*', '').trim() : input.name;

                if (input.type === 'radio') {
                    const radioName = input.name;

                    // Skip if we've already processed this radio group
                    if (processedRadioGroups.indexOf(radioName) !== -1) {
                        return;
                    }

                    processedRadioGroups.push(radioName);

                    const radioGroup = currentStepElement.querySelectorAll('input[name="' + radioName + '"]');
                    const isChecked = Array.from(radioGroup).some(function(radio) {
                        return radio.checked;
                    });

                    if (!isChecked) {
                        // Find the label for radio group
                        const radioContainer = input.closest('.form-group');
                        const radioLabel = radioContainer ? radioContainer.querySelector('label:not(.radio-label)') : null;
                        const radioFieldName = radioLabel ? radioLabel.textContent.replace('*', '').trim() : radioName;
                        emptyFields.push(radioFieldName);

                        // Add visual feedback
                        if (radioContainer) {
                            radioContainer.style.border = '2px solid #ff4444';
                            radioContainer.style.borderRadius = '8px';
                            radioContainer.style.padding = '10px';
                        }
                    } else {
                        // Remove visual feedback if checked
                        const radioContainer = input.closest('.form-group');
                        if (radioContainer) {
                            radioContainer.style.border = '';
                            radioContainer.style.borderRadius = '';
                            radioContainer.style.padding = '';
                        }
                    }
                } else if (input.type === 'checkbox') {
                    const checkboxName = input.name;

                    // Skip if we've already processed this checkbox
                    if (processedCheckboxGroups.indexOf(checkboxName) !== -1) {
                        return;
                    }

                    processedCheckboxGroups.push(checkboxName);

                    if (!input.checked) {
                        emptyFields.push(fieldName);
                        const checkboxContainer = input.closest('.form-group');
                        if (checkboxContainer) {
                            checkboxContainer.style.border = '2px solid #ff4444';
                            checkboxContainer.style.borderRadius = '8px';
                            checkboxContainer.style.padding = '10px';
                        }
                    } else {
                        const checkboxContainer = input.closest('.form-group');
                        if (checkboxContainer) {
                            checkboxContainer.style.border = '';
                            checkboxContainer.style.borderRadius = '';
                            checkboxContainer.style.padding = '';
                        }
                    }
                } else if (input.type === 'file') {
                    if (!input.files || input.files.length === 0) {
                        emptyFields.push(fieldName);
                        input.classList.add('field-invalid');
                    } else {
                        input.classList.remove('field-invalid');
                    }
                } else if (input.tagName === 'SELECT') {
                    if (!input.value || input.value === '') {
                        emptyFields.push(fieldName);
                        input.classList.add('field-invalid');
                    } else {
                        input.classList.remove('field-invalid');
                    }
                } else {
                    if (!input.value || !input.value.trim()) {
                        emptyFields.push(fieldName);
                        input.classList.add('field-invalid');
                    } else {
                        input.classList.remove('field-invalid');
                    }
                }
            });

            if (emptyFields.length > 0) {
                const stepTitles = {
                    '1': 'Data Pribadi Belum Lengkap',
                    '2': 'Profile Perusahaan Belum Lengkap',
                    '3': 'Data Organisasi Belum Lengkap',
                    '4': 'Pernyataan Belum Disetujui'
                };

                showCustomAlert(stepTitles[step] || 'Form Belum Lengkap', emptyFields);

                // Scroll to first invalid field
                const firstInvalid = currentStepElement.querySelector('.field-invalid, [style*="border: 2px solid #ff4444"]');
                if (firstInvalid) {
                    firstInvalid.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }

                return false;
            }

            // Remove all visual feedback if validation passes
            currentStepElement.querySelectorAll('.field-invalid').forEach(function(el) {
                el.classList.remove('field-invalid');
            });
            currentStepElement.querySelectorAll('[style*="border: 2px solid"]').forEach(function(el) {
                el.style.border = '';
                el.style.borderRadius = '';
                el.style.padding = '';
            });

            return true;
        }

        function goToStep(stepNumber) {
            // Hide all steps
            const allSteps = document.querySelectorAll('.form-step');
            allSteps.forEach(function(step) {
                step.classList.remove('active');
            });

            // Show target step
            const targetStep = document.querySelector('.form-step[data-step="' + stepNumber + '"]');
            if (targetStep) {
                targetStep.classList.add('active');
            }

            // Update stepper
            updateStepper(stepNumber);

            // Scroll to top
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function updateStepper(currentStep) {
            // Update stepper items
            const stepperItems = document.querySelectorAll('.stepper-item');
            stepperItems.forEach(function(item, index) {
                const stepNumber = index + 1;
                item.classList.remove('active', 'completed');

                if (stepNumber < currentStep) {
                    item.classList.add('completed');
                } else if (stepNumber === currentStep) {
                    item.classList.add('active');
                }
            });

            // Update stepper lines
            const stepperLines = document.querySelectorAll('.stepper-line');
            stepperLines.forEach(function(line, index) {
                const lineNumber = index + 1;
                line.classList.remove('completed');

                if (lineNumber < currentStep) {
                    line.classList.add('completed');
                }
            });
        }
    });
</script>
@endsection