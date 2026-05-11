<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla para almacenar transacciones de Wompi
     * Permite trazabilidad completa de pagos
     */
    public function up(): void
    {
        Schema::create('wompi_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            
            // Identificadores de Wompi
            $table->string('transaction_id')->unique()->nullable(); // ID de transacción Wompi
            $table->string('reference')->unique(); // Referencia única generada por nosotros
            
            // Datos del pago
            $table->decimal('amount', 10, 2); // Monto en pesos
            $table->integer('amount_in_cents'); // Monto en centavos (requerido por Wompi)
            $table->string('currency', 3)->default('COP');
            
            // Estado del pago
            $table->enum('status', ['PENDING', 'APPROVED', 'DECLINED', 'VOIDED', 'ERROR'])->default('PENDING');
            $table->string('status_message')->nullable();
            
            // Método de pago
            $table->string('payment_method')->nullable(); // CARD, NEQUI, PSE, BANCOLOMBIA, etc.
            $table->string('payment_method_type')->nullable(); // Tipo específico
            
            // Datos del cliente
            $table->string('customer_email');
            $table->json('customer_data')->nullable();
            
            // Firma de integridad
            $table->string('integrity_signature', 64)->nullable();
            
            // Respuesta completa de Wompi (para debugging y auditoría)
            $table->json('wompi_response')->nullable();
            
            // Webhooks
            $table->timestamp('webhook_received_at')->nullable();
            $table->json('webhook_data')->nullable();
            
            // Metadata adicional
            $table->json('metadata')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Índices para búsquedas rápidas
            $table->index('transaction_id');
            $table->index('reference');
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wompi_payments');
    }
};
