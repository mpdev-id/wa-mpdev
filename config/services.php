<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'whatsapp' => [
        'server_url' => env('WA_SERVER_URL', 'http://localhost:3005'),
        'delay_time' => env('DELAY_TIME', 1000),
    ],

    /*
    |--------------------------------------------------------------------------
    | QRIS Android Webhook Token
    |--------------------------------------------------------------------------
    | Secret token used to authenticate incoming requests from the Android
    | device running the QRIS notification listener.
    | Set ANDROID_QRIS_TOKEN in your .env file.
    | Can also be managed via the admin panel at /admin/qris-android.
    */
    'qris_android_token' => env('ANDROID_QRIS_TOKEN', ''),

];
