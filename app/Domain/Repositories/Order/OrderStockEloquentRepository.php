<?php

namespace App\Domain\Repositories\Order;

use App\Domain\Entities\OrderStock;
use App\Domain\Repositories\BaseEloquentRepository;
use Illuminate\Support\Collection;

class OrderStockEloquentRepository extends BaseEloquentRepository implements OrderStockRepository
{

    /**
     * @return string
     */
    protected function model(): string
    {
        return OrderStock::class;
    }

    /**
     * orderId からデータを取得します。
     * @param int $orderId
     * @return Collection<int, OrderStock>
     */
    public function getByOrderId(int $orderId): Collection
    {
        /** @var Collection<int, OrderStock> */
        return $this->model
            ->where('order_id', $orderId,)
            ->get();
    }

}
