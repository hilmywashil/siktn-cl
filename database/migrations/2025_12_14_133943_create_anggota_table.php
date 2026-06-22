<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anggota', function (Blueprint $table) {
            $table->id();

            // =====================
            // AUTH
            // =====================
            $table->string('password')->nullable();
            $table->string('initial_password')->nullable();
            $table->rememberToken();

            // =====================
            // DATA PERUSAHAAN
            // =====================
            $table->string('nama_perusahaan');
            $table->string('trade_mark');
            $table->date('tanggal_lahir');
            $table->text('alamat_kantor');
            $table->string('telepon_wa_perusahaan');
            $table->string('email_website_perusahaan'); // UNIQUE DIHAPUS

            // =====================
            // DATA PIMPINAN
            // =====================
            $table->string('nama_pimpinan');
            $table->text('alamat_pimpinan');
            $table->string('telepon_wa_pimpinan');
            $table->string('email_pimpinan'); // UNIQUE DIHAPUS

            // =====================
            // LEGALITAS
            // =====================
            $table->string('akte_notaris');
            $table->string('nomor_induk_berusaha_tdup');
            $table->string('npwp_perusahaan');

            // =====================
            // PRODUK USAHA
            // =====================
            $table->json('produk_usaha_yang_akan_dijual')->nullable();

            // =====================
            // DOKUMEN
            // =====================
            $table->string('surat_permohonan');
            $table->string('akte_pendirian_perusahaan');
            $table->string('nib_atau_tdup');
            $table->string('ktp_pimpinan');
            $table->string('npwp_perusahaan_file');

            // =====================
            // STATUS & APPROVAL
            // =====================
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggota');
    }
};