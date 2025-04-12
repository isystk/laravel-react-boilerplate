<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactFormImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('contact_form_images')->truncate();
        DB::table('contact_form_images')->insert([
            [
                'id' => 1,
                'contact_form_id' => 1,
                'file_name' => '1615273024sample.jpg',
            ],
            [
                'id' => 2,
                'contact_form_id' => 2,
                'file_name' => '1615273024sample.jpg',
            ],
            [
                'id' => 3,
                'contact_form_id' => 3,
                'file_name' => '1615273024sample.jpg',
            ]
        ]);
    }
}
