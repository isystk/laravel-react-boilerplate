<?php

namespace Database\Seeders;

use App\Domain\Entities\OrderStock;
use Illuminate\Database\Seeder;

class OrderTableSeeder extends Seeder
{
    public function run(): void
    {
        OrderStock::factory()->count(100)->create();
    }
}
