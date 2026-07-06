<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah ENUM status untuk mencakup status SIKTN baru
        DB::statement("ALTER TABLE anggota MODIFY COLUMN status ENUM('pending', 'pending_profile', 'pending_verification', 'approved', 'rejected') DEFAULT 'pending_profile'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ENUM status ke aslinya
        DB::statement("ALTER TABLE anggota MODIFY COLUMN status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'");
    }
};
