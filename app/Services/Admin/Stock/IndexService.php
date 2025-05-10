<?php

namespace App\Services\Admin\Stock;

use App\Domain\Entities\Stock;
use App\Domain\Repositories\Stock\StockRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

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
     * @param array{
     *   name : ?string,
     *   sort_name : string,
     *   sort_direction : 'asc' | 'desc',
     *   limit : int,
     * } $conditions
     * @return Collection<int, Stock>|LengthAwarePaginator<int, Stock>
     */
    public function searchStock(array $conditions): Collection|LengthAwarePaginator
    {
        return $this->stockRepository->getByConditions($conditions);
    }

}
