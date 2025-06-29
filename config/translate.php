<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Translation Service Configuration
    |--------------------------------------------------------------------------
    */

    'enabled' => env('TRANSLATE_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Default Translation Provider
    |--------------------------------------------------------------------------
    | Supported: "google", "stichoza", "mock"
    */
    'default_provider' => env('TRANSLATE_PROVIDER', 'stichoza'),

    /*
    |--------------------------------------------------------------------------
    | Google Translate API Configuration
    |--------------------------------------------------------------------------
    */
    'google' => [
        'api_key' => env('GOOGLE_TRANSLATE_API_KEY'),
        'endpoint' => 'https://translation.googleapis.com/language/translate/v2',
    ],

    /*
    |--------------------------------------------------------------------------
    | Usage Quotas
    |--------------------------------------------------------------------------
    */
    'daily_quota' => env('TRANSLATE_DAILY_QUOTA', 10000),
    'monthly_quota' => env('TRANSLATE_MONTHLY_QUOTA', 100000),

    /*
    |--------------------------------------------------------------------------
    | Supported Languages
    |--------------------------------------------------------------------------
    */
    'supported_languages' => [
        'th' => 'Thai',
        'en' => 'English',
        'ja' => 'Japanese',
        'ko' => 'Korean',
        'zh' => 'Chinese',
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    */
    'cache' => [
        'enabled' => env('TRANSLATE_CACHE_ENABLED', true),
        'ttl' => env('TRANSLATE_CACHE_TTL', 3600), // 1 hour
        'prefix' => 'translate',
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    */
    'rate_limit' => [
        'requests_per_minute' => env('TRANSLATE_RATE_LIMIT', 100),
        'delay_between_requests' => env('TRANSLATE_DELAY_MS', 200), // milliseconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Fallback Configuration
    |--------------------------------------------------------------------------
    */
    'fallback' => [
        'enabled' => true,
        'use_mock_when_failed' => true,
        'mock_prefix' => '[TRANSLATED]',
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    */
    'logging' => [
        'enabled' => env('TRANSLATE_LOGGING', true),
        'level' => env('TRANSLATE_LOG_LEVEL', 'info'),
        'channel' => env('TRANSLATE_LOG_CHANNEL', 'single'),
    ],
];
