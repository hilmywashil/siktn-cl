<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_id')->constrained('surats')->onDelete('cascade');
            $table->foreignId('admin_id')->nullable()->constrained('admins')->onDelete('set null');
            $table->string('admin_name')->nullable();
            $table->string('action');
            $table->string('old_status')->nullable();
            $table->string('new_status')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_audit_logs');
    }
};
