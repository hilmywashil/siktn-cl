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
        Schema::table('program_anggota', function (Blueprint $table) {
            if (!Schema::hasColumn('program_anggota', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('anggota_id');
            }
        });

        // Set existing records to approved
        \Illuminate\Support\Facades\DB::table('program_anggota')->update(['status' => 'approved']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_anggota', function (Blueprint $table) {
            if (Schema::hasColumn('program_anggota', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
