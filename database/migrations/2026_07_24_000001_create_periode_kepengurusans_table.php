<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('periode_kepengurusans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_periode');
            $table->integer('tahun_mulai');
            $table->integer('tahun_selesai');
            $table->string('nomor_sk')->nullable();
            $table->text('keterangan')->nullable();
            $table->boolean('is_aktif')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('periode_kepengurusans');
    }
};
