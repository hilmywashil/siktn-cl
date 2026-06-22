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
        Schema::table('katalogs', function (Blueprint $table) {
            // Cek apakah kolom anggota_id sudah ada
            if (!Schema::hasColumn('katalogs', 'anggota_id')) {
                $table->unsignedBigInteger('anggota_id')->nullable()->after('id');
                
                // Foreign key ke tabel 'anggota' (SINGULAR, bukan anggotas!)
                if (Schema::hasTable('anggota')) {
                    $table->foreign('anggota_id')
                          ->references('id')
                          ->on('anggota')
                          ->onDelete('cascade');
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('katalogs', function (Blueprint $table) {
            // Drop foreign key terlebih dahulu jika ada
            if (Schema::hasColumn('katalogs', 'anggota_id')) {
                // Try to drop foreign key (gunakan nama constraint yang mungkin)
                try {
                    $table->dropForeign(['anggota_id']);
                } catch (\Exception $e) {
                    // Ignore jika foreign key tidak ada
                }
                
                $table->dropColumn('anggota_id');
            }
        });
    }
};