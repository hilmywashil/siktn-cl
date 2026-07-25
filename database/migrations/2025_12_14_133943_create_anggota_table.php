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
            // IDENTITAS & AUTH
            // =====================
            $table->string('username')->unique()->nullable();
            $table->string('nik')->nullable();
            $table->string('nama_lengkap')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat_lengkap')->nullable();
            $table->string('domisili')->nullable()->comment('Wilayah untuk filter PPKT/PKKT');
            $table->string('jabatan')->nullable()->comment('Diisi oleh Sekretariat');
            $table->string('pendidikan_terakhir')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->text('riwayat_organisasi')->nullable();
            $table->text('kompetensi')->nullable();
            $table->string('email')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('foto_diri')->nullable();

            // =====================
            // SOSIAL MEDIA
            // =====================
            $table->string('instagram')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('twitter')->nullable();

            // =====================
            // AUTH
            // =====================
            $table->string('password')->nullable();
            $table->string('initial_password')->nullable();
            $table->rememberToken();

            // =====================
            // STATUS & APPROVAL
            // =====================
            $table->enum('status', ['pending', 'pending_profile', 'pending_verification', 'approved', 'rejected'])->default('pending_profile');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();

            // =====================
            // TRACKING
            // =====================
            $table->json('updated_fields')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggota');
    }
};