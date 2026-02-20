<?php

namespace App\Services\Admin\Stock;

use App\Domain\Entities\Stock;
use App\Domain\Repositories\Stock\StockRepositoryInterface;
use App\Dto\Request\Admin\Stock\SearchConditionDto;
use App\FileIO\Exports\StockExport;
use Illuminate\Support\Collection;

class ExportService extends BaseStockService
{
    public function __construct(private readonly StockRepositoryInterface $stockRepository) {}

    /**
     * エクスポート用のオブジェクトを取得します。
     */
    public function getExport(SearchConditionDto $searchConditionDto): StockExport
    {
        $items = [
            'name'           => $searchConditionDto->name,
            'sort_name'      => $searchConditionDto->sortName,
            'sort_direction' => $searchConditionDto->sortDirection,
        ];
        /** @var Collection<int, Stock> $stocks */
        $stocks = $this->stockRepository->getByConditions($items);

        return new StockExport($stocks);
    }
}
