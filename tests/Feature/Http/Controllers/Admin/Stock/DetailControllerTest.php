<?php

namespace Tests\Feature\Http\Controllers\Admin\Stock;

use App\Enums\AdminRole;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class DetailControllerTest extends BaseTest
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_show(): void
    {
        $admin1 = $this->createDefaultAdmin([
            'name'  => '管理者A',
            'email' => 'admin1@test.com',
            'role'  => AdminRole::HighManager,
        ]);
        $this->actingAs($admin1, 'admin');

        $stock = $this->createDefaultStock([
            'name'     => 'aaa',
            'detail'   => 'aaaの説明',
            'price'    => 111,
            'quantity' => 1,
        ]);

        $response = $this->get(route('admin.stock.show', $stock));
        $response->assertSuccessful();
        $response->assertSee('aaa');
        $response->assertSee('111');
        $response->assertSee('1');
    }

    public function test_show_管理者ロール別アクセス権限検証(): void
    {
        $cases = [
            ['role' => AdminRole::HighManager, 'status' => 200],
            ['role' => AdminRole::Manager,     'status' => 200],
        ];

        $stock = $this->createDefaultStock();

        foreach ($cases as $case) {
            $admin = $this->createDefaultAdmin([
                'role' => $case['role']->value,
            ]);

            $this->actingAs($admin, 'admin')
                ->get(route('admin.stock.show', $stock))
                ->assertStatus($case['status']);
        }
    }

    public function test_destroy(): void
    {
        $admin1 = $this->createDefaultAdmin([
            'name' => '管理者1',
            'role' => AdminRole::Manager,
        ]);
        $this->actingAs($admin1, 'admin');

        $stock = $this->createDefaultStock([
            'name' => 'aaa',
        ]);

        // manager権限ではアクセスできないことのテスト
        $response = $this->delete(route('admin.stock.destroy', $stock));
        $response->assertForbidden();

        $admin2 = $this->createDefaultAdmin([
            'name' => '管理者2',
            'role' => AdminRole::HighManager,
        ]);
        $this->actingAs($admin2, 'admin');

        $redirectResponse = $this->delete(route('admin.stock.destroy', $stock));
        $response         = $this->get($redirectResponse->headers->get('Location'));
        $response->assertSuccessful();

        // データが削除されたことをテスト
        $this->assertDatabaseMissing('stocks', ['id' => $stock->id]);
    }
}
