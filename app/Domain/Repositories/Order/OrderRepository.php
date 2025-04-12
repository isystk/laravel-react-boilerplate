<?php

namespace App\Domain\Repositories\Order;

use App\Domain\Entities\Order;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use App\Domain\Repositories\BaseRepository;

interface OrderRepository extends BaseRepository
{
    /**
     * 検索条件からデータを取得します。
     * @param array{
     *   user_name : ?string,
     *   order_date_from : ?CarbonImmutable,
     *   order_date_to : ?CarbonImmutable,
     *   sort_name : ?string,
     *   sort_direction : 'asc' | 'desc' | null,
     *   limit : ?int,
     * } $conditions
     * @return Collection<int, Order>|LengthAwarePaginator<Order>
     */
    public function getConditionsWithUserStock(array $conditions): Collection|LengthAwarePaginator;

}
