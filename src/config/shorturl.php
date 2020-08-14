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

    'base_url' => env('API_BASE_URL'),

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    |
    | This array is used by the api client to connect to short url app
    |
    */

    'auth' => [
        'id' => env('API_ID'),
        'token' => env('API_TOKEN'),
    ],
];
