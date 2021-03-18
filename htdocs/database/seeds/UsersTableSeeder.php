<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->truncate();
        DB::table('users')->insert([
            [
                'name' => 'テスト1',
                'email' => 'test1@test.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
            ],
        ]);

        factory(User::class, 200)->create(); // 200個のダミーデータを作成する。
    }
}
