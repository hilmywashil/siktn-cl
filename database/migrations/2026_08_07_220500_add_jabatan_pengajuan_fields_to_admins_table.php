<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->string('jabatan_diajukan')->nullable()->after('domisili');
            $table->string('status_jabatan')->default('none')->after('jabatan_diajukan'); // 'none', 'pending', 'approved', 'rejected'
            $table->text('keterangan_jabatan')->nullable()->after('status_jabatan');
        });
    }

    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn(['jabatan_diajukan', 'status_jabatan', 'keterangan_jabatan']);
        });
    }
};
