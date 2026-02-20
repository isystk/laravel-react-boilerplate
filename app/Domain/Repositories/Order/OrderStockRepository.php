<?php

namespace App\Domain\Repositories\Order;

use App\Domain\Entities\OrderStock;
use App\Domain\Repositories\BaseRepository;
use Illuminate\Support\Collection;

interface OrderStockRepository extends BaseRepository
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
