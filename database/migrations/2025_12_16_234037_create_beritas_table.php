<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beritas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->text('konten');
            $table->string('gambar');
            $table->string('kategori')->default('Pengumuman');
            $table->json('tags')->nullable();
            $table->boolean('is_populer')->default(false);
            $table->enum('status', ['Draft', 'Published', 'Archived'])->default('Draft');
            $table->integer('views')->default(0);
            $table->dateTime('tanggal_publish')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beritas');
    }
};