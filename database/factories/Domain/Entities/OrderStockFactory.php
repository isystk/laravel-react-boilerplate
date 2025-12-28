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
     */
    public function definition(): array
    {
        return [
            'order_id' => \App\Domain\Entities\Order::factory(),
            'stock_id' => \App\Domain\Entities\Stock::factory(),
            'price' => $this->faker->numberBetween(1000, 100000),
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
