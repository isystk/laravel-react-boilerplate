<?php

namespace Tests\Feature\Http\Controllers\Front\Auth;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\BaseTest;

class ResetUserPasswordTest extends BaseTest
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_reset_password(): void
    {
        $user  = $this->createDefaultUser(['email' => 'user@test.com']);
        $token = Password::broker()->createToken($user);

        $this->post('/reset-password', [
            'token'                 => $token,
            'email'                 => 'user@test.com',
            'password'              => 'new_password',
            'password_confirmation' => 'new_password',
        ])
            ->assertStatus(302);

        $user->refresh();
        $this->assertTrue(Hash::check('new_password', $user->password));
    }

    public function test_reset_password_validation_error_confirmation_mismatch(): void
    {
        $user  = $this->createDefaultUser(['email' => 'user@test.com']);
        $token = Password::broker()->createToken($user);

        $this->post('/reset-password', [
            'token'                 => $token,
            'email'                 => 'user@test.com',
            'password'              => 'new_password',
            'password_confirmation' => 'different_password',
        ])
            ->assertSessionHasErrors('password');
    }

    public function test_reset_password_invalid_token(): void
    {
        $this->createDefaultUser(['email' => 'user@test.com']);

        $this->post('/reset-password', [
            'token'                 => 'invalid_token',
            'email'                 => 'user@test.com',
            'password'              => 'new_password',
            'password_confirmation' => 'new_password',
        ])
            ->assertSessionHasErrors('email');
    }
}
