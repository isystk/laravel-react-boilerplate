<?php

return [

    'upload_max_filesize' => 104857600, // 100MB

    'maxlength' => [
        'commons' => [
            'date' => 10
        ],
        'users' => [
            'name' => 50,
            'email' => 64,
            'password' => 12,
        ],
        'admins' => [
            'name' => 50,
            'email' => 64,
            'password' => 12,
        ],
        'stocks' => [
            'name' => 50,
            'detail' => 500,
            'price' => 10,
            'quantity' => 10,
        ],
        'carts' => [
        ],
        'orders' => [
            'price' => 10,
            'quantity' => 10,
        ],
        'contact_forms' => [
            'user_name' => 50,
            'title' => 50,
            'email' => 64,
            'url' => 255,
            'contact' => 200
        ],
        'contact_form_images' => [
         ],
    ],

    'stripe' => [
        'secret' => env('STRIPE_SECRET', ''),
     ],
];
