<?php

namespace Tests\Unit\Domain\Repositories\Order;

use App\Domain\Repositories\Order\OrderStockRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class OrderStockRepositoryTest extends BaseTest
{
    use RefreshDatabase;

    private OrderStockRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app(OrderStockRepositoryInterface::class);
    }

    public function test_getByOrderId(): void
    {
        $user1 = $this->createDefaultUser(['name' => 'user1', 'email' => 'user1@test.com']);

        $stock1 = $this->createDefaultStock(['name' => 'stock1']);
        $stock2 = $this->createDefaultStock(['name' => 'stock2']);

        $order = $this->createDefaultOrder(['user_id' => $user1->id, 'created_at' => '2024-04-01']);
        $this->createDefaultOrderStock([
            'order_id' => $order->id,
            'stock_id' => $stock1->id,
            'price'    => $stock1->price,
            'quantity' => 1,
        ]);
        $this->createDefaultOrderStock([
            'order_id' => $order->id,
            'stock_id' => $stock2->id,
            'price'    => $stock2->price,
            'quantity' => 1,
        ]);
        $orderStocks = $this->repository->getByOrderId($order->id);
        $this->assertSame(2, $orderStocks->count());
    }

    public function test_deleteByUserId(): void
    {
        $user1        = $this->createDefaultUser();
        $order1       = $this->createDefaultOrder(['user_id' => $user1->id]);
        $order1Stock1 = $this->createDefaultOrderStock(['order_id' => $order1->id]);
        $order1Stock2 = $this->createDefaultOrderStock(['order_id' => $order1->id]);
        $order2       = $this->createDefaultOrder(['user_id' => $user1->id]);
        $order2Stock1 = $this->createDefaultOrderStock(['order_id' => $order2->id]);
        $order2Stock2 = $this->createDefaultOrderStock(['order_id' => $order2->id]);

        $user2        = $this->createDefaultUser();
        $order3       = $this->createDefaultOrder(['user_id' => $user2->id]);
        $order3Stock1 = $this->createDefaultOrderStock(['order_id' => $order3->id]);

        $this->repository->deleteByUserId($user1->id);

        // user1の注文が削除され、user2の注文は削除されないことをテスト
        $this->assertDatabaseMissing('order_stocks', ['id' => $order1Stock1->id]);
        $this->assertDatabaseMissing('order_stocks', ['id' => $order1Stock2->id]);
        $this->assertDatabaseMissing('order_stocks', ['id' => $order2Stock1->id]);
        $this->assertDatabaseMissing('order_stocks', ['id' => $order2Stock2->id]);
        $this->assertDatabaseHas('order_stocks', ['id' => $order3Stock1->id]);
    }
}
