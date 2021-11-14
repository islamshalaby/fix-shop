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
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'facebook' => [
        'client_id' => '222878809766585',
        'client_secret' => '49fc87cd1cdbcba151b56d0a766b5dc3',
        'redirect' => env('APP_URL').'/callback',
    ],
    'google' => [
        'client_id' => '415811579142-l2psbmf6le5mdbbpn6qe3ngbp2rr2vot.apps.googleusercontent.com',
        'client_secret' => '60xX2vzhactEDnql1jVlzcXi',
        'redirect' => env('APP_URL').'/auth/google/callback',
    ],

];
