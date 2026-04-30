<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Agregar campos para mejor gestión de facturas
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('status')->default('active')->after('total'); // active, cancelled
            $table->text('cancellation_reason')->nullable()->after('status');
            $table->timestamp('cancelled_at')->nullable()->after('cancellation_reason');
            $table->decimal('iva_percentage', 5, 2)->default(19.00)->after('total_iva');
            $table->boolean('iva_exempt')->default(false)->after('iva_percentage');
        });

        // Crear tabla de configuración de facturación
        Schema::create('invoice_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, number, boolean
            $table->timestamps();
        });

        // Insertar configuración por defecto
        DB::table('invoice_settings')->insert([
            ['key' => 'iva_percentage', 'value' => '19.00', 'type' => 'number', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'invoice_prefix', 'value' => 'FV', 'type' => 'string', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'next_invoice_number', 'value' => '1', 'type' => 'number', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'company_name', 'value' => 'FiftyOne', 'type' => 'string', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'company_nit', 'value' => '900123456-7', 'type' => 'string', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'company_address', 'value' => 'Calle 123 #45-67, Bogotá', 'type' => 'string', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'company_phone', 'value' => '+57 300 123 4567', 'type' => 'string', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'company_email', 'value' => 'contacto@fiftyone.com', 'type' => 'string', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['status', 'cancellation_reason', 'cancelled_at', 'iva_percentage', 'iva_exempt']);
        });
        
        Schema::dropIfExists('invoice_settings');
    }
};
