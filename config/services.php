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

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'currency' => [
        'base_uri' => env('CURRENCY_API_BASE_URL', 'https://api.exchangerate-api.com/'),
        'key' => env('CURRENCY_API_KEY'),
    ],

    'openweather' => [
        'base_uri' => env('OPENWEATHER_API_BASE_URL', 'https://api.openweathermap.org/'),
        'key' => env('OPENWEATHER_API_KEY'),
    ],

    'gnews' => [
        'base_uri' => env('GNEWS_API_BASE_URL', 'https://gnews.io/'),
        'key' => env('GNEWS_API_KEY'),
    ],

    'amadeus' => [
        'client_id' => env('AMADEUS_CLIENT_ID'),
        'client_secret' => env('AMADEUS_CLIENT_SECRET'),
        'base_uri' => env('AMADEUS_API_BASE', 'https://test.api.amadeus.com/'),
    ],
    
];
