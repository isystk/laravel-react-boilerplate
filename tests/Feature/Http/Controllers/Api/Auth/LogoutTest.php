<?php

namespace Tests\Feature\Http\Controllers\Api\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\BaseTest;

class LogoutTest extends BaseTest
{
    use RefreshDatabase;

    public function test_logout_success(): void
    {
        $user = $this->createDefaultUser();
        Auth::login($user);

        $this->assertAuthenticatedAs($user);

        $response = $this->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Logged out successfully']);

        $this->assertGuest();
    }

    public function test_logout_as_guest(): void
    {
        $response = $this->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Logged out successfully']);

        $this->assertGuest();
    }
}
