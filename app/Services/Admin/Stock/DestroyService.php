<?php

namespace App\Services\Admin\Stock;

use App\Domain\Repositories\Stock\StockRepositoryInterface;
use App\Services\BaseService;

class DestroyService extends BaseService
{
    public function __construct(private readonly StockRepositoryInterface $stockRepository) {}

    /**
     * 商品を削除します。
     */
    public function delete(int $id): void
    {
        $this->stockRepository->delete($id);
    }
}
