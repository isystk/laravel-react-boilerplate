<?php

namespace Database\Factories\Domain\Entities;

use App\Domain\Entities\Stock;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Domain\Entities\Stock>
 */
class StockFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Stock::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name'            => fake()->text(50),
            'detail'          => fake()->realText(500),
            'price'           => fake()->numberBetween(1, 6),
            'image_file_name' => fake()->image('/tmp', 400, 300, null, false),
            'quantity'        => fake()->numberBetween(0, 10),
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
