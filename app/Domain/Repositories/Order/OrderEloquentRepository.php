<?php

namespace App\Domain\Repositories\Order;

use App\Domain\Entities\Order;
use App\Domain\Repositories\BaseEloquentRepository;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class OrderEloquentRepository extends BaseEloquentRepository implements OrderRepository
{
    protected function model(): string
    {
        return Order::class;
    }

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

        $sortColumn = $this->validateSortColumn(
            $conditions['sort_name'] ?? '',
            ['id', 'orders.created_at', 'orders.updated_at', 'users.name'],
        );
        if ($sortColumn !== null) {
            $query->orderBy($sortColumn, $conditions['sort_direction'] ?? 'asc');
        }
        $query->orderBy('id', 'asc');

        if (!is_null($conditions['limit'] ?? null)) {
            /** @var LengthAwarePaginator<int, Order> */
            return $query->paginate($conditions['limit']);
        }

        /** @var Collection<int, Order> */
        return $query->get();
    }

    public function countTodaysOrders(): int
    {
        return $this->model
            ->whereDate('created_at', Carbon::today()->toDateString())
            ->count();
    }

    /**
     * @return Collection<int, Order>
     */
    public function getLatestWithUser(int $limit): Collection
    {
        /** @var Collection<int, Order> */
        return $this->model
            ->with('user')
            ->latest('created_at')
            ->take($limit)
            ->get();
    }
}
