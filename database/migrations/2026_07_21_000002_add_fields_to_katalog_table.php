<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('katalogs', function (Blueprint $table) {
            $table->string('website_url')->nullable()->after('email');
            $table->string('marketplace_url')->nullable()->after('website_url');
            $table->string('harga')->nullable()->after('marketplace_url');
            $table->string('wilayah')->nullable()->after('address');
            $table->text('revision_notes')->nullable()->after('rejection_reason');
            $table->unsignedBigInteger('kategori_id')->nullable()->after('harga');
            $table->foreign('kategori_id')->references('id')->on('kategori_ekatalog')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('katalogs', function (Blueprint $table) {
            $table->dropForeign(['kategori_id']);
            $table->dropColumn([
                'website_url',
                'marketplace_url',
                'kategori_id',
                'harga',
                'wilayah',
                'revision_notes',
            ]);
        });
    }
};
