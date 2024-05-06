<?php

namespace App\Domain\Repositories\Order;

use App\Domain\Repositories\BaseRepository;
use Illuminate\Support\Collection;

interface OrderStockRepository extends BaseRepository
{
    /**
     * orderId からデータを取得します。
     * @param int $orderId
     * @return Collection
     */
    public function getByOrderId(int $orderId): Collection;

}
