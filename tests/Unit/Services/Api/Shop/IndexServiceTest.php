<?php

namespace Tests\Unit\Services\Api\Shop;

use App\Domain\Entities\Stock;
use App\Services\Api\Shop\IndexService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexServiceTest extends TestCase
{

    use RefreshDatabase;

    private IndexService $service;

    /**
     * 各テストの実行前に起動する。
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(IndexService::class);
    }

    /**
     * searchStockのテスト
     */
    public function testSearchStock(): void
    {
        $stocks = $this->service->searchStock();
        $this->assertCount(0, $stocks->items(), '引数がない状態でエラーにならないことを始めにテスト');

        /** @var Stock $stock1 */
        $stock1 = Stock::factory(['name' => 'stock1'])->create();
        /** @var Stock $stock2 */
        $stock2 = Stock::factory(['name' => 'stock2'])->create();
        /** @var Stock $stock3 */
        $stock3 = Stock::factory(['name' => 'stock3'])->create();
        /** @var Stock $stock4 */
        $stock4 = Stock::factory(['name' => 'stock4'])->create();
        /** @var Stock $stock5 */
        $stock5 = Stock::factory(['name' => 'stock5'])->create();
        /** @var Stock $stock6 */
        $stock6 = Stock::factory(['name' => 'stock6'])->create();
        /** @var Stock $stock7 */
        $stock7 = Stock::factory(['name' => 'stock7'])->create();

        $stocks = $this->service->searchStock();
        $expectedStockId = [
            $stock7->id,
            $stock6->id,
            $stock5->id,
            $stock4->id,
            $stock3->id,
            $stock2->id,
        ];
        /** @var Stock[] $items */
        $items = $stocks->items();
        $stockIds = collect($items)->pluck('id')->all();
        $this->assertCount(6, $items, '1ページ最大6レコードが取得出来ることをテスト');
        $this->assertSame($expectedStockId, $stockIds, 'ソート指定で検索が出来ることをテスト');

    }
}
