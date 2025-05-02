<?php

namespace Domain\Entities;

use App\Domain\Entities\Stock;
use Tests\TestCase;

class StockTest extends TestCase
{
    private Stock $sub;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sub = new Stock();
    }

    public function test_hasQuantity(): void
    {
        $this->assertFalse($this->sub->hasQuantity(), '在庫がない場合 → False');
        $this->sub->quantity = 1;
        $this->assertTrue($this->sub->hasQuantity(), '在庫がある場合 → True');
    }

}
