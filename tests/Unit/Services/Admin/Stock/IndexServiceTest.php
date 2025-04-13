<?php

namespace Tests\Unit\Services\Admin\Stock;

use App\Domain\Entities\Stock;
use App\Services\Admin\Stock\IndexService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
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
     * インスタンスがテスト対象のクラスであることのテスト
     */
    public function testInstanceOf(): void
    {
        $this->assertInstanceOf(IndexService::class, $this->service);
    }

    /**
     * searchStockのテスト
     */
    public function testSearchStock(): void
    {
        $default = [
            'name' => null,
            'sort_name' => 'updated_at',
            'sort_direction' => 'asc',
            'limit' => 20,
        ];

        $stocks = $this->service->searchStock($default);
        $this->assertSame(0, $stocks->count(), '引数がない状態でエラーにならないことを始めにテスト');

        /** @var Stock $stock1 */
        $stock1 = Stock::factory(['name' => 'stock1'])->create();
        /** @var Stock $stock2 */
        $stock2 = Stock::factory(['name' => 'stock2'])->create();

        $input = $default;
        $input['name'] = 'stock2';
        /** @var LengthAwarePaginator<int, Stock> $orders */
        $stocks = $this->service->searchStock($input);
        $stockIds = $stocks->pluck('id')->all();
        $this->assertSame([$stock2->id], $stockIds, 'nameで検索が出来ることをテスト');

        $input = $default;
        $input['sort_name'] = 'id';
        $input['sort_direction'] = 'desc';
        /** @var LengthAwarePaginator<int, Stock> $orders */
        $stocks = $this->service->searchStock($input);
        $stockIds = $stocks->pluck('id')->all();
        $this->assertSame([$stock2->id, $stock1->id], $stockIds, 'ソート指定で検索が出来ることをテスト');

    }
}
