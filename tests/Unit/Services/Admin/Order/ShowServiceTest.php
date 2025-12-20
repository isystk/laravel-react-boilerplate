<?php

namespace Tests\Unit\Services\Admin\Order;

use App\Services\Admin\Order\ShowService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowServiceTest extends TestCase
{
    use RefreshDatabase;

    private ShowService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(ShowService::class);
    }

    /**
     * getOrderStockのテスト
     */
    public function test_get_order_stock(): void
    {
        $orderImages = $this->service->getOrderStock(1);
        $this->assertSame(0, $orderImages->count(), 'データがない状態で正常に動作することを始めにテスト');

        $user1 = $this->createDefaultUser(['name' => 'user1']);

        $stock1 = $this->createDefaultStock(['name' => '商品1']);
        $stock2 = $this->createDefaultStock(['name' => '商品2']);

        $order1 = $this->createDefaultOrder(['user_id' => $user1->id, 'created_at' => '2024-05-01']);
        $orderStock1 = $this->createDefaultOrderStock(['order_id' => $order1->id, 'stock_id' => $stock1->id]);
        $orderStock2 = $this->createDefaultOrderStock(['order_id' => $order1->id, 'stock_id' => $stock2->id]);

        $orderStocks = $this->service->getOrderStock($order1->id);
        $orderStockIds = $orderStocks->pluck('id')->all();
        $this->assertSame([$orderStock1->id, $orderStock2->id], $orderStockIds, 'order_idで検索が出来ることをテスト');
    }
}
