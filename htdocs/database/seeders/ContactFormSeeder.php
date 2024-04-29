<?php

namespace Database\Seeders;

use App\Entities\ContactForm;
use Illuminate\Database\Seeder;

class ContactFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        ContactForm::factory()->count(200)->create(); // 200個のダミーデータを作成する。
    }
}
