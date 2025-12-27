<?php

namespace Tests\Unit\Services\Admin\Stock;

use App\Dto\Request\Admin\Stock\SearchConditionDto;
use App\Services\Admin\Stock\IndexService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\BaseTest;

class IndexServiceTest extends BaseTest
{
    use RefreshDatabase;

    private IndexService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(IndexService::class);
    }

    public function test_searchStock(): void
    {
        $request = new Request([
            'name' => null,
            'sort_name' => 'updated_at',
            'sort_direction' => 'asc',
            'limit' => 20,
        ]);
        $default = new SearchConditionDto($request);

        $stocks = $this->service->searchStock($default)->getCollection();
        $this->assertSame(0, $stocks->count(), '引数がない状態でエラーにならないことを始めにテスト');

        $stock1 = $this->createDefaultStock(['name' => 'stock1']);
        $stock2 = $this->createDefaultStock(['name' => 'stock2']);

        $input = clone $default;
        $input->name = 'stock2';
        $stocks = $this->service->searchStock($input)->getCollection();
        $stockIds = $stocks->pluck('id')->all();
        $this->assertSame([$stock2->id], $stockIds, 'nameで検索が出来ることをテスト');

        $input = clone $default;
        $input->sortName = 'id';
        $input->sortDirection = 'desc';
        $stocks = $this->service->searchStock($input)->getCollection();
        $stockIds = $stocks->pluck('id')->all();
        $this->assertSame([$stock2->id, $stock1->id], $stockIds, 'ソート指定で検索が出来ることをテスト');
    }
}
