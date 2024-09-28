<?php

namespace App\Services\Admin\Order;

use App\Domain\Entities\OrderStock;
use App\Domain\Repositories\Order\OrderStockRepository;
use App\Services\BaseService;
use Illuminate\Support\Collection;

class ShowService extends BaseService
{
    private OrderStockRepository $orderStockRepository;

    /**
     * Create a new controller instance.
     *
     * @param OrderStockRepository $orderStockRepository
     */
    public function __construct(
        OrderStockRepository $orderStockRepository
    )
    {
        $this->orderStockRepository = $orderStockRepository;
    }

    /**
     * 注文情報を取得します。
     * @param int $orderId
     * @return Collection<int, OrderStock>
     */
    public function getOrderStock(int $orderId): Collection
    {
        return $this->orderStockRepository->getByOrderId($orderId);
    }

}
