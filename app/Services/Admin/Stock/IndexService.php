<?php

namespace App\Services\Admin\Stock;

use App\Domain\Entities\Stock;
use App\Domain\Repositories\Stock\StockRepository;
use App\Dto\Request\Admin\Stock\SearchConditionDto;
use Illuminate\Pagination\LengthAwarePaginator;

class IndexService extends BaseStockService
{
    private StockRepository $stockRepository;

    public function __construct(
        StockRepository $stockRepository
    ) {
        $this->stockRepository = $stockRepository;
    }

    /**
     * 商品を検索します。
     * @param SearchConditionDto $searchConditionDto
     * @return LengthAwarePaginator<int, Stock>
     */
    public function searchStock(SearchConditionDto $searchConditionDto): LengthAwarePaginator
    {
        $items = [
            'name' => $searchConditionDto->name,
            'sort_name' => $searchConditionDto->sortName,
            'sort_direction' => $searchConditionDto->sortDirection,
            'limit' => $searchConditionDto->limit,
        ];
        return $this->stockRepository->getByConditions($items);
    }

}
