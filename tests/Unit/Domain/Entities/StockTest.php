<?php

namespace Domain\Entities;

use App\Domain\Entities\Stock;
use Tests\BaseTest;

class StockTest extends BaseTest
{
    private Stock $sub;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sub = new Stock;
    }

    public function test_has_quantity(): void
    {
        $this->assertFalse($this->sub->hasQuantity(), '在庫がない場合 → False');
        $this->sub->quantity = 1;
        $this->assertTrue($this->sub->hasQuantity(), '在庫がある場合 → True');
    }
}
