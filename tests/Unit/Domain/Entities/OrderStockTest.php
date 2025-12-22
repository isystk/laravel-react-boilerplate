<?php

namespace Domain\Entities;

use App\Domain\Entities\Order;
use App\Domain\Entities\Stock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class OrderStockTest extends BaseTest
{
    use RefreshDatabase;

    public function test_order(): void
    {
        $orderStock = $this->createDefaultOrderStock();

        $result = $orderStock->order;
        $this->assertInstanceOf(Order::class, $result);
        $this->assertSame($orderStock->order->id, $result->id);
    }

    public function test_stock(): void
    {
        $orderStock = $this->createDefaultOrderStock();

        $result = $orderStock->stock;
        $this->assertInstanceOf(Stock::class, $result);
        $this->assertSame($orderStock->stock->id, $result->id);
    }
}
