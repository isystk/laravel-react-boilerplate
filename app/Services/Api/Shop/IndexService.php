<?php

namespace App\Services\Api\Shop;

use App\Domain\Entities\Stock;
use App\Domain\Repositories\Stock\StockRepository;
use App\Services\BaseService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class IndexService extends BaseService
{
    private StockRepository $stockRepository;

    public function __construct(
        StockRepository $stockRepository
    ) {
        $this->stockRepository = $stockRepository;
    }

    /**
     * 商品を検索します。
     * @return LengthAwarePaginator<int, Stock>
     */
    public function searchStock(): LengthAwarePaginator
    {
        return $this->stockRepository->getByLimit(6);
    }

}
