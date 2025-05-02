<?php

namespace App\Domain\Repositories\Admin;

use App\Domain\Entities\Admin;
use App\Domain\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

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
     * @return Collection<int, Admin>|LengthAwarePaginator<int, Admin>
     */
    public function getByConditions(array $conditions): Collection|LengthAwarePaginator;

    /**
     * メールアドレスからレコードを取得します。
     * @param string $email
     * @return Admin|null
     */
    public function getByEmail(string $email): ?Admin;

    /**
     * すべてのデータをIDの昇順で取得します。
     * @return Collection<int, Admin>
     */
    public function getAllOrderById(): Collection;

}
