<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('anggota', function (Blueprint $table) {
            // Hapus kolom-kolom lama (HIPMI & kolom yang akan diganti)
            $table->dropColumn([
                'nrp', 'angkatan', 'gender', 'agama', 'kode_pos', 'no_ktp', 'foto_ktp', 
                'sfc_hipmi', 'ref_hipmi', 'aktif_org_lain', 'nama_perusahaan', 
                'company_description', 'company_logo', 'company_images', 'trade_mark', 
                'alamat_kantor', 'company_map_embed_url', 'katalog_is_active', 
                'telepon_wa_perusahaan', 'email_website_perusahaan', 'nama_pimpinan', 
                'alamat_pimpinan', 'telepon_wa_pimpinan', 'email_pimpinan', 'akte_notaris', 
                'nomor_induk_berusaha_tdup', 'npwp_perusahaan', 'produk_usaha_yang_akan_dijual', 
                'surat_permohonan', 'akte_pendirian_perusahaan', 'nib_atau_tdup', 
                'ktp_pimpinan', 'npwp_perusahaan_file',
                'tempat_lahir_personal', 'alamat_domisili', 'no_telp'
            ]);

            // Tambah kolom-kolom baru (SIKTN)
            $table->string('username')->unique()->nullable()->after('id');
            $table->string('nik')->nullable()->after('username');
            $table->string('tempat_lahir')->nullable()->after('nama_lengkap');
            $table->text('alamat_lengkap')->nullable()->after('tanggal_lahir');
            $table->string('domisili')->nullable()->after('alamat_lengkap')->comment('Wilayah untuk filter PPKT/PKKT');
            $table->string('jabatan')->nullable()->after('domisili')->comment('Diisi oleh Sekretariat');
            $table->string('pendidikan_terakhir')->nullable()->after('jabatan');
            $table->string('pekerjaan')->nullable()->after('pendidikan_terakhir');
            $table->text('riwayat_organisasi')->nullable()->after('pekerjaan');
            $table->text('kompetensi')->nullable()->after('riwayat_organisasi');
            $table->string('no_hp')->nullable()->after('email');
            $table->string('instagram')->nullable()->after('no_hp');
            $table->string('tiktok')->nullable()->after('instagram');
            $table->string('twitter')->nullable()->after('tiktok');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anggota', function (Blueprint $table) {
            // Hapus kolom-kolom SIKTN
            $table->dropColumn([
                'username', 'nik', 'tempat_lahir', 'alamat_lengkap', 'domisili', 
                'jabatan', 'pendidikan_terakhir', 'pekerjaan', 'riwayat_organisasi', 
                'kompetensi', 'no_hp', 'instagram', 'tiktok', 'twitter'
            ]);
        });
    }
};
