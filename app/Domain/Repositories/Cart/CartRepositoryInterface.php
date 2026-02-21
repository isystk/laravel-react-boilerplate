<?php

namespace App\Domain\Repositories\Cart;

use App\Domain\Entities\Cart;
use App\Domain\Repositories\BaseRepositoryInterface;
use Illuminate\Support\Collection;

interface CartRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * ユーザーIDからデータを取得します。
     *
     * @return Collection<int, Cart>
     */
    public function getByUserId(int $userId): Collection;

    /**
     * ユーザーIDからデータを削除します。
     */
    public function deleteByUserId(int $userId): void;
}
