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
        Schema::table('temu_karyas', function (Blueprint $table) {
            $table->foreignId('surat_keputusan_id')->nullable()->after('catatan')->constrained('surat_keputusans')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('temu_karyas', function (Blueprint $table) {
            $table->dropForeign(['surat_keputusan_id']);
            $table->dropColumn('surat_keputusan_id');
        });
    }
};
