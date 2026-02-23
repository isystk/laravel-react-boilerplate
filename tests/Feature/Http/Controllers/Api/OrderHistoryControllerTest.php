<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Domain\Entities\User;
use App\Services\Api\OrderHistory\IndexService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class OrderHistoryControllerTest extends BaseTest
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->createDefaultUser();
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
        $stock = $this->createDefaultStock();
        $order = $this->createDefaultOrder(['user_id' => $this->user->id]);
        $this->createDefaultOrderStock([
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

    public function test_index_error(): void
    {
        $this->mock(IndexService::class, function ($mock) {
            $mock->shouldReceive('getOrderHistory')->andThrow(new Exception('History error'));
        });

        $response = $this->actingAs($this->user)->getJson(route('api.order-history'));

        $response->assertStatus(500);
        $response->assertJson([
            'result' => false,
            'error'  => ['messages' => ['History error']],
        ]);
    }
}
