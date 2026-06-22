<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('anggota', function (Blueprint $table) {
            // Tambahkan kolom untuk data katalog
            $table->text('company_description')->nullable()->after('nama_perusahaan');
            $table->string('company_logo')->nullable()->after('company_description');
            $table->json('company_images')->nullable()->after('company_logo');
            $table->string('company_map_embed_url')->nullable()->after('alamat_kantor');
            $table->boolean('katalog_is_active')->default(false)->after('company_map_embed_url');
        });
    }

    public function down(): void
    {
        Schema::table('anggota', function (Blueprint $table) {
            $table->dropColumn([
                'company_description',
                'company_logo',
                'company_images',
                'company_map_embed_url',
                'katalog_is_active'
            ]);
        });
    }
};