<?php

namespace Http\Controllers\Api\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Password;
use Tests\BaseTest;

class ForgotPasswordTest extends BaseTest
{
    use RefreshDatabase;

    public function test_forgot_password_success(): void
    {
        $user = $this->createDefaultUser(['email' => 'user@test.com']);

        $response = $this->postJson('/api/forgot-password', [
            'email' => 'user@test.com',
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => __(Password::RESET_LINK_SENT)]);
    }

    public function test_forgot_password_failure_non_existent_email(): void
    {
        $response = $this->postJson('/api/forgot-password', [
            'email' => 'nonexistent@test.com',
        ]);

        $response->assertStatus(422)
            ->assertJson(['message' => __(Password::INVALID_USER)]);
    }

    public function test_forgot_password_validation_errors(): void
    {
        $response = $this->postJson('/api/forgot-password', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }
}
