<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_program');
            $table->enum('kategori', ['CSR', 'Bidang']);
            $table->enum('status', ['Perencanaan', 'Berjalan', 'Selesai'])->default('Perencanaan');
            $table->date('periode_mulai');
            $table->date('periode_selesai');
            $table->string('pic');
            $table->text('target_output');
            $table->decimal('anggaran', 15, 2)->nullable();
            $table->string('gambar')->nullable();

            // Khusus CSR
            $table->string('mitra')->nullable();

            // Khusus Bidang
            $table->foreignId('jabatan_id')->nullable()->constrained('jabatans')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
