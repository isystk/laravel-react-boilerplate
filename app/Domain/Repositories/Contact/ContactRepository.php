<?php

namespace App\Domain\Repositories\Contact;

use App\Domain\Entities\Contact;
use App\Domain\Repositories\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ContactRepository extends BaseRepository
{
    /**
     * 検索条件からデータを取得します。
     *
     * @param array{
     *   user_name : ?string,
     *   title : ?string,
     *   sort_name : ?string,
     *   sort_direction : 'asc' | 'desc' | null,
     *   limit : ?int,
     * } $conditions
     * @return Collection<int, Contact>|LengthAwarePaginator<int, Contact>
     */
    public function getByConditions(array $conditions): Collection|LengthAwarePaginator;
}
