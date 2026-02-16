<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Domain\Entities\Image;
use App\Enums\ImageType;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class SessionControllerTest extends BaseTest
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_index_ログイン情報が返却されること(): void
    {
        $user = $this->createDefaultUser([
            'name'  => 'テストユーザー',
            'email' => 'test@test.com',
        ]);
        $this->actingAs($user);

        $response = $this->postJson(route('api.session'));

        $response->assertOk();
        $response->assertJson([
            'name'              => 'テストユーザー',
            'email'             => 'test@test.com',
            'email_verified_at' => $user->email_verified_at->toJSON(),
            'avatar_url'        => null,
        ]);
    }

    public function test_index_アバター画像がある場合URLが返却されること(): void
    {
        $image = Image::factory()->create([
            'file_name' => 'avatar.png',
            'type'      => ImageType::User->value,
        ]);

        $user = $this->createDefaultUser([
            'name'            => 'テストユーザー',
            'email'           => 'test@test.com',
            'avatar_image_id' => $image->id,
        ]);
        $this->actingAs($user);

        $response = $this->postJson(route('api.session'));

        $response->assertOk();
        $response->assertJson([
            'name'       => 'テストユーザー',
            'email'      => 'test@test.com',
            'avatar_url' => $image->getImageUrl(),
        ]);
    }

    public function test_index_GoogleアバターURLがある場合URLが返却されること(): void
    {
        $user = $this->createDefaultUser([
            'name'       => 'Googleユーザー',
            'email'      => 'google@test.com',
            'avatar_url' => 'https://lh3.googleusercontent.com/avatar.png',
        ]);
        $this->actingAs($user);

        $response = $this->postJson(route('api.session'));

        $response->assertOk();
        $response->assertJson([
            'name'       => 'Googleユーザー',
            'email'      => 'google@test.com',
            'avatar_url' => 'https://lh3.googleusercontent.com/avatar.png',
        ]);
    }

    public function test_index_未認証の場合は401が返却されること(): void
    {
        $response = $this->postJson(route('api.session'));

        $response->assertStatus(401);
    }
}
