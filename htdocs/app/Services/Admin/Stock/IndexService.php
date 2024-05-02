<?php

namespace App\Services\Admin\Stock;

use App\Domain\Repositories\Stock\StockRepository;
use App\Services\BaseService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class IndexService extends BaseService
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
     * @return Collection|LengthAwarePaginator
     */
    public function searchStock(): Collection|LengthAwarePaginator
    {
        $limit = 20;
        return $this->stockRepository->getByConditions(
            [
                'name' => $this->request()->name,
                'limit' => $limit,
            ]);
    }
}
