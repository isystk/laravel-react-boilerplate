<?php

namespace Http\Controllers\Admin\Order;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DetailControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    /**
     * 注文詳細画面表示のテスト
     */
    public function test_show(): void
    {
        $admin = $this->createDefaultAdmin([
            'name' => '管理者A',
            'role' => 'manager',
        ]);
        $this->actingAs($admin, 'admin');

        $user1 = $this->createDefaultUser(['name' => 'user1']);

        $stock1 = $this->createDefaultStock(['name' => '商品1']);
        $stock2 = $this->createDefaultStock(['name' => '商品2']);

        $order1 = $this->createDefaultOrder(['user_id' => $user1->id, 'created_at' => '2024-05-01']);
        $this->createDefaultOrderStock(['order_id' => $order1->id, 'stock_id' => $stock1->id]);
        $this->createDefaultOrderStock(['order_id' => $order1->id, 'stock_id' => $stock2->id]);

        $response = $this->get(route('admin.order.show', $order1));
        $response->assertSuccessful();
        $response->assertSee('user1');
        $response->assertSee('商品1');
        $response->assertSee('商品2');
    }
}
