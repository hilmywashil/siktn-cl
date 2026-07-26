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
        Schema::table('organisasi', function (Blueprint $table) {
            $table->string('provinsi')->default('Nasional')->after('kategori');
            $table->string('kabupaten')->nullable()->after('provinsi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organisasi', function (Blueprint $table) {
            $table->dropColumn(['provinsi', 'kabupaten']);
        });
    }
};
