<?php

namespace App\Domain\Repositories\User;

use App\Domain\Entities\User;
use App\Domain\Repositories\BaseRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * 検索条件からデータを取得します。
     *
     * @param array{
     *   name : ?string,
     *   email : ?string,
     *   sort_name : ?string,
     *   sort_direction : 'asc' | 'desc' | null,
     *   limit : ?int,
     * } $conditions
     * @return Collection<int, User>|LengthAwarePaginator<int, User>
     */
    public function getByConditions(array $conditions): Collection|LengthAwarePaginator;

    /**
     * Google IDからレコードを取得します。削除済みのレコードも対象とします。
     */
    public function findByGoogleIdWithTrashed(string $googleId): ?User;
}
