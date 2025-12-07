<?php

namespace App\Services\PaymentAdapters;

use App\Models\User;

interface PaymentAdapterInterface
{
    /**
     * Get provider name
     */
    public function getProviderName(): string;

    /**
     * Create payment
     */
    public function createPayment(User $user, float $amount, string $currency, array $metadata = []): ?array;

    /**
     * Verify webhook payload
     */
    public function verifyWebhook(array $payload): ?array;
}
