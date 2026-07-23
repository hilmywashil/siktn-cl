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
        Schema::create('admin_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('module', 50);               // anggota, berita, program, katalog, surat, sk, notulensi, dll
            $table->string('action', 50);               // Tambah, Edit, Hapus, Ubah Status, Approve, Tolak, dll
            $table->unsignedBigInteger('record_id')->nullable();
            $table->string('record_label')->nullable(); // judul/nomor/nama record
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->string('admin_name', 100);
            $table->string('detail')->nullable();       // info tambahan (misal: "Draft -> Published")
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index(['module', 'created_at']);
            $table->index('admin_id');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_activity_logs');
    }
};
