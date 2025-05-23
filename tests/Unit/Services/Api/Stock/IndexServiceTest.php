<?php

namespace Tests\Unit\Services\Api\Stock;

use App\Services\Api\Stock\IndexService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexServiceTest extends TestCase
{
    use RefreshDatabase;

    private IndexService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(IndexService::class);
    }

    /**
     * searchStockのテスト
     */
    public function test_search_stock(): void
    {
        $result = $this->service->searchStock();
        $this->assertCount(0, $result->stocks, '引数がない状態でエラーにならないことを始めにテスト');

        $this->createDefaultStock(['name' => 'stock1']);
        $stock2 = $this->createDefaultStock(['name' => 'stock2']);
        $stock3 = $this->createDefaultStock(['name' => 'stock3']);
        $stock4 = $this->createDefaultStock(['name' => 'stock4']);
        $stock5 = $this->createDefaultStock(['name' => 'stock5']);
        $stock6 = $this->createDefaultStock(['name' => 'stock6']);
        $stock7 = $this->createDefaultStock(['name' => 'stock7']);

        $result = $this->service->searchStock();
        $expectedStockId = [
            $stock7->id,
            $stock6->id,
            $stock5->id,
            $stock4->id,
            $stock3->id,
            $stock2->id,
        ];
        $stockIds = collect($result->stocks)->pluck('id')->all();
        $this->assertCount(6, $result->stocks, '1ページ最大6レコードが取得出来ることをテスト');
        $this->assertSame($expectedStockId, $stockIds, 'ソート指定で検索が出来ることをテスト');
    }
}
