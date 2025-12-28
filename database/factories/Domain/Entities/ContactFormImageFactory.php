<?php

namespace Database\Factories\Domain\Entities;

use App\Domain\Entities\ContactForm;
use App\Domain\Entities\ContactFormImage;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFormImageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ContactFormImage::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'contact_form_id' => ContactForm::factory(),
            'file_name' => $this->faker->image('/tmp', 400, 300, null, false),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
            ];
        });
    }
}
