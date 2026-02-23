<?php

namespace Http\Controllers\Front\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\BaseTest;

class FrontEmailVerificationTest extends BaseTest
{
    use RefreshDatabase;

    public function test_verify_success(): void
    {
        $user = $this->createDefaultUser([
            'email_verified_at' => null,
        ]);

        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->getEmailForVerification())]
        );

        $response = $this->get($url);

        $response->assertRedirect(config('app.url') . '/home?verified=1');
        $this->assertTrue($user->refresh()->hasVerifiedEmail());
    }

    public function test_verify_failure_invalid_hash(): void
    {
        $user = $this->createDefaultUser([
            'email_verified_at' => null,
        ]);

        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => 'invalid-hash']
        );

        $response = $this->get($url);

        $response->assertStatus(403);
        $this->assertFalse($user->refresh()->hasVerifiedEmail());
    }

    public function test_verify_failure_invalid_signature(): void
    {
        $user = $this->createDefaultUser([
            'email_verified_at' => null,
        ]);

        $url = URL::route(
            'verification.verify',
            ['id' => $user->id, 'hash' => sha1($user->getEmailForVerification())]
        );

        // Access without signature
        $response = $this->get($url);

        $response->assertStatus(403);
    }

    public function test_verify_redirect_already_verified(): void
    {
        $user = $this->createDefaultUser([
            'email_verified_at' => now(),
        ]);

        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->getEmailForVerification())]
        );

        $response = $this->get($url);

        $response->assertRedirect(config('app.url') . '/home?verified=1');
    }

    public function test_verify_failure_user_not_found(): void
    {
        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => 99999, 'hash' => 'some-hash']
        );

        $response = $this->get($url);

        $response->assertStatus(403);
    }
}
