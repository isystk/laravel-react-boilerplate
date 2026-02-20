<?php

namespace Database\Seeders;

use App\Domain\Entities\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'              => 'ユーザー1',
            'email'             => 'user1@test.com',
            'password'          => Hash::make('password'),
            'email_verified_at' => Carbon::now(),
        ]);

        User::factory()->count(200)->create(); // 200個のダミーデータを作成する。
    }
}
