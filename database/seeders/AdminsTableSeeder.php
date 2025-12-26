<?php

namespace Database\Seeders;

use App\Domain\Entities\Admin;
use App\Enums\AdminRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Admin::truncate();
        Admin::create([
            'name' => '管理者A',
            'email' => 'sample@sample.com',
            'password' => Hash::make('password'),
            'role' => AdminRole::HighManager->value,
        ]);
    }
}
