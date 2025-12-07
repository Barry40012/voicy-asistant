<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Payment Providers Configuration
    |--------------------------------------------------------------------------
    */

    'stripe' => [
        'secret_key' => env('STRIPE_SECRET_KEY'),
        'public_key' => env('STRIPE_PUBLIC_KEY'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],

    'flutterwave' => [
        'secret_key' => env('FLUTTERWAVE_SECRET_KEY'),
        'public_key' => env('FLUTTERWAVE_PUBLIC_KEY'),
        'webhook_secret' => env('FLUTTERWAVE_WEBHOOK_SECRET'),
        'base_url' => env('FLUTTERWAVE_BASE_URL', 'https://api.flutterwave.com/v3'),
    ],

    'orange' => [
        'merchant_id' => env('ORANGE_MERCHANT_ID'),
        'api_key' => env('ORANGE_API_KEY'),
        'webhook_secret' => env('ORANGE_WEBHOOK_SECRET'),
    ],

    'mtn' => [
        'subscription_key' => env('MTN_SUBSCRIPTION_KEY'),
        'api_key' => env('MTN_API_KEY'),
        'webhook_secret' => env('MTN_WEBHOOK_SECRET'),
    ],
];
