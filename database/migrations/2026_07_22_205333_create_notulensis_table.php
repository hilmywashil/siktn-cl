<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notulensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agenda_id')->nullable()->constrained('agendas')->onDelete('set null');
            $table->string('judul_rapat');
            $table->dateTime('tanggal_rapat');
            $table->string('pemimpin_rapat')->nullable();
            $table->text('ringkasan_hasil')->nullable();
            $table->text('link_drive')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('admins')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notulensis');
    }
};
