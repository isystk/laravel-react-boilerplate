<?php

namespace App\Domain\Repositories\Order;

use App\Domain\Entities\Order;
use App\Domain\Repositories\BaseEloquentRepository;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

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
     *   limit : ?int,
     * } $conditions
     * @return Collection|LengthAwarePaginator
     */
    public function getConditionsWithUserStock(array $conditions): Collection|LengthAwarePaginator
    {
        $query = $this->model
            ->select('orders.*')
            ->with([
                'user',
                'stock',
            ])
            ->join('users', 'users.id', 'orders.user_id')
            ->orderBy('orders.created_at', 'desc');

        if (null !== $conditions['user_name']) {
            $query->where('users.name', 'like', '%' . $conditions['user_name'] . '%');
        }
        if (null !== $conditions['order_date_from']) {
            $query->where('orders.created_at', '>=', $conditions['order_date_from']);
        }
        if (null !== $conditions['order_date_to']) {
            $query->where('orders.created_at', '<=', $conditions['order_date_to']);
        }

        return null !== $conditions['limit'] ? $query->paginate($conditions['limit']) : $query->get();

    }

}
