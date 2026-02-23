<?php

namespace App\Domain\Repositories\Order;

use App\Domain\Entities\Order;
use App\Domain\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    protected function model(): string
    {
        return Order::class;
    }

    /**
     * {@inheritDoc}
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

    /**
     * {@inheritDoc}
     */
    public function countTodaysOrders(): int
    {
        return $this->model
            ->whereDate('created_at', Carbon::today()->toDateString())
            ->count();
    }

    /**
     * {@inheritDoc}
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

    /**
     * {@inheritDoc}
     */
    public function getByUserId(int $userId): Collection
    {
        /** @var Collection<int, Order> */
        return $this->model
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * {@inheritDoc}
     */
    public function deleteByUserId(int $userId): void
    {
        $this->model
            ->where('user_id', $userId)
            ->delete();
    }
}
