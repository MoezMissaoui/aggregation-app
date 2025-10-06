<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration options for the API microservice.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | API Keys
    |--------------------------------------------------------------------------
    |
    | Valid API keys for accessing protected endpoints.
    | In production, these should be stored in environment variables.
    |
    */
    'keys' => [
        env('API_KEY_1', 'test-api-key-123'),
        env('API_KEY_2', 'dev-api-key-456'),
        // Add more API keys as needed
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Configure rate limiting for API endpoints.
    |
    */
    'rate_limit' => [
        'requests_per_minute' => env('API_RATE_LIMIT', 60),
        'burst_limit' => env('API_BURST_LIMIT', 100),
    ],

    /*
    |--------------------------------------------------------------------------
    | API Version
    |--------------------------------------------------------------------------
    |
    | Current API version information.
    |
    */
    'version' => [
        'current' => '1.0.0',
        'supported' => ['1.0.0'],
        'deprecated' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Response Settings
    |--------------------------------------------------------------------------
    |
    | Default settings for API responses.
    |
    */
    'response' => [
        'default_per_page' => 15,
        'max_per_page' => 100,
        'include_debug_info' => env('API_DEBUG', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | CORS Settings
    |--------------------------------------------------------------------------
    |
    | Cross-Origin Resource Sharing configuration.
    |
    */
    'cors' => [
        'allowed_origins' => env('API_ALLOWED_ORIGINS', '*'),
        'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],
        'allowed_headers' => ['Content-Type', 'Authorization', 'X-API-Key', 'Accept'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    |
    | API request and response logging configuration.
    |
    */
    'logging' => [
        'enabled' => env('API_LOGGING_ENABLED', true),
        'log_requests' => env('API_LOG_REQUESTS', true),
        'log_responses' => env('API_LOG_RESPONSES', false),
        'log_channel' => env('API_LOG_CHANNEL', 'daily'),
    ],

];