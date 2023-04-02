<?php

namespace App\Repositories;

use App\Models\Cart;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CartRepository
{

    /**
     * @param string $userId
     * @param array<string, mixed>|array<int, string> $options
     * @return int
     */
    public function count(string $userId, array $options = []): int
    {
        return Cart::where([
            'user_id' => $userId,
        ])->count();
    }

    /**
     * @param string $userId
     * @param array<string, mixed>|array<int, string> $options
     * @return Collection|LengthAwarePaginator
     */
    public function findAll(string $userId, array $options = []): Collection|LengthAwarePaginator
    {
        $query = Cart::with($this->__with($options))
            ->where([
                'user_id' => $userId,
            ]);

        $limit = !empty($options['limit']) ? (int)$options['limit'] : null;
        return $limit > 0 ? $query->paginate($limit) : $query->get();
    }

    /**
     * @param string $id
     * @param array<string, mixed>|array<int, string> $options
     * @return Cart|null
     */
    public function findById(string $id, array $options = []): Cart|null
    {
        return Cart::with($this->__with($options))
            ->where([
                'id' => $id
            ])
            ->first();
    }

    /**
     * @param array<string, mixed>|array<int, string> $options
     * @return array<int, string>
     */
    private function __with($options = [])
    {
        $with = [];
        if (!empty($options['with:stock'])) {
            $with[] = 'stock';
        }
        return $with;
    }

    /**
     * @param ?string $id
     * @param string $stockId
     * @param string $userId
     * @return Cart
     */
    public function store(
        ?string $id,
        string  $stockId,
        string $userId
    ): Cart
    {
        $cart = new Cart();
        $cart['id'] = $id;
        $cart['stock_id'] = $stockId;
        $cart['user_id'] = $userId;

        $cart->save();

        return $cart;
    }

    /**
     * @param string $id
     * @param string $stockId
     * @param string $userId
     * @return Cart
     */
    public function update(
        string $id,
        string $stockId,
        string $userId
    ): Cart
    {
        $cart = $this->findById($id);
        $cart['stock_id'] = $stockId;
        $cart['user_id'] = $userId;
        $cart->save();

        return $cart;
    }

    /**
     * @param string $id
     * @return Cart|null
     */
    public function delete(
        string $id
    )
    {
        $cart = $this->findById($id);
        $cart->delete();

        return $cart;
    }

}
