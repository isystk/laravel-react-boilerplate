<?php

use App\Enums\AdminRole;

return [
    'permissions' => [
        'admin.stock.create'    => [AdminRole::SuperAdmin],
        'admin.stock.store'     => [AdminRole::SuperAdmin],
        'admin.stock.destroy'   => [AdminRole::SuperAdmin],
        'admin.stock.edit'      => [AdminRole::SuperAdmin],
        'admin.stock.update'    => [AdminRole::SuperAdmin],
        'admin.user.suspend'    => [AdminRole::SuperAdmin],
        'admin.user.activate'   => [AdminRole::SuperAdmin],
        'admin.user.edit'       => [AdminRole::SuperAdmin],
        'admin.user.update'     => [AdminRole::SuperAdmin],
        'admin.contact.destroy' => [AdminRole::SuperAdmin],
        'admin.contact.edit'    => [AdminRole::SuperAdmin],
        'admin.staff.create'    => [AdminRole::SuperAdmin],
        'admin.staff.store'     => [AdminRole::SuperAdmin],
        'admin.staff.destroy'   => [AdminRole::SuperAdmin],
        'admin.staff.edit'      => [AdminRole::SuperAdmin],
        'admin.staff.update'    => [AdminRole::SuperAdmin],
        'admin.staff.import.*'  => [AdminRole::SuperAdmin],
        'admin.contact.update'  => [AdminRole::SuperAdmin],
        'admin.photo.destroy'   => [AdminRole::SuperAdmin],
    ],
];
