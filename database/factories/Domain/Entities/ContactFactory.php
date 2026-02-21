<?php

namespace Database\Factories\Domain\Entities;

use App\Domain\Entities\Contact;
use App\Domain\Entities\Image;
use App\Domain\Entities\User;
use App\Enums\ContactType;
use App\Enums\ImageType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Contact>
 */
class ContactFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Contact::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        // 1年前から現在までのランダムな日時を生成
        $createdAt = fake()->dateTimeBetween('-1 year', 'now');

        return [
            'user_id'    => User::factory(),
            'type'       => fake()->randomElement(ContactType::cases()),
            'title'      => fake()->realText(100),
            'message'    => fake()->realText(500),
            'image_id'   => Image::factory()->state(['type' => ImageType::Contact->value]),
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
