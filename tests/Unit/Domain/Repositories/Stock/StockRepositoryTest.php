<?php

namespace Domain\Repositories\Stock;

use App\Domain\Entities\Stock;
use App\Domain\Repositories\Stock\StockRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockRepositoryTest extends TestCase
{
    /**
     * 各テストの実行後にテーブルを空にする。
     */
    use RefreshDatabase;

    private StockRepository $repository;

    /**
     * 各テストの実行前に起動する。
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app(StockRepository::class);
    }

    /**
     * インスタンスがテスト対象のクラスであることのテスト
     */
    public function testInstanceOf(): void
    {
        $this->assertInstanceOf(StockRepository::class, $this->repository);
    }

    /**
     * getByLimitのテスト
     */
    public function testGetByLimit(): void
    {
        $stocks = $this->repository->getByLimit()->items();
        $this->assertCount(0, $stocks, 'データがない状態で正常に動作することを始めにテスト');

        /** @var Stock $expectStock1 */
        $expectStock1 = Stock::factory(['name' => 'stock1'])->create();
        /** @var Stock $expectStock2 */
        $expectStock2 = Stock::factory(['name' => 'stock2'])->create();
        /** @var Stock $expectStock3 */
        $expectStock3 = Stock::factory(['name' => 'stock3'])->create();

        /** @var array<Stock> $stocks */
        $stocks = $this->repository->getByLimit(2)->items();
        $this->assertCount(2, $stocks, '指定した件数のデータが取得されることをテスト');
        $this->assertSame($expectStock3->id, $stocks[0]->id, '最新順に取得されることをテスト');
    }

    /**
     * getByConditionsのテスト
     */
    public function testGetByConditions(): void
    {
        $defaultConditions = [
            'name' => null,
            'sort_name' => null,
            'sort_direction' => null,
            'limit' => null,
        ];

        $stocks = $this->repository->getByConditions($defaultConditions);
        $this->assertSame(0, $stocks->count(), 'データがない状態で正常に動作することを始めにテスト');

        /** @var Stock $expectStock1 */
        $expectStock1 = Stock::factory(['name' => 'stock1'])->create();
        /** @var Stock $expectStock2 */
        $expectStock2 = Stock::factory(['name' => 'stock2'])->create();

        /** @var Stock $stock */
        $stock = $this->repository->getByConditions([
            ...$defaultConditions,
            'name' => 'stock1'
        ])->first();
        $this->assertSame($expectStock1->id, $stock->id, 'nameで検索が出来ることをテスト');

        $stocks = $this->repository->getByConditions([
            ...$defaultConditions,
            'limit' => 1
        ]);
        $this->assertSame(1, $stocks->count(), 'limitで取得件数が指定出来ることをテスト');
    }
}
