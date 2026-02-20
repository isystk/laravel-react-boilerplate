<?php

namespace App\Domain\Repositories\Order;

use App\Domain\Entities\OrderStock;
use App\Domain\Repositories\BaseEloquentRepository;
use Illuminate\Support\Collection;

class OrderStockEloquentRepository extends BaseEloquentRepository implements OrderStockRepository
{
    protected function model(): string
    {
        return OrderStock::class;
    }

    /**
     * orderId からデータを取得します。
     *
     * @return Collection<int, OrderStock>
     */
    public function getByOrderId(int $orderId): Collection
    {
        /** @var Collection<int, OrderStock> */
        return $this->model
            ->where('order_id', $orderId)
            ->get();
    }

    /**
     * ユーザーIDからデータを削除します。
     */
    public function deleteByUserId(int $userId): void
    {
        $this->model
            ->whereIn('order_id', function ($query) use ($userId) {
                $query->select('id')
                    ->from('orders')
                    ->where('user_id', $userId);
            })
            ->delete();
    }
}
