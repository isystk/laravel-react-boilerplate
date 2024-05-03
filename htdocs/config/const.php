<?php

return [

    'maxlength' => [
        'users' => [
            'name' => 50,
            'email' => 255,
            'password' => 8,
        ],
        'admins' => [
            'name' => 50,
            'email' => 255,
            'password' => 255,
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
            'your_name' => 50,
            'title' => 50,
            'email' => 255,
            'url' => 255,
            'contact' => 200
        ],
        'contact_form_images' => [
         ],
    ]
];
