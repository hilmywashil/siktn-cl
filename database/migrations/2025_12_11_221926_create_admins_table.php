<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('photo')->nullable();
            $table->string('password');
            $table->string('domisili')->nullable();
            $table->string('category')->default('super_admin');
            $table->boolean('is_active')->default(true);
            $table->string('no_hp')->nullable();
            $table->string('foto_profil')->nullable();
            $table->json('notification_settings')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};