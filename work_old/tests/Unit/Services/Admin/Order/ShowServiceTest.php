<?php

namespace Tests\Unit\Services\Admin\Order;

use App\Domain\Entities\Order;
use App\Domain\Entities\OrderStock;
use App\Domain\Entities\User;
use App\Domain\Entities\Stock;
use App\Services\Admin\Order\ShowService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use tests\TestCase;

class ShowServiceTest extends TestCase
{

    use RefreshDatabase;

    private ShowService $service;

    /**
     * 各テストの実行前に起動する。
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(ShowService::class);
    }

    /**
     * インスタンスがテスト対象のクラスであることのテスト
     */
    public function testInstanceOf(): void
    {
        $this->assertInstanceOf(ShowService::class, $this->service);
    }

    /**
     * getOrderStockのテスト
     */
    public function testGetOrderStock(): void
    {
        $orderImages = $this->service->getOrderStock(1);
        $this->assertSame(0, $orderImages->count(), 'データがない状態で正常に動作することを始めにテスト');

        /** @var User $user1 */
        $user1 = User::factory(['name' => 'user1'])->create();

        /** @var Stock $stock1 */
        $stock1 = Stock::factory(['name' => '商品1'])->create();
        /** @var Stock $stock2 */
        $stock2 = Stock::factory(['name' => '商品2'])->create();

        /** @var Order $order1 */
        $order1 = Order::factory(['user_id' => $user1->id, 'created_at' => '2024-05-01'])->create();
        /** @var OrderStock $orderStock1 */
        $orderStock1 = OrderStock::factory(['order_id' => $order1->id, 'stock_id' => $stock1->id])->create();
        /** @var OrderStock $orderStock2 */
        $orderStock2 = OrderStock::factory(['order_id' => $order1->id, 'stock_id' => $stock2->id])->create();

        $orderStocks = $this->service->getOrderStock($order1->id);
        $orderStockIds = $orderStocks->pluck('id')->all();
        $this->assertSame([$orderStock1->id, $orderStock2->id], $orderStockIds, 'order_idで検索が出来ることをテスト');
    }
}
