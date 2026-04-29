<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Índices para products
        Schema::table('products', function (Blueprint $table) {
            $table->index('created_at');
            $table->index('stock');
            $table->index(['category_id', 'created_at']);
        });

        // Índices para orders
        Schema::table('orders', function (Blueprint $table) {
            $table->index('status');
            $table->index('created_at');
            $table->index(['user_id', 'created_at']);
        });

        // Índices para reviews
        Schema::table('reviews', function (Blueprint $table) {
            $table->index('product_id');
            $table->index('created_at');
        });

        // Índices para order_items
        Schema::table('order_items', function (Blueprint $table) {
            $table->index('order_id');
            $table->index('product_id');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
            $table->dropIndex(['stock']);
            $table->dropIndex(['category_id', 'created_at']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['user_id', 'created_at']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex(['order_id']);
            $table->dropIndex(['product_id']);
        });
    }
};
