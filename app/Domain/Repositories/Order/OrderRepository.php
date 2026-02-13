<?php

namespace App\Domain\Repositories\Order;

use App\Domain\Entities\Order;
use App\Domain\Repositories\BaseRepository;
use Carbon\CarbonImmutable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface OrderRepository extends BaseRepository
{
    /**
     * 検索条件からデータを取得します。
     *
     * @param array{
     *   user_name : ?string,
     *   order_date_from : ?CarbonImmutable,
     *   order_date_to : ?CarbonImmutable,
     *   sort_name : ?string,
     *   sort_direction : 'asc' | 'desc' | null,
     *   limit : ?int,
     * } $conditions
     * @return Collection<int, Order>|LengthAwarePaginator<int, Order>
     */
    public function getConditionsWithUserStock(array $conditions): Collection|LengthAwarePaginator;

    /**
     * 本日の注文件数を返却する。
     */
    public function countTodaysOrders(): int;

    /**
     * 最新の注文をユーザー情報付きで取得する。
     *
     * @return Collection<int, Order>
     */
    public function getLatestWithUser(int $limit): Collection;
}
