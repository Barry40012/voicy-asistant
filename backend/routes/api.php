<?php

use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

// Webhooks (no auth required, but signature verification)
Route::prefix('webhooks')->group(function () {
    Route::post('/whatsapp', [WebhookController::class, 'handleWhatsApp'])->name('webhooks.whatsapp');
    Route::post('/payments/{provider}', [WebhookController::class, 'handlePayment'])->name('webhooks.payments');
});
