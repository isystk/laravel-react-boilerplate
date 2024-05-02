<?php

namespace App\Domain\Repositories\Cart;

use App\Domain\Entities\Cart;
use App\Domain\Repositories\BaseEloquentRepository;
use Illuminate\Support\Collection;

class CartEloquentRepository extends BaseEloquentRepository implements CartRepository
{

    /**
     * @return string
     */
    protected function model(): string
    {
        return Cart::class;
    }

    /**
     * ユーザーIDからデータを取得します。
     * @param string $userId
     * @return Collection
     */
    public function getByUserId(string $userId): Collection
    {
        $query = $this->model
            ->with([
                'user',
                'stock',
            ])
            ->where([
                'user_id' => $userId,
            ]);

        return $query->get();
    }

    /**
     * ユーザーIDからデータを削除します。
     * @param int $userId
     * @return void
     */
    public function deleteByUserId(int $userId): void
    {
        $this->model
            ->where('user_id', $userId)
            ->delete();
    }

}
