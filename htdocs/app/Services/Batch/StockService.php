<?php

namespace App\Services\Batch;

use App\Domain\Repositories\Stock\StockRepository;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class StockService extends BaseService
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
     * @return Collection
     */
    public function searchStock(): Collection
    {
        return $this->stockRepository->getAll();
    }

}
