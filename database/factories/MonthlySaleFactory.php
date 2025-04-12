<?php

namespace Database\Factories\Domain\Entities;

use App\Domain\Entities\MonthlySale;
use Illuminate\Database\Eloquent\Factories\Factory;

class MonthlySaleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MonthlySale::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
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
