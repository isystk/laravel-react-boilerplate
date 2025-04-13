<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UsersTableSeeder::class);
        $this->call(AdminsTableSeeder::class);
        $this->call(StockTableSeeder::class);
        $this->call(ContactFormSeeder::class);
        $this->call(ContactFormImageSeeder::class);
    }
}
