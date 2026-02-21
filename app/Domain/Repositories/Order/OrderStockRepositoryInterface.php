<?php

namespace App\Domain\Repositories\Order;

use App\Domain\Entities\OrderStock;
use App\Domain\Repositories\BaseRepositoryInterface;
use Illuminate\Support\Collection;

interface OrderStockRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * orderId からデータを取得します。
     *
     * @return Collection<int, OrderStock>
     */
    public function getByOrderId(int $orderId): Collection;

    /**
     * ユーザーIDからデータを削除します。
     */
    public function deleteByUserId(int $userId): void;
}
