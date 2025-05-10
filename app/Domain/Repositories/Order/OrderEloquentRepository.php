<?php

namespace App\Domain\Repositories\Order;

use App\Domain\Entities\Order;
use App\Domain\Repositories\BaseEloquentRepository;
use Carbon\CarbonImmutable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class OrderEloquentRepository extends BaseEloquentRepository implements OrderRepository
{

    /**
     * @return string
     */
    protected function model(): string
    {
        return Order::class;
    }

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
     * @return Collection<int, Order>|LengthAwarePaginator<int, Order>
     */
    public function getConditionsWithUserStock(array $conditions): Collection|LengthAwarePaginator
    {
        $query = $this->model
            ->select('orders.*')
            ->with([
                'user',
            ])
            ->join('users', 'users.id', 'orders.user_id');

        if (!is_null($conditions['user_name'] ?? null)) {
            $query->where('users.name', 'like', '%' . $conditions['user_name'] . '%');
        }
        if (!is_null($conditions['order_date_from'] ?? null)) {
            $query->where('orders.created_at', '>=', $conditions['order_date_from']->format('Y-m-d'));
        }
        if (!is_null($conditions['order_date_to'] ?? null)) {
            $query->where('orders.created_at', '<=', $conditions['order_date_to']->format('Y-m-d'));
        }

        if (!is_null($conditions['sort_name'] ?? null)) {
            $query->orderBy($conditions['sort_name'], $conditions['sort_direction'] ?? 'asc');
        }
        $query->orderBy('id', 'asc');

        if (!is_null($conditions['limit'] ?? null)) {
            /** @var LengthAwarePaginator<int, Order> */
            return $query->paginate($conditions['limit']);
        }
        /** @var Collection<int, Order> */
        return $query->get();
    }

}
