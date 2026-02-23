<?php

namespace Http\Controllers\Api\Auth;

use App\Enums\UserStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\BaseTest;

class LoginTest extends BaseTest
{
    use RefreshDatabase;

    public function test_login_success(): void
    {
        $password = 'password';
        $user     = $this->createDefaultUser([
            'password' => Hash::make($password),
            'status'   => UserStatus::Active,
        ]);

        $response = $this->postJson('/api/login', [
            'email'    => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'name'  => $user->name,
                'email' => $user->email,
            ]);

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_failure_invalid_password(): void
    {
        $user = $this->createDefaultUser([
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email'    => $user->email,
            'password' => 'wrong_password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('email');

        $this->assertGuest();
    }

    public function test_login_failure_non_existent_user(): void
    {
        $response = $this->postJson('/api/login', [
            'email'    => 'nonexistent@test.com',
            'password' => 'password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('email');

        $this->assertGuest();
    }

    public function test_login_failure_suspended_user(): void
    {
        $password = 'password';
        $user     = $this->createDefaultUser([
            'password' => Hash::make($password),
            'status'   => UserStatus::Suspended,
        ]);

        $response = $this->postJson('/api/login', [
            'email'    => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('email')
            ->assertJsonPath('errors.email.0', __('auth.suspended'));

        $this->assertGuest();
    }

    public function test_login_validation_errors(): void
    {
        $response = $this->postJson('/api/login', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    }
}
