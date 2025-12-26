<?php

namespace Database\Seeders;

use App\Domain\Entities\ContactFormImage;
use Illuminate\Database\Seeder;

class ContactFormImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        ContactFormImage::truncate();
        ContactFormImage::create([
            'id' => 1,
            'contact_form_id' => 1,
            'file_name' => '1615273024sample.jpg',
        ]);
        ContactFormImage::create([
            'id' => 2,
            'contact_form_id' => 2,
            'file_name' => '1615273024sample.jpg',
        ]);
        ContactFormImage::create([
            'id' => 3,
            'contact_form_id' => 3,
            'file_name' => '1615273024sample.jpg',
        ]);
    }
}
