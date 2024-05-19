<?php

namespace App\Services\Api\Shop;

use App\Domain\Entities\Stock;
use App\Domain\Repositories\Stock\StockRepository;
use App\Services\BaseService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class IndexService extends BaseService
{
    /**
     * @var StockRepository
     */
    protected StockRepository $stockRepository;

    public function __construct(
        Request $request,
        StockRepository $stockRepository,
    )
    {
        parent::__construct($request);
        $this->stockRepository = $stockRepository;
    }

    /**
     * @return LengthAwarePaginator<Stock>
     */
    public function searchStock(): LengthAwarePaginator
    {
        $limit = 6;
        return $this->stockRepository->getByLimit($limit);
    }

}
