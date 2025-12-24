<?php

namespace Http\Controllers\Admin\Staff;

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

    /**
     * スタッフ編集画面表示のテスト
     */
    public function test_edit(): void
    {
        $admin1 = $this->createDefaultAdmin([
            'name' => '管理者A',
            'role' => 'manager',
        ]);
        $this->actingAs($admin1, 'admin');

        // manager権限ではアクセスできないことのテスト
        $response = $this->get(route('admin.staff.edit', $admin1));
        $response->assertForbidden();

        $admin2 = $this->createDefaultAdmin([
            'name' => '管理者2',
            'role' => 'high-manager',
        ]);
        $this->actingAs($admin2, 'admin');

        $response = $this->get(route('admin.staff.edit', $admin1));
        $response->assertSuccessful();
    }

    /**
     * スタッフ編集画面 変更のテスト
     */
    public function test_update(): void
    {
        $admin1 = $this->createDefaultAdmin([
            'name' => '管理者1',
            'email' => 'admin1@test.com',
            'role' => 'manager',
        ]);
        $this->actingAs($admin1, 'admin');

        // manager権限ではアクセスできないことのテスト
        $response = $this->put(route('admin.staff.update', $admin1), []);
        $response->assertForbidden();

        $admin2 = $this->createDefaultAdmin([
            'name' => '管理者2',
            'email' => 'admin2@test.com',
            'role' => 'high-manager',
        ]);
        $this->actingAs($admin2, 'admin');

        $redirectResponse = $this->put(route('admin.staff.update', $admin1), [
            'name' => '管理者A',
            'email' => 'adminA@test.com',
            'role' => 'high-manager',
        ]);
        $response = $this->get($redirectResponse->headers->get('Location'));
        $response->assertSuccessful();

        // データが更新されたことをテスト
        $this->assertDatabaseHas('admins', [
            'id' => $admin1->id,
            'name' => '管理者A',
            'email' => 'adminA@test.com',
            'role' => 'high-manager',
        ]);
    }
}
