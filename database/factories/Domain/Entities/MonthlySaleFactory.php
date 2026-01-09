<?php

namespace Database\Factories\Domain\Entities;

use App\Domain\Entities\MonthlySale;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Domain\Entities\MonthlySale>
 */
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
     */
    public function definition(): array
    {
        return [
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
