<?php

namespace App\Domain\Repositories\Admin;

use App\Domain\Entities\Admin;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use App\Domain\Repositories\BaseRepository;

interface AdminRepository extends BaseRepository
{
    /**
     * 検索条件からデータを取得します。
     * @param array{
     *   name : ?string,
     *   email : ?string,
     *   role : ?string,
     *   sort_name : ?string,
     *   sort_direction : 'asc' | 'desc' | null,
     *   limit : ?int,
     * } $conditions
     * @return Collection<int, Admin>|LengthAwarePaginator<Admin>
     */
    public function getByConditions(array $conditions): Collection|LengthAwarePaginator;

}
