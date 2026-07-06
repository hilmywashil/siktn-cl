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
        Schema::table('admins', function (Blueprint $table) {
            // Change enum to string so we can save SIKTN roles like 'pnkt', 'ppkt'
            $table->string('category')->default('super_admin')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            // We cannot easily revert back to ENUM if it contains new values,
            // so we'll just leave it as string in down() or you can define the old enum.
            $table->string('category')->default('bpc')->change();
        });
    }
};
