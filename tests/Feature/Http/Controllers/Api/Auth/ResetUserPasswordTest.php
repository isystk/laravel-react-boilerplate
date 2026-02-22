<?php

namespace Http\Controllers\Api\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\BaseTest;

class ResetUserPasswordTest extends BaseTest
{
    use RefreshDatabase;

    public function test_reset_password(): void
    {
        $user  = $this->createDefaultUser(['email' => 'user@test.com']);
        $token = Password::broker()->createToken($user);

        $this->postJson('/api/reset-password', [
            'token'                 => $token,
            'email'                 => 'user@test.com',
            'password'              => 'new_password',
            'password_confirmation' => 'new_password',
        ])
            ->assertStatus(200);

        $user->refresh();
        $this->assertTrue(Hash::check('new_password', $user->password));
    }

    public function test_reset_password_validation_error_confirmation_mismatch(): void
    {
        $user  = $this->createDefaultUser(['email' => 'user@test.com']);
        $token = Password::broker()->createToken($user);

        $this->postJson('/api/reset-password', [
            'token'                 => $token,
            'email'                 => 'user@test.com',
            'password'              => 'new_password',
            'password_confirmation' => 'different_password',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('password');
    }

    public function test_reset_password_invalid_token(): void
    {
        $this->createDefaultUser(['email' => 'user@test.com']);

        $this->postJson('/api/reset-password', [
            'token'                 => 'invalid_token',
            'email'                 => 'user@test.com',
            'password'              => 'new_password',
            'password_confirmation' => 'new_password',
        ])
            ->assertStatus(422);
    }
}
