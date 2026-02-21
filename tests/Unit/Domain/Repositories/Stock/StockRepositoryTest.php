<?php

namespace Tests\Unit\Domain\Repositories\Stock;

use App\Domain\Entities\Stock;
use App\Domain\Repositories\Stock\StockRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class StockRepositoryTest extends BaseTest
{
    use RefreshDatabase;

    private StockRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app(StockRepositoryInterface::class);
    }

    public function test_getByLimit(): void
    {
        $stocks = $this->repository->getByLimit()->items();
        $this->assertCount(0, $stocks, 'データがない状態で正常に動作することを始めにテスト');

        $this->createDefaultStock(['name' => 'stock1']);
        $this->createDefaultStock(['name' => 'stock2']);
        $expectStock3 = $this->createDefaultStock(['name' => 'stock3']);

        /** @var array<Stock> $stocks */
        $stocks = $this->repository->getByLimit(2)->items();
        $this->assertCount(2, $stocks, '指定した件数のデータが取得されることをテスト');
        $this->assertSame($expectStock3->id, $stocks[0]->id, '最新順に取得されることをテスト');
    }

    public function test_getByConditions(): void
    {
        $defaultConditions = [
            'name'           => null,
            'sort_name'      => null,
            'sort_direction' => null,
            'limit'          => null,
        ];

        $stocks = $this->repository->getByConditions($defaultConditions);
        $this->assertSame(0, $stocks->count(), 'データがない状態で正常に動作することを始めにテスト');

        $expectStock1 = $this->createDefaultStock(['name' => 'stock1']);
        $this->createDefaultStock(['name' => 'stock2']);

        /** @var Stock $stock */
        $stock = $this->repository->getByConditions([
            ...$defaultConditions,
            'name' => 'stock1',
        ])->first();
        $this->assertSame($expectStock1->id, $stock->id, 'nameで検索が出来ることをテスト');

        $stocks = $this->repository->getByConditions([
            ...$defaultConditions,
            'limit' => 1,
        ]);
        $this->assertSame(1, $stocks->count(), 'limitで取得件数が指定出来ることをテスト');
    }
}
