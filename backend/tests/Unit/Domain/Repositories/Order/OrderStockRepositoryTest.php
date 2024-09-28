<?php

namespace Unit\Domain\Repositories\Order;

use App\Domain\Entities\Order;
use App\Domain\Entities\OrderStock;
use App\Domain\Entities\Stock;
use App\Domain\Entities\User;
use App\Domain\Repositories\Order\OrderStockRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderStockRepositoryTest extends TestCase
{

    use RefreshDatabase;

    private OrderStockRepository $repository;

    /**
     * 各テストの実行前に起動する。
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app(OrderStockRepository::class);
    }

    /**
     * インスタンスがテスト対象のクラスであることのテスト
     */
    public function testInstanceOf(): void
    {
        $this->assertInstanceOf(OrderStockRepository::class, $this->repository);
    }

    /**
     * getByOrderIdのテスト
     */
    public function testGetByOrderId(): void
    {
        /** @var User $user1 */
        $user1 = User::factory(['name' => 'user1', 'email' => 'user1@test.com'])->create();

        /** @var Stock $stock1 */
        $stock1 = Stock::factory(['name' => 'stock1'])->create();
        /** @var Stock $stock2 */
        $stock2 = Stock::factory(['name' => 'stock2'])->create();

        /** @var Order $order */
        $order = Order::factory(['user_id' => $user1->id, 'created_at' => '2024-04-01'])->create();
        OrderStock::factory(['order_id' => $order->id, 'stock_id' => $stock1->id, 'price' => $stock1->price, 'quantity' => 1])->create();
        OrderStock::factory(['order_id' => $order->id, 'stock_id' => $stock2->id, 'price' => $stock2->price, 'quantity' => 1])->create();
        $orderStocks = $this->repository->getByOrderId($order->id);
        $this->assertSame(2, $orderStocks->count());
    }
}
