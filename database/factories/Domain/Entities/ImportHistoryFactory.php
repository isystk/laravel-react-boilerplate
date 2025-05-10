<?php

namespace Database\Factories\Domain\Entities;

use App\Domain\Entities\ImportHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImportHistoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ImportHistory::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'file_name' => $this->faker->text(255),
            'save_file_name' => $this->faker->text(255),
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
