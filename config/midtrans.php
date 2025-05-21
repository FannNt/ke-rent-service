<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Midtrans Configuration
    |--------------------------------------------------------------------------
    |
    | This file is for configuring the Midtrans payment gateway for your
    | application. You can set the environment, server key, client key,
    | and other options here.
    |
    */

    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION'),
    'is_sanitized' => env('MIDTRANS_IS_SANITIZED'),
    'is_3ds' => env('MIDTRANS_IS_3DS'),

    // Additional configuration options can be added here as needed.
];