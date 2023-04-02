<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class OrderRepository
{

    /**
     * @param string $userName
     * @param array<string, mixed>|array<int, string> $options
     * @return int
     */
    public function count(string $userName, array $options = []): int
    {
        return Order::with($this->__with($options))
            ->whereHas('user', function ($query) use ($userName) {
                $query->where('name', 'like', "%$userName%");
            })->count();
    }

    /**
     * @param string|null $userName
     * @param array<string, mixed>|array<int, string> $options
     * @return Collection|LengthAwarePaginator
     */
    public function findAll(?string $userName, array $options = []): Collection|LengthAwarePaginator
    {
        $query = Order::with($this->__with($options))
            ->whereHas('user', function ($query) use ($userName) {
                $query->where('name', 'like', "%$userName%");
            })
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'asc');

        $limit = !empty($options['limit']) ? (int)$options['limit'] : null;
        return $limit > 0 ? $query->paginate($limit) : $query->get();
    }

    /**
     * @param string $id
     * @param array<string, mixed>|array<int, string> $options
     * @return Order|null
     */
    public function findById($id, array $options = []): Order|null
    {
        return Order::with($this->__with($options))
            ->where([
                'id' => $id
            ])
            ->first();
    }

    /**
     * @param array<string, mixed>|array<int, string> $options
     * @return array<int, string>
     */
    private function __with(array $options = [])
    {
        $with = [];
        if (!empty($options['with:user'])) {
            $with[] = 'user';
        }
        if (!empty($options['with:stock'])) {
            $with[] = 'stock';
        }
        return $with;
    }

    /**
     * @param string $id
     * @param string $stockId
     * @param string $userId
     * @param int $price
     * @param int $quantity
     * @return Order
     */
    public function store(
        string $id,
        string $stockId,
        string $userId,
        int $price,
        int $quantity
    ): Order
    {
        $order = new Order();
        $order['id'] = $id;
        $order['stock_id'] = $stockId;
        $order['user_id'] = $userId;
        $order['price'] = $price;
        $order['quantity'] = $quantity;

        $order->save();

        return $order;
    }

    /**
     * @param string $id
     * @param string $stockId
     * @param string $userId
     * @param int $price
     * @param int $quantity
     * @return object|null
     */
    public function update(
        string $id,
        string $stockId,
        string $userId,
        int    $price,
        int $quantity
    ): ?object
    {
        $order = $this->findById($id);
        $order['stock_id'] = $stockId;
        $order['user_id'] = $userId;
        $order['price'] = $price;
        $order['quantity'] = $quantity;
        $order->save();

        return $order;
    }

    /**
     * @param string $id
     * @return object|null
     */
    public function delete(
        string $id
    ): ?object
    {
        $order = $this->findById($id);
        $order->delete();

        return $order;
    }

}
