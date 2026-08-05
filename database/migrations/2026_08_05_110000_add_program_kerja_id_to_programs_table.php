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
        Schema::table('programs', function (Blueprint $table) {
            if (!Schema::hasColumn('programs', 'program_kerja_id')) {
                $table->foreignId('program_kerja_id')->nullable()->after('kategori')->constrained('programs')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            if (Schema::hasColumn('programs', 'program_kerja_id')) {
                $table->dropForeign(['program_kerja_id']);
                $table->dropColumn('program_kerja_id');
            }
        });
    }
};
