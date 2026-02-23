<?php

namespace Tests\Feature\Providers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\BaseTest;

class AppServiceProviderTest extends BaseTest
{
    use RefreshDatabase;

    public function test_skip_login_routes_are_registered_in_local_environment(): void
    {
        // 環境を local に擬似的に変更
        $this->refreshApplicationWithLocal();

        $this->assertTrue(Route::has('admin.staff.import.export')); // 既存ルートの確認

        // skip-login ルートが存在することを確認
        $routes = Route::getRoutes();
        $this->assertNotNull($routes->getByName('admin')); // prefixなしでも動くか等

        // 実際には Route::has ではなく GET リクエストで存在確認するのが確実
        $user     = $this->createDefaultUser();
        $response = $this->get("/skip-login/user?id={$user->id}");
        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

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

    /**
     * 環境を local にしてアプリケーションを再起動する
     */
    protected function refreshApplicationWithLocal(): void
    {
        putenv('APP_ENV=local');
        $this->refreshApplication();
    }

    protected function tearDown(): void
    {
        putenv('APP_ENV=testing');
        parent::tearDown();
    }
}
