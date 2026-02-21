<?php

return [

    'upload_max_filesize' => 10485760, // 10MB

    'maxlength' => [
        'commons' => [
            'date' => 10,
        ],
        'users' => [
            'name'     => 50,
            'email'    => 64,
            'password' => 12,
        ],
        'admins' => [
            'name'     => 50,
            'email'    => 64,
            'password' => 12,
        ],
        'stocks' => [
            'name'     => 50,
            'detail'   => 500,
            'price'    => 10,
            'quantity' => 10,
        ],
        'carts' => [
        ],
        'orders' => [
            'price'    => 10,
            'quantity' => 10,
        ],
        'contacts' => [
            'title'   => 100,
            'message' => 500,
        ],
        'contact_replies' => [
            'body' => 1000,
        ],
    ],

    'mail' => [
        'subject' => [
            'reset_password_to_user'    => '【Laraec】パスワードリセットのご案内',
            'verify_email_to_user'      => '【Laraec】メールアドレス確認のお願い',
            'checkout_complete_to_user' => '【Laraec】ご購入ありがとうございます',
            'contact_reply_to_user'     => '【Laraec】お問い合わせへのご返信',
        ],
    ],

    'cookie' => [
        'like' => [
            'key'    => 'like',
            'expire' => 60 * 24, // 24時間
        ],
    ],

    'stripe' => [
        'secret' => env('STRIPE_SECRET', ''),
    ],
];
