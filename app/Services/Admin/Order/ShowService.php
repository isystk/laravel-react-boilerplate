<?php

namespace App\Services\Admin\Order;

use App\Domain\Entities\OrderStock;
use App\Domain\Repositories\Order\OrderStockRepositoryInterface;
use App\Services\BaseService;
use Illuminate\Support\Collection;

class ShowService extends BaseService
{
    public function __construct(private readonly OrderStockRepositoryInterface $orderStockRepository) {}

    /**
     * 注文情報を取得します。
     *
     * @return Collection<int, OrderStock>
     */
    public function getOrderStock(int $orderId): Collection
    {
        return $this->orderStockRepository->getByOrderId($orderId);
    }
}
