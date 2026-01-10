<?php

namespace Database\Factories\Domain\Entities;

use App\Domain\Entities\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Domain\Entities\Order>
 */
class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id'   => \App\Domain\Entities\User::factory(),
            'sum_price' => fake()->numberBetween(1000, 100000),
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
