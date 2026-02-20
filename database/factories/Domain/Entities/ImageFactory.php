<?php

namespace Database\Factories\Domain\Entities;

use App\Domain\Entities\Image;
use App\Enums\ImageType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Image>
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
            'type'      => ImageType::Stock->value,
        ];
    }
}
