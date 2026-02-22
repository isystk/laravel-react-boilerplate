<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\Stock;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class StockTest extends BaseTest
{
    use RefreshDatabase;

    private Stock $sub;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sub = new Stock;
    }

    public function test_正しくキャストされる事(): void
    {
        $model = $this->createDefaultUser([
            'deleted_at' => Carbon::now(),
        ]);

        $this->assertInstanceOf(Carbon::class, $model->created_at);
        $this->assertInstanceOf(Carbon::class, $model->updated_at);
        $this->assertInstanceOf(Carbon::class, $model->deleted_at);
    }

    public function test_hasQuantity(): void
    {
        $this->assertFalse($this->sub->hasQuantity(), '在庫がない場合 → False');
        $this->sub->quantity = 1;
        $this->assertTrue($this->sub->hasQuantity(), '在庫がある場合 → True');
    }
}
