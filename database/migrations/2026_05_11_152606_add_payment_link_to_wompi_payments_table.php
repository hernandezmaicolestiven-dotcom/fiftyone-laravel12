<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wompi_payments', function (Blueprint $table) {
            $table->string('payment_link_id')->nullable()->after('transaction_id');
            $table->text('payment_link_url')->nullable()->after('payment_link_id');
        });
    }

    public function down(): void
    {
        Schema::table('wompi_payments', function (Blueprint $table) {
            $table->dropColumn(['payment_link_id', 'payment_link_url']);
        });
    }
};
