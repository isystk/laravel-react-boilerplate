<?php

namespace App\Domain\Repositories\User;

use App\Domain\Entities\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use App\Domain\Repositories\BaseRepository;

interface UserRepository extends BaseRepository
{
    /**
     * 検索条件からデータを取得します。
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

}
