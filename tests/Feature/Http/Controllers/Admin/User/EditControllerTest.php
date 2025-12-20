<?php

namespace Http\Controllers\Admin\User;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EditControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    /**
     * ユーザー編集画面表示のテスト
     */
    public function test_edit(): void
    {
        $user1 = $this->createDefaultUser([
            'name' => 'user1',
            'email' => 'user1@test.com',
        ]);

        $admin1 = $this->createDefaultAdmin([
            'name' => '管理者A',
            'role' => 'manager',
        ]);
        $this->actingAs($admin1, 'admin');

        // manager権限ではアクセスできないことのテスト
        $response = $this->get(route('admin.user.edit', $user1));
        $response->assertForbidden();

        $admin2 = $this->createDefaultAdmin([
            'name' => '管理者2',
            'role' => 'high-manager',
        ]);
        $this->actingAs($admin2, 'admin');

        $response = $this->get(route('admin.user.edit', $user1));
        $response->assertSuccessful();
    }

    /**
     * ユーザー編集画面 変更のテスト
     */
    public function test_update(): void
    {
        $user1 = $this->createDefaultUser([
            'name' => 'user1',
            'email' => 'user1@test.com',
        ]);

        $admin1 = $this->createDefaultAdmin([
            'name' => '管理者1',
            'role' => 'manager',
        ]);
        $this->actingAs($admin1, 'admin');

        // manager権限ではアクセスできないことのテスト
        $response = $this->put(route('admin.user.update', $user1), []);
        $response->assertForbidden();

        $admin2 = $this->createDefaultAdmin([
            'name' => '管理者2',
            'role' => 'high-manager',
        ]);
        $this->actingAs($admin2, 'admin');

        $redirectResponse = $this->put(route('admin.user.update', $user1), [
            'name' => 'userA',
            'email' => 'userA@test.com',
        ]);
        $response = $this->get($redirectResponse->headers->get('Location'));
        $response->assertSuccessful();

        // データが更新されたことをテスト
        $this->assertDatabaseHas('users', [
            'id' => $user1->id,
            'name' => 'userA',
            'email' => 'userA@test.com',
        ]);
    }
}
