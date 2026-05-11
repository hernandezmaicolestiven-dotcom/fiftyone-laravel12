<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::apiResource('products', ProductController::class);

// ── Wompi Payment Gateway ──────────────────────────────────────────────────
Route::prefix('wompi')->name('wompi.')->group(function () {
    // Crear transacción (sin middleware auth para permitir peticiones desde frontend)
    Route::post('/create-transaction', [\App\Http\Controllers\WompiController::class, 'createTransaction'])
        ->name('create-transaction');
    
    // Consultar estado de pago
    Route::get('/payment/{payment}/status', [\App\Http\Controllers\WompiController::class, 'getPaymentStatus'])
        ->name('payment.status');
    
    // Webhook (sin autenticación, validado por firma)
    Route::post('/webhook', [\App\Http\Controllers\WompiController::class, 'webhook'])
        ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class])
        ->name('webhook');
});
