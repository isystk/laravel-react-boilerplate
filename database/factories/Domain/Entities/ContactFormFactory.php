<?php

namespace Database\Factories\Domain\Entities;

use App\Domain\Entities\ContactForm;
use App\Domain\Entities\Image;
use App\Enums\Age;
use App\Enums\Gender;
use App\Enums\ImageType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Domain\Entities\ContactForm>
 */
class ContactFormFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ContactForm::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_name' => fake()->text(20),
            'title'     => fake()->realText(50),
            'email'     => fake()->unique()->email,
            'url'       => fake()->url,
            'gender'    => fake()->randomElement(Gender::cases()),
            'age'       => fake()->randomElement(Age::cases()),
            'contact'   => fake()->realText(200),
            'image_id'  => Image::factory()->state(['type' => ImageType::Contact->value]),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): Factory
    {
        return $this->state(fn (array $attributes) => [
        ]);
    }
}
