<?php

namespace Database\Seeders;

use App\Domain\Entities\Contact;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        Contact::factory()->count(100)->create();
    }
}
