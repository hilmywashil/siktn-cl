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
            // 1. Make company fields nullable
            $table->string('nama_perusahaan')->nullable()->change();
            $table->string('trade_mark')->nullable()->change();
            $table->date('tanggal_lahir')->nullable()->change();
            $table->text('alamat_kantor')->nullable()->change();
            $table->string('telepon_wa_perusahaan')->nullable()->change();
            $table->string('email_website_perusahaan')->nullable()->change();
            
            $table->string('nama_pimpinan')->nullable()->change();
            $table->text('alamat_pimpinan')->nullable()->change();
            $table->string('telepon_wa_pimpinan')->nullable()->change();
            $table->string('email_pimpinan')->nullable()->change();
            
            $table->string('akte_notaris')->nullable()->change();
            $table->string('nomor_induk_berusaha_tdup')->nullable()->change();
            $table->string('npwp_perusahaan')->nullable()->change();
            
            $table->string('surat_permohonan')->nullable()->change();
            $table->string('akte_pendirian_perusahaan')->nullable()->change();
            $table->string('nib_atau_tdup')->nullable()->change();
            $table->string('ktp_pimpinan')->nullable()->change();
            $table->string('npwp_perusahaan_file')->nullable()->change();
            
            // 2. Add personal member fields
            if (!Schema::hasColumn('anggota', 'nrp')) {
                $table->string('nrp')->nullable()->after('id');
            }
            if (!Schema::hasColumn('anggota', 'angkatan')) {
                $table->string('angkatan')->nullable()->after('nrp');
            }
            if (!Schema::hasColumn('anggota', 'nama_lengkap')) {
                $table->string('nama_lengkap')->nullable()->after('angkatan');
            }
            if (!Schema::hasColumn('anggota', 'gender')) {
                $table->string('gender')->nullable()->after('nama_lengkap');
            }
            if (!Schema::hasColumn('anggota', 'tempat_lahir_personal')) {
                $table->string('tempat_lahir_personal')->nullable()->after('gender');
            }
            if (!Schema::hasColumn('anggota', 'agama')) {
                $table->string('agama')->nullable()->after('tempat_lahir_personal');
            }
            if (!Schema::hasColumn('anggota', 'no_telp')) {
                $table->string('no_telp')->nullable()->after('agama');
            }
            if (!Schema::hasColumn('anggota', 'alamat_domisili')) {
                $table->text('alamat_domisili')->nullable()->after('no_telp');
            }
            if (!Schema::hasColumn('anggota', 'kode_pos')) {
                $table->string('kode_pos')->nullable()->after('alamat_domisili');
            }
            if (!Schema::hasColumn('anggota', 'email')) {
                $table->string('email')->nullable()->after('kode_pos');
            }
            if (!Schema::hasColumn('anggota', 'no_ktp')) {
                $table->string('no_ktp')->nullable()->after('email');
            }
            if (!Schema::hasColumn('anggota', 'foto_ktp')) {
                $table->string('foto_ktp')->nullable()->after('no_ktp');
            }
            if (!Schema::hasColumn('anggota', 'foto_diri')) {
                $table->string('foto_diri')->nullable()->after('foto_ktp');
            }
            
            // 3. Add organization fields
            if (!Schema::hasColumn('anggota', 'sfc_hipmi')) {
                $table->string('sfc_hipmi')->nullable()->after('foto_diri');
            }
            if (!Schema::hasColumn('anggota', 'ref_hipmi')) {
                $table->string('ref_hipmi')->nullable()->after('sfc_hipmi');
            }
            if (!Schema::hasColumn('anggota', 'aktif_org_lain')) {
                $table->string('aktif_org_lain')->nullable()->after('ref_hipmi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anggota', function (Blueprint $table) {
            $table->dropColumn([
                'nrp',
                'angkatan',
                'nama_lengkap',
                'gender',
                'tempat_lahir_personal',
                'agama',
                'no_telp',
                'alamat_domisili',
                'kode_pos',
                'email',
                'no_ktp',
                'foto_ktp',
                'foto_diri',
                'sfc_hipmi',
                'ref_hipmi',
                'aktif_org_lain'
            ]);
        });
    }
};
