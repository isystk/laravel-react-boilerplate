<?php

namespace Database\Seeders;

use App\Domain\Entities\ContactForm;
use Illuminate\Database\Seeder;

class ContactFormSeeder extends Seeder
{
    public function run(): void
    {
        ContactForm::factory()->count(100)->create();
    }
}
