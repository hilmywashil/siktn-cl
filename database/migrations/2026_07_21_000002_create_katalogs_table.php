<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('katalogs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('anggota_id')->nullable();
            $table->string('created_by_type')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->string('company_name');
            $table->string('business_field');
            $table->unsignedBigInteger('kategori_id')->nullable();
            $table->string('harga')->nullable();
            $table->text('description');
            $table->string('logo')->nullable();
            $table->json('images')->nullable();
            $table->string('address');
            $table->string('wilayah')->nullable();
            $table->string('phone');
            $table->string('email');
            $table->string('website_url')->nullable();
            $table->string('marketplace_url')->nullable();
            $table->text('map_embed_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->enum('status', ['pending', 'approved', 'rejected', 'revision'])->default('pending');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('revision_notes')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('anggota_id')->references('id')->on('anggota')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('admins')->onDelete('set null');
            $table->foreign('kategori_id')->references('id')->on('kategori_ekatalog')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('katalogs');
    }
};
