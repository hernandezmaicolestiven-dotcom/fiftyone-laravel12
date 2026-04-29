<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Agregar soft deletes a users
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Agregar soft deletes a orders
        Schema::table('orders', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Agregar soft deletes a coupons
        Schema::table('coupons', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Agregar soft deletes a reviews
        Schema::table('reviews', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('coupons', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
