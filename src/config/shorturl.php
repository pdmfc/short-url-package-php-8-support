<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    |
    | This array is used by the api client to connect to short url app
    |
    */

    'base_url' => env('SHORT_URL_API_BASE_URL'),

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    |
    | This array is used by the api client to connect to short url app
    |
    */

    'auth' => [
        'id' => env('SHORT_URL_API_ID'),
        'token' => env('SHORT_URL_API_TOKEN'),
    ],
];
