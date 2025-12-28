<?php

namespace Database\Factories\Domain\Entities;

use App\Domain\Entities\Stock;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'name' => $this->faker->text(50),
            'detail' => $this->faker->realText(500),
            'price' => $this->faker->numberBetween(1, 6),
            'image_file_name' => $this->faker->image('/tmp', 400, 300, null, false),
            'quantity' => $this->faker->numberBetween(0, 10),
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
