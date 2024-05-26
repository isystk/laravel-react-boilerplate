<?php

namespace App\Services\Admin\Stock;

use App\Domain\Entities\Stock;
use App\Domain\Repositories\Stock\StockRepository;
use App\Services\BaseService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class BaseStockService extends BaseService
{
    /**
     * @var StockRepository
     */
    protected StockRepository $stockRepository;

    public function __construct(
        Request $request,
        StockRepository $stockRepository
    )
    {
        parent::__construct($request);
        $this->stockRepository = $stockRepository;
    }

    /**
     * @param int $limit
     * @return Collection<int, Stock>|LengthAwarePaginator<Stock>
     */
    public function searchStock(int $limit = 20): Collection|LengthAwarePaginator
    {
        return $this->stockRepository->getByConditions(
            [
                'name' => $this->request()->name,
                'sort_name' => $this->request()->sort_name ?? 'updated_at',
                'sort_direction' => $this->request()->sort_direction ?? 'desc',
                'limit' => $limit,
            ]);
    }
}
