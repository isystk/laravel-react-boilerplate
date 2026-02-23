<?php

namespace Tests\Feature\Providers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class AppServiceProviderTest extends BaseTest
{
    use RefreshDatabase;

    public function test_skip_login_admin_works(): void
    {
        $this->refreshApplicationWithLocal();

        $admin    = $this->createDefaultAdmin();
        $response = $this->get("/skip-login/admin?id={$admin->id}");
        $response->assertRedirect(route('admin.home'));
        $this->assertAuthenticatedAs($admin, 'admin');
    }

    public function test_skip_login_user_not_found(): void
    {
        $this->refreshApplicationWithLocal();

        $response = $this->get('/skip-login/user?id=999');
        $response->assertOk();
        $response->assertSee('User not found.');
    }

    public function test_skip_login_admin_not_found(): void
    {
        $this->refreshApplicationWithLocal();

        $response = $this->get('/skip-login/admin?id=999');
        $response->assertOk();
        $response->assertSee('User not found.');
    }

    protected function tearDown(): void
    {
        putenv('APP_ENV=testing');
        $_SERVER['APP_ENV'] = 'testing';
        parent::tearDown();
    }
}
