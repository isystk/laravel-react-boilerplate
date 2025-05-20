<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    */
    'paths' => ['api/*'],

    'allowed_methods' => ['*'],

    // フロントエンドの開発環境のオリジンを全て書く
    'allowed_origins' => [
        'http://localhost:3000',
        'http://127.0.0.1:3000',
    ],

    'allowed_headers' => ['*'],

    'supports_credentials' => true,

    'max_age' => 0,
];
