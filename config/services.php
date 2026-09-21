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

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

   'easebuzz' => [
        'key'  => env('EASEBUZZ_KEY'),
        'salt' => env('EASEBUZZ_SALT'),
        'env'  => env('EASEBUZZ_ENV', 'prod'),
    ],

    'dovesoft' => [
        'key'          => env('DOVESOFT_API_KEY'),
        'waba_number'  => env('DOVESOFT_WABA_NUMBER'),
        'api_url'      => env('DOVESOFT_API_URL'),
        'admin_number' => env('ADMIN_WHATSAPP_NUMBER', '919158980898'),
    ],

];
