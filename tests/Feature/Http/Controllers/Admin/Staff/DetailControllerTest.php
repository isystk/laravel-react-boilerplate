<?php

namespace Http\Controllers\Admin\Staff;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DetailControllerTest extends TestCase
{

    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    /**
     * スタッフ詳細画面表示のテスト
     */
    public function testShow(): void
    {
        $admin1 = $this->createDefaultAdmin([
            'name' => '管理者A',
            'email' => 'admin1@test.com',
            'role' => 'high-manager'
        ]);
        $this->actingAs($admin1, 'admin');

        $response = $this->get(route('admin.staff.show', $admin1));
        $response->assertSuccessful();
        $response->assertSee('管理者A');
        $response->assertSee('admin1@test.com');
        $response->assertSee('上位管理者');
    }

    /**
     * スタッフ詳細画面 削除のテスト
     */
    public function testDestroy(): void
    {
        $admin1 = $this->createDefaultAdmin([
            'name' => '管理者1',
            'role' => 'manager'
        ]);
        $this->actingAs($admin1, 'admin');

        // manager権限ではアクセスできないことのテスト
        $response = $this->delete(route('admin.staff.destroy', $admin1));
        $response->assertForbidden();

        $admin2 = $this->createDefaultAdmin([
            'name' => '管理者2',
            'role' => 'high-manager'
        ]);
        $this->actingAs($admin2, 'admin');

        // 自分は削除できないことのテスト
        $response = $this->delete(route('admin.staff.destroy', $admin2));
        $response->assertSessionHasErrors('errors', '自分自身を削除することはできません');

        // 自分以外は削除出来ることのテスト
        $redirectResponse = $this->delete(route('admin.staff.destroy', $admin1));
        $response = $this->get($redirectResponse->headers->get('Location'));
        $response->assertSuccessful();

        // データが削除されたことをテスト
        $this->assertDatabaseMissing('admins', ['id' => $admin1->id]);
    }

}
