<?php

return [
    /*
    |--------------------------------------------------------------------------
    | WhatsApp Business Cloud API Configuration
    |--------------------------------------------------------------------------
    */

    'verify_token' => env('WHATSAPP_VERIFY_TOKEN', 'your_verify_token_here'),
    'app_secret' => env('WHATSAPP_APP_SECRET'),
    'graph_api_version' => env('WHATSAPP_GRAPH_API_VERSION', 'v18.0'),
];
