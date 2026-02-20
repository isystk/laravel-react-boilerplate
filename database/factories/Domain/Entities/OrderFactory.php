<?php

namespace Database\Factories\Domain\Entities;

use App\Domain\Entities\Order;
use App\Domain\Entities\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
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
        // 1年前から現在までのランダムな日時を生成
        $createdAt = fake()->dateTimeBetween('-1 year', 'now');

        return [
            'user_id'    => User::factory(),
            'sum_price'  => fake()->numberBetween(1000, 100000),
            'created_at' => $createdAt,
            'updated_at' => fake()->dateTimeBetween($createdAt, 'now'),
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
