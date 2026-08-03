<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            DB::statement('ALTER TABLE temu_karyas MODIFY foto_dokumentasi LONGTEXT NULL;');
        } catch (\Throwable $e) {
            Schema::table('temu_karyas', function (Blueprint $table) {
                $table->longText('foto_dokumentasi')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            DB::statement('ALTER TABLE temu_karyas MODIFY foto_dokumentasi VARCHAR(255) NULL;');
        } catch (\Throwable $e) {
            Schema::table('temu_karyas', function (Blueprint $table) {
                $table->string('foto_dokumentasi')->nullable()->change();
            });
        }
    }
};
