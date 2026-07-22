<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->enum('tipe', ['masuk', 'keluar'])->default('keluar');
            $table->enum('klasifikasi', ['internal', 'eksternal', 'penting'])->default('internal');
            $table->string('nomor_surat');
            $table->date('tanggal');
            $table->text('perihal');
            $table->string('pengirim_tujuan');
            $table->enum('status', ['Pending TTD', 'Terbit', 'Revisi', 'Draft'])->default('Pending TTD');
            $table->text('link_drive')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('admins')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surats');
    }
};
