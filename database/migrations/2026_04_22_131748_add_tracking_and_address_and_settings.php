<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Número de guía en pedidos
        Schema::table('orders', function (Blueprint $table) {
            $table->string('tracking_number')->nullable()->after('status');
        });

        // Dirección guardada en usuarios
        Schema::table('users', function (Blueprint $table) {
            $table->string('default_address')->nullable()->after('phone');
            $table->string('default_city')->nullable()->after('default_address');
        });

        // Tabla de configuración de la tienda
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('orders', fn($t) => $t->dropColumn('tracking_number'));
        Schema::table('users', fn($t) => $t->dropColumn(['default_address','default_city']));
        Schema::dropIfExists('settings');
    }
};
