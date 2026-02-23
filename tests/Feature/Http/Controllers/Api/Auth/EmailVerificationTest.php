<?php

namespace Tests\Feature\Http\Controllers\Api\Auth;

use App\Mails\VerifyEmailToUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Tests\BaseTest;

class EmailVerificationTest extends BaseTest
{
    use RefreshDatabase;

    public function test_resend_verification_notification_success(): void
    {
        Notification::fake();

        $user = $this->createDefaultUser([
            'email_verified_at' => null,
        ]);
        Auth::login($user);

        $response = $this->postJson('/api/email/verification-notification');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Verification link sent']);

        Notification::assertSentTo($user, VerifyEmailToUser::class);
    }

    public function test_resend_verification_notification_already_verified(): void
    {
        Notification::fake();

        $user = $this->createDefaultUser([
            'email_verified_at' => now(),
        ]);
        Auth::login($user);

        $response = $this->postJson('/api/email/verification-notification');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Already verified']);

        Notification::assertNotSentTo($user, VerifyEmailToUser::class);
    }

    public function test_resend_verification_notification_unauthenticated(): void
    {
        $response = $this->postJson('/api/email/verification-notification');

        $response->assertStatus(401);
    }
}
