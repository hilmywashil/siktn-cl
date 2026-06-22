<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('katalogs', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])
                  ->default('pending')
                  ->after('is_active');
            
            $table->unsignedBigInteger('approved_by')
                  ->nullable()
                  ->after('status');
            
            $table->timestamp('approved_at')
                  ->nullable()
                  ->after('approved_by');
            
            $table->text('rejection_reason')
                  ->nullable()
                  ->after('approved_at');
            
            // Foreign key ke table users (admin)
            $table->foreign('approved_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('katalogs', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn([
                'status', 
                'approved_by', 
                'approved_at', 
                'rejection_reason'
            ]);
        });
    }
};