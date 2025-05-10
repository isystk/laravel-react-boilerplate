<?php

namespace App\Services\Admin\Stock;

use App\Domain\Repositories\Stock\StockRepository;
use App\Services\BaseService;

class DestroyService extends BaseService
{
    private StockRepository $stockRepository;

    public function __construct(
        StockRepository $stockRepository
    ) {
        $this->stockRepository = $stockRepository;
    }

    /**
     * 商品を削除します。
     */
    public function delete(int $id): void
    {
        $this->stockRepository->delete($id);
    }
}
