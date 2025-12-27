<?php

namespace Database\Seeders;

use App\Domain\Entities\ContactForm;
use Illuminate\Database\Seeder;

class ContactFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ContactForm::factory()->count(200)->create(); // 200個のダミーデータを作成する。
    }
}
