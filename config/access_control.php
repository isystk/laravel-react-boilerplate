<?php

use App\Enums\AdminRole;

return [
    'permissions' => [
        'admin.stock.create' => [AdminRole::HighManager],
        'admin.stock.store' => [AdminRole::HighManager],
        'admin.stock.destroy' => [AdminRole::HighManager],
        'admin.stock.edit' => [AdminRole::HighManager],
        'admin.stock.update' => [AdminRole::HighManager],
        'admin.user.destroy' => [AdminRole::HighManager],
        'admin.user.edit' => [AdminRole::HighManager],
        'admin.user.update' => [AdminRole::HighManager],
        'admin.contact.destroy' => [AdminRole::HighManager],
        'admin.contact.edit' => [AdminRole::HighManager],
        'admin.staff.create' => [AdminRole::HighManager],
        'admin.staff.store' => [AdminRole::HighManager],
        'admin.staff.destroy' => [AdminRole::HighManager],
        'admin.staff.edit' => [AdminRole::HighManager],
        'admin.staff.update' => [AdminRole::HighManager],
        'admin.staff.import.*' => [AdminRole::HighManager],
        'admin.contact.update' => [AdminRole::HighManager],
        'admin.photo.destroy' => [AdminRole::HighManager],
    ],
];
