<?php

namespace App\Domain\Repositories\Order;

use App\Domain\Entities\Order;
use App\Domain\Repositories\BaseRepositoryInterface;
use Carbon\CarbonImmutable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface OrderRepositoryInterface extends BaseRepositoryInterface
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

    /**
     * ユーザーIDから注文データを取得します。
     *
     * @return Collection<int, Order>
     */
    public function getByUserId(int $userId): Collection;

    /**
     * ユーザーIDからデータを削除します。
     */
    public function deleteByUserId(int $userId): void;
}
