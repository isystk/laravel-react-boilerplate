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
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->text(100),
            'detail' => $this->faker->realText(500),
            'price' => $this->faker->numberBetween(1, 6),
            'imgpath' => $this->faker->image('/tmp', 400, 300, null, false),
            'quantity' => $this->faker->numberBetween(0, 10),
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
