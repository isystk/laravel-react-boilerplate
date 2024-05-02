<?php

namespace App\Domain\Repositories\Order;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use App\Domain\Repositories\BaseRepository;

interface OrderRepository extends BaseRepository
{
    /**
     * 検索条件からデータを取得します。
     * @param array{
     *   user_name : ?string,
     *   limit : ?int,
     * } $conditions
     * @return Collection|LengthAwarePaginator
     */
    public function getConditionsWithUserStock(array $conditions): Collection|LengthAwarePaginator;

}
