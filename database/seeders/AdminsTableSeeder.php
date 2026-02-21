<?php

namespace Database\Seeders;

use App\Domain\Entities\Admin;
use App\Enums\AdminRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminsTableSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'name'     => '管理者1',
            'email'    => 'admin1@test.com',
            'password' => Hash::make('password'),
            'role'     => AdminRole::HighManager->value,
        ]);
        Admin::create([
            'name'     => '管理者2',
            'email'    => 'admin2@test.com',
            'password' => Hash::make('password'),
            'role'     => AdminRole::Manager->value,
        ]);

    }
}
