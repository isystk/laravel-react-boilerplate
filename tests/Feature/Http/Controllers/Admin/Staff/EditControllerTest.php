<?php

namespace Http\Controllers\Admin\Staff;

use App\Enums\AdminRole;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class EditControllerTest extends BaseTest
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_edit(): void
    {
        $admin1 = $this->createDefaultAdmin([
            'name' => '管理者A',
            'role' => AdminRole::Manager,
        ]);
        $this->actingAs($admin1, 'admin');

        // manager権限ではアクセスできないことのテスト
        $response = $this->get(route('admin.staff.edit', $admin1));
        $response->assertForbidden();

        $admin2 = $this->createDefaultAdmin([
            'name' => '管理者2',
            'role' => AdminRole::HighManager,
        ]);
        $this->actingAs($admin2, 'admin');

        $response = $this->get(route('admin.staff.edit', $admin1));
        $response->assertSuccessful();
    }

    public function test_update(): void
    {
        $admin1 = $this->createDefaultAdmin([
            'name' => '管理者1',
            'email' => 'admin1@test.com',
            'role' => AdminRole::Manager,
        ]);
        $this->actingAs($admin1, 'admin');

        // manager権限ではアクセスできないことのテスト
        $response = $this->put(route('admin.staff.update', $admin1), []);
        $response->assertForbidden();

        $admin2 = $this->createDefaultAdmin([
            'name' => '管理者2',
            'email' => 'admin2@test.com',
            'role' => AdminRole::HighManager,
        ]);
        $this->actingAs($admin2, 'admin');

        $redirectResponse = $this->put(route('admin.staff.update', $admin1), [
            'name' => '管理者A',
            'email' => 'adminA@test.com',
            'role' => AdminRole::HighManager->value,
        ]);
        $response = $this->get($redirectResponse->headers->get('Location'));
        $response->assertSuccessful();

        // データが更新されたことをテスト
        $this->assertDatabaseHas('admins', [
            'id' => $admin1->id,
            'name' => '管理者A',
            'email' => 'adminA@test.com',
            'role' => AdminRole::HighManager,
        ]);
    }
}
