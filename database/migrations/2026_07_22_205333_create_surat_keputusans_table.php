<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_keputusans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_sk');
            $table->string('judul_sk');
            $table->date('tanggal_berlaku');
            $table->date('tanggal_berakhir');
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif');
            $table->text('link_drive')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('admins')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_keputusans');
    }
};
