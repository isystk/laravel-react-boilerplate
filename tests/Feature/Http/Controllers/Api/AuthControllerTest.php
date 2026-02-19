<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Domain\Entities\Image;
use App\Enums\ImageType;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthControllerTest extends BaseTest
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_login_正しい認証情報でトークンが返却されること(): void
    {
        $user = $this->createDefaultUser([
            'email'    => 'test@test.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email'    => 'test@test.com',
            'password' => 'password',
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['token']);
    }

    public function test_login_不正な認証情報で401が返却されること(): void
    {
        $this->createDefaultUser([
            'email'    => 'test@test.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email'    => 'test@test.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401);
        $response->assertJson(['error' => 'Unauthorized']);
    }

    public function test_login_アカウント停止中のユーザーは401が返却されること(): void
    {
        $this->createDefaultUser([
            'email'    => 'test@test.com',
            'password' => bcrypt('password'),
            'status'   => \App\Enums\UserStatus::Suspended->value,
        ]);

        $response = $this->postJson('/api/login', [
            'email'    => 'test@test.com',
            'password' => 'password',
        ]);

        $response->assertStatus(401);
        $response->assertJson(['error' => 'Unauthorized']);
    }

    public function test_logout_ログアウトが成功すること(): void
    {
        $user  = $this->createDefaultUser();
        $token = JWTAuth::fromUser($user);

        $response = $this->postJson('/api/logout', [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertOk();
        $response->assertJson(['message' => 'Successfully logged out']);
    }

    public function test_authenticate_ログインユーザーの情報が返却されること(): void
    {
        $user  = $this->createDefaultUser(['name' => 'テストユーザー']);
        $token = JWTAuth::fromUser($user);

        $response = $this->getJson('/api/authenticate', [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertOk();
        $response->assertJson([
            'name'      => 'テストユーザー',
            'avatarUrl' => null,
        ]);
    }

    public function test_authenticate_アバター画像がある場合URLが返却されること(): void
    {
        $image = Image::factory()->create([
            'file_name' => 'avatar.png',
            'type'      => ImageType::User->value,
        ]);

        $user = $this->createDefaultUser([
            'name'            => 'テストユーザー',
            'avatar_image_id' => $image->id,
        ]);
        $token = JWTAuth::fromUser($user);

        $response = $this->getJson('/api/authenticate', [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertOk();
        $response->assertJson([
            'name'      => 'テストユーザー',
            'avatarUrl' => $image->getImageUrl(),
        ]);
    }

    public function test_authenticate_アバターURLがある場合URLが返却されること(): void
    {
        $user = $this->createDefaultUser([
            'name'       => 'Googleユーザー',
            'avatar_url' => 'https://lh3.googleusercontent.com/avatar.png',
        ]);
        $token = JWTAuth::fromUser($user);

        $response = $this->getJson('/api/authenticate', [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertOk();
        $response->assertJson([
            'name'      => 'Googleユーザー',
            'avatarUrl' => 'https://lh3.googleusercontent.com/avatar.png',
        ]);
    }

    public function test_authenticate_未認証の場合は401が返却されること(): void
    {
        $response = $this->getJson('/api/authenticate');

        $response->assertStatus(401);
    }
}
