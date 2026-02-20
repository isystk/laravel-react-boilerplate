<?php

namespace App\Domain\Repositories\Cart;

use App\Domain\Entities\Cart;
use App\Domain\Repositories\BaseRepository;
use Illuminate\Support\Collection;

class CartRepository extends BaseRepository implements CartRepositoryInterface
{
    protected function model(): string
    {
        return Cart::class;
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     */
    public function deleteByUserId(int $userId): void
    {
        $this->model
            ->where('user_id', $userId)
            ->delete();
    }
}
