<?php

namespace Database\Factories\Domain\Entities;

use App\Domain\Entities\ContactForm;
use Illuminate\Database\Eloquent\Factories\Factory;

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
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'user_name' => $this->faker->text(20),
            'title' => $this->faker->realText(50),
            'email' => $this->faker->unique()->email,
            'url' => $this->faker->url,
            'gender' => $this->faker->randomElement(['0', '1']),
            'age' => $this->faker->numberBetween($min = 1, $max = 6),
            'contact' => $this->faker->realText(200),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return Factory
     */
    public function unverified(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
            ];
        });
    }
}
