<?php

namespace App\Services\Admin\Order;

use App\Domain\Repositories\Order\OrderStockRepository;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ShowService extends BaseService
{
    /**
     * @var OrderStockRepository
     */
    protected OrderStockRepository $orderStockRepository;

    public function __construct(
        Request $request,
        OrderStockRepository $orderStockRepository
    )
    {
        parent::__construct($request);
        $this->orderStockRepository = $orderStockRepository;
    }

    /**
     * @param int $orderId
     * @return Collection
     */
    public function getOrderStock(int $orderId): Collection
    {
        return $this->orderStockRepository->getByOrderId($orderId);
    }

}
