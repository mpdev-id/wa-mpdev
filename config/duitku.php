<?php

return [
    'merchant_key' => env('DUITKU_MERCHANT_KEY', '4128ae2976605e9e410287af2287d7d1'),
    'merchant_code' => env('DUITKU_MERCHANT_CODE', 'DS25587'),
    'sandbox' => filter_var(env('DUITKU_SANDBOX', true), FILTER_VALIDATE_BOOLEAN), // Set to false for production
];
