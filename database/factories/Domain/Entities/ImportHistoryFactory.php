<?php

namespace Database\Factories\Domain\Entities;

use App\Domain\Entities\ImportHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ImportHistory>
 */
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
            'file_name'      => fake()->text(255),
            'save_file_name' => fake()->text(255),
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
