<?php

namespace Console\Commands;

use App\Console\Commands\StockCSVBatch;
use App\Domain\Entities\Stock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockCSVBatchTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 各テストの実行前に起動するメソッド
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * バッチが正常に実行されることのテスト
     */
    public function testSuccess(): void
    {
        /** @var Stock $expectStock1 */
        $expectStock1 = Stock::factory(['name' => 'stock1'])->create();
        /** @var Stock $expectStock2 */
        $expectStock2 = Stock::factory(['name' => 'stock2'])->create();
        /** @var Stock $expectStock3 */
        $expectStock3 = Stock::factory(['name' => 'stock3'])->create();

        // バッチ処理の実行
        $this->runBatch('stockcsv', []);
    }

}
