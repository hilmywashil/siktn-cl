<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            $table->foreignId('admin_id')->nullable()->after('id')->constrained('admins')->nullOnDelete();
            $table->string('kategori')->default('Pengumuman')->after('gambar');
            $table->json('tags')->nullable()->after('kategori');
            $table->enum('status', ['Draft', 'Published', 'Archived'])->default('Draft')->after('is_populer');
            
            // We keep tanggal_publish but drop is_active
            $table->dropColumn('is_active');
        });
        
        // Convert date to datetime
        Schema::table('beritas', function (Blueprint $table) {
            $table->dateTime('tanggal_publish')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropColumn(['kategori', 'tags', 'status', 'admin_id']);
            $table->boolean('is_active')->default(true);
            $table->date('tanggal_publish')->change();
        });
    }
};
