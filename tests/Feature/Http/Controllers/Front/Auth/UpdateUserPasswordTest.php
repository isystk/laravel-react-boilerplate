<?php

namespace Tests\Feature\Http\Controllers\Front\Auth;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\BaseTest;

class UpdateUserPasswordTest extends BaseTest
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_update_password(): void
    {
        $user = $this->createDefaultUser([
            'password' => Hash::make('old_password'),
        ]);

        $this->actingAs($user)
            ->put('/user/password', [
                'current_password'      => 'old_password',
                'password'              => 'new_password',
                'password_confirmation' => 'new_password',
            ])
            ->assertStatus(302);

        $user->refresh();
        $this->assertTrue(Hash::check('new_password', $user->password));
    }

    public function test_update_password_wrong_current_password(): void
    {
        $user = $this->createDefaultUser([
            'password' => Hash::make('correct_password'),
        ]);

        $this->actingAs($user)
            ->put('/user/password', [
                'current_password'      => 'wrong_password',
                'password'              => 'new_password',
                'password_confirmation' => 'new_password',
            ])
            ->assertSessionHasErrors('current_password', null, 'updatePassword');
    }

    public function test_update_password_confirmation_mismatch(): void
    {
        $user = $this->createDefaultUser([
            'password' => Hash::make('old_password'),
        ]);

        $this->actingAs($user)
            ->put('/user/password', [
                'current_password'      => 'old_password',
                'password'              => 'new_password',
                'password_confirmation' => 'different_password',
            ])
            ->assertSessionHasErrors('password', null, 'updatePassword');
    }

    public function test_update_password_requires_authentication(): void
    {
        $this->put('/user/password', [
            'current_password'      => 'old_password',
            'password'              => 'new_password',
            'password_confirmation' => 'new_password',
        ])
            ->assertRedirect('/login');
    }
}
