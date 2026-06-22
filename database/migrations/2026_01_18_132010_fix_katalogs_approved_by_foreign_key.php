<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('katalogs', function (Blueprint $table) {
            // Drop foreign key lama yang salah (merujuk ke users)
            $table->dropForeign(['approved_by']);
            
            // Tambahkan foreign key baru yang benar (merujuk ke admins)
            $table->foreign('approved_by')
                  ->references('id')
                  ->on('admins')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('katalogs', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            
            // Restore ke foreign key lama
            $table->foreign('approved_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }
};