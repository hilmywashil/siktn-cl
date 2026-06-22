<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('katalogs', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('business_field');
            $table->text('description');
            $table->string('logo')->nullable();
            $table->json('images')->nullable();
            $table->string('address');
            $table->string('phone');
            $table->string('email');
            $table->string('map_embed_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('katalogs');
    }
};