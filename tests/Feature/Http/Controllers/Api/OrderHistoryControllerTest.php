<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Domain\Entities\Order;
use App\Domain\Entities\OrderStock;
use App\Domain\Entities\Stock;
use App\Domain\Entities\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class OrderHistoryControllerTest extends BaseTest
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_index_unauthenticated(): void
    {
        $response = $this->getJson(route('api.order-history'));
        // 実際には web ミドルウェアの影響で 302 になる可能性があるが、JSONリクエストなら 401 が期待される
        $response->assertStatus(401);
    }

    public function test_index_authenticated(): void
    {
        // テストデータ作成
        $stock = Stock::factory()->create();
        $order = Order::factory()->create(['user_id' => $this->user->id]);
        OrderStock::factory()->create([
            'order_id' => $order->id,
            'stock_id' => $stock->id,
            'price'    => 1000,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($this->user)->getJson(route('api.order-history'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'result',
                'orders' => [
                    '*' => [
                        'id',
                        'sumPrice',
                        'createdAt',
                        'items' => [
                            '*' => [
                                'stock' => ['id', 'name', 'imageUrl'],
                                'price',
                                'quantity',
                            ],
                        ],
                    ],
                ],
            ]);

        $this->assertCount(1, $response->json('orders'));
        $this->assertEquals($order->id, $response->json('orders.0.id'));
    }
}
