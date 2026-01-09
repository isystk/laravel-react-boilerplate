<?php

namespace App\Services\Admin\Stock;

use App\Domain\Entities\Stock;
use App\Domain\Repositories\Stock\StockRepository;
use App\Dto\Request\Admin\Stock\SearchConditionDto;
use Illuminate\Pagination\LengthAwarePaginator;

class IndexService extends BaseStockService
{
    public function __construct(private readonly StockRepository $stockRepository) {}

    /**
     * 商品を検索します。
     *
     * @return LengthAwarePaginator<int, Stock>
     */
    public function searchStock(SearchConditionDto $searchConditionDto): LengthAwarePaginator
    {
        $items = [
            'name'           => $searchConditionDto->name,
            'sort_name'      => $searchConditionDto->sortName,
            'sort_direction' => $searchConditionDto->sortDirection,
            'limit'          => $searchConditionDto->limit,
        ];

        return $this->stockRepository->getByConditions($items);
    }
}
