<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cek apakah kolom sudah ada sebelum menambahkan
        Schema::table('katalogs', function (Blueprint $table) {
            if (!Schema::hasColumn('katalogs', 'created_by_type')) {
                $table->string('created_by_type')->nullable()->after('anggota_id');
            }
            if (!Schema::hasColumn('katalogs', 'created_by_id')) {
                $table->unsignedBigInteger('created_by_id')->nullable()->after('created_by_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('katalogs', function (Blueprint $table) {
            if (Schema::hasColumn('katalogs', 'created_by_type')) {
                $table->dropColumn('created_by_type');
            }
            if (Schema::hasColumn('katalogs', 'created_by_id')) {
                $table->dropColumn('created_by_id');
            }
        });
    }
};