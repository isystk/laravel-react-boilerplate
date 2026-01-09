<?php

namespace Domain\Repositories\Order;

use App\Domain\Repositories\Order\OrderStockRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class OrderStockRepositoryTest extends BaseTest
{
    use RefreshDatabase;

    private OrderStockRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app(OrderStockRepository::class);
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
}
