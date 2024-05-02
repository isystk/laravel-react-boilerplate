<?php

namespace App\Domain\Repositories\Cart;

use App\Domain\Repositories\BaseRepository;
use Illuminate\Support\Collection;

interface CartRepository extends BaseRepository
{
    /**
     * ユーザーIDからデータを取得します。
     * @param string $userId
     * @return Collection
     */
    public function getByUserId(string $userId): Collection;

    /**
     * ユーザーIDからデータを削除します。
     * @param int $userId
     * @return void
     */
    public function deleteByUserId(int $userId): void;
}
