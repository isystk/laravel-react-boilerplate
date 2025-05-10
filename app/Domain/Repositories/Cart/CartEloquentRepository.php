<?php

namespace App\Domain\Repositories\Cart;

use App\Domain\Entities\Cart;
use App\Domain\Repositories\BaseEloquentRepository;
use Illuminate\Support\Collection;

class CartEloquentRepository extends BaseEloquentRepository implements CartRepository
{
    protected function model(): string
    {
        return Cart::class;
    }

    /**
     * ユーザーIDからデータを取得します。
     *
     * @return Collection<int, Cart>
     */
    public function getByUserId(int $userId): Collection
    {
        /** @var Collection<int, Cart> */
        return $this->model
            ->with([
                'user',
                'stock',
            ])
            ->where('user_id', $userId)
            ->get();
    }

    /**
     * ユーザーIDからデータを削除します。
     */
    public function deleteByUserId(int $userId): void
    {
        $this->model
            ->where('user_id', $userId)
            ->delete();
    }
}
