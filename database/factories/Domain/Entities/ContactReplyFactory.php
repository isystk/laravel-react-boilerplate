<?php

namespace Database\Factories\Domain\Entities;

use App\Domain\Entities\Admin;
use App\Domain\Entities\Contact;
use App\Domain\Entities\ContactReply;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ContactReply>
 */
class ContactReplyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ContactReply::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'contact_id' => Contact::factory(),
            'admin_id'   => Admin::factory(),
            'body'       => fake()->realText(200),
        ];
    }
}
