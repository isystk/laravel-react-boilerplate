<?php

namespace Tests\Feature\Http\Controllers\Front\Auth;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\BaseTest;

class UpdateUserProfileInformationTest extends BaseTest
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_update_profile(): void
    {
        $user = $this->createDefaultUser([
            'name'  => 'old_name',
            'email' => 'old@test.com',
        ]);

        $this->actingAs($user)
            ->put('/user/profile-information', [
                'name'  => 'new_name',
                'email' => 'old@test.com',
            ])
            ->assertStatus(302);

        $user->refresh();
        $this->assertSame('new_name', $user->name);
        $this->assertNotNull($user->email_verified_at);
    }

    public function test_update_profile_email_changed_resets_verification(): void
    {
        Notification::fake();

        $user = $this->createDefaultUser([
            'name'              => 'user1',
            'email'             => 'old@test.com',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)
            ->put('/user/profile-information', [
                'name'  => 'user1',
                'email' => 'new@test.com',
            ])
            ->assertStatus(302);

        $user->refresh();
        $this->assertSame('new@test.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_update_profile_validation_error_name_required(): void
    {
        $user = $this->createDefaultUser();

        $this->actingAs($user)
            ->put('/user/profile-information', [
                'email' => 'test@test.com',
            ])
            ->assertSessionHasErrors('name', null, 'updateProfileInformation');
    }

    public function test_update_profile_validation_error_email_duplicate(): void
    {
        $this->createDefaultUser(['email' => 'existing@test.com']);
        $user = $this->createDefaultUser(['email' => 'mine@test.com']);

        $this->actingAs($user)
            ->put('/user/profile-information', [
                'name'  => 'user',
                'email' => 'existing@test.com',
            ])
            ->assertSessionHasErrors('email', null, 'updateProfileInformation');
    }

    public function test_update_profile_same_email_allowed(): void
    {
        $user = $this->createDefaultUser(['email' => 'same@test.com']);

        $this->actingAs($user)
            ->put('/user/profile-information', [
                'name'  => 'updated_name',
                'email' => 'same@test.com',
            ])
            ->assertStatus(302);

        $user->refresh();
        $this->assertSame('updated_name', $user->name);
        $this->assertSame('same@test.com', $user->email);
    }
}
