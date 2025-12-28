<?php

namespace Database\Seeders;

use App\Domain\Entities\ContactFormImage;
use Illuminate\Database\Seeder;

class ContactFormSeeder extends Seeder
{
    public function run(): void
    {
        ContactFormImage::factory()->count(100)->create();
    }
}
