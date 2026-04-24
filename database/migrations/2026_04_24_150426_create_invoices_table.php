<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique(); // FACT-YYYYMMDD-XXXX
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Datos del cliente
            $table->string('customer_name');
            $table->string('customer_email');
            $table->text('customer_address')->nullable();
            $table->string('customer_document')->nullable();
            
            // Totales
            $table->decimal('subtotal', 12, 2); // Antes de IVA
            $table->decimal('total_discounts', 12, 2)->default(0);
            $table->decimal('total_iva', 12, 2); // IVA 19%
            $table->decimal('total', 12, 2); // Total a pagar
            
            // Detalles de productos (JSON)
            $table->json('items'); // Array de productos con cálculos
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
