<?php

namespace Database\Factories\Domain\Entities;

use App\Domain\Entities\OrderStock;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderStockFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderStock::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'price' => $this->faker->numberBetween(1, 6),
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
