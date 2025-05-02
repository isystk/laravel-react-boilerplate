<?php

namespace Http\Controllers\Admin\Stock;

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
     * 商品詳細画面表示のテスト
     */
    public function testShow(): void
    {
        $admin1 = $this->createDefaultAdmin([
            'name' => '管理者A',
            'email' => 'admin1@test.com',
            'role' => 'high-manager',
        ]);
        $this->actingAs($admin1, 'admin');

        $stock = $this->createDefaultStock([
            'name' => 'aaa',
            'detail' => 'aaaの説明',
            'price' => 111,
            'quantity' => 1,
            'imgpath' => 'stock1.jpg',
        ]);

        $response = $this->get(route('admin.stock.show', $stock));
        $response->assertSuccessful();
        $response->assertSee('aaa');
        $response->assertSee('111');
        $response->assertSee('1');
        $response->assertSee('stock1.jpg');
    }

    /**
     * 商品詳細画面 削除のテスト
     */
    public function testDestroy(): void
    {
        $admin1 = $this->createDefaultAdmin([
            'name' => '管理者1',
            'role' => 'manager',
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
            'role' => 'high-manager',
        ]);
        $this->actingAs($admin2, 'admin');

        $redirectResponse = $this->delete(route('admin.stock.destroy', $stock));
        $response = $this->get($redirectResponse->headers->get('Location'));
        $response->assertSuccessful();

        // データが削除されたことをテスト
        $this->assertDatabaseMissing('stocks', ['id' => $stock->id]);
    }

}
