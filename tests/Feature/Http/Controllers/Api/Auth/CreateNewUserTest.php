<?php

namespace Tests\Feature\Http\Controllers\Api\Auth;

use App\Domain\Entities\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class CreateNewUserTest extends BaseTest
{
    use RefreshDatabase;

    public function test_create(): void
    {
        $items = [
            'name'                  => 'user1',
            'email'                 => 'user1@test.com',
            'password'              => 'password',
            'password_confirmation' => 'password',
        ];
        $this->postJson('/api/register', $items)
            ->assertStatus(201);

        $user = User::where(['email' => $items['email']])->first();
        $this->assertSame($items['name'], $user->name, 'ユーザーが新規登録できることをテスト');
    }

    public function test_create_validation_error_name_required(): void
    {
        $items = [
            'email'                 => 'user1@test.com',
            'password'              => 'password',
            'password_confirmation' => 'password',
        ];
        $this->postJson('/api/register', $items)
            ->assertStatus(422)
            ->assertJsonValidationErrors('name');
    }

    public function test_create_validation_error_email_required(): void
    {
        $items = [
            'name'                  => 'user1',
            'password'              => 'password',
            'password_confirmation' => 'password',
        ];
        $this->postJson('/api/register', $items)
            ->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }

    public function test_create_validation_error_email_duplicate(): void
    {
        $this->createDefaultUser(['email' => 'existing@test.com']);

        $items = [
            'name'                  => 'user2',
            'email'                 => 'existing@test.com',
            'password'              => 'password',
            'password_confirmation' => 'password',
        ];
        $this->postJson('/api/register', $items)
            ->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }

    public function test_create_validation_error_password_confirmation_mismatch(): void
    {
        $items = [
            'name'                  => 'user1',
            'email'                 => 'user1@test.com',
            'password'              => 'password',
            'password_confirmation' => 'different',
        ];
        $this->postJson('/api/register', $items)
            ->assertStatus(422)
            ->assertJsonValidationErrors('password');
    }
}
