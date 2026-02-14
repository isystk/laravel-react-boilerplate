<?php

namespace Database\Factories\Domain\Entities;

use App\Domain\Entities\Image;
use App\Enums\PhotoType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Domain\Entities\Image>
 */
class ImageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Image::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'file_name' => fake()->image('/tmp', 400, 300, null, false),
            'type'      => PhotoType::Stock->value,
        ];
    }
}
