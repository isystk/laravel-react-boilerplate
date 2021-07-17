<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('admins')->truncate();
        DB::table('admins')->insert([
            [
                'name' => '管理者A',
                'email' => 'sample@sample.com',
                'password' => Hash::make('password'),
            ]
        ]);
    }
}
