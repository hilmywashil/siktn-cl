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
        Schema::create('temu_karyas', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis', ['temu_karya', 'caretaker'])->default('temu_karya');
            $table->string('wilayah');
            $table->enum('level', ['provinsi', 'kab_kota'])->default('provinsi');
            $table->date('tanggal_pelaksanaan')->nullable();
            $table->string('lokasi')->nullable();
            $table->integer('jumlah_peserta')->default(0);
            $table->string('foto_dokumentasi')->nullable();
            $table->text('catatan')->nullable();
            $table->string('file_sk')->nullable(); // Upload file SK Temu Karya / Caretaker
            $table->enum('status', ['selesai', 'pending', 'caretaker'])->default('selesai');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('admins')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temu_karyas');
    }
};
