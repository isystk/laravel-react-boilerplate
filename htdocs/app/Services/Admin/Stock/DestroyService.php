<?php

namespace App\Services\Admin\Stock;

use App\Domain\Repositories\Stock\StockRepository;
use App\Services\BaseService;
use Illuminate\Http\Request;

class DestroyService extends BaseService
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
     * @param int $id
     */
    public function delete(int $id): void
    {
        $this->stockRepository->delete($id);
    }
}
