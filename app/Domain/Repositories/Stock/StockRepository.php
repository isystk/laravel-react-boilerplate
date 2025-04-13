<?php

namespace App\Domain\Repositories\Stock;

use App\Domain\Entities\Stock;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use App\Domain\Repositories\BaseRepository;

interface StockRepository extends BaseRepository
{
    /**
     * @param int $limit
     * @return LengthAwarePaginator<int, Stock>
     */
    public function getByLimit(int $limit = 0): LengthAwarePaginator;

    /**
     * 検索条件からデータを取得します。
     * @param array{
     *   name : ?string,
     *   sort_name : ?string,
     *   sort_direction : 'asc' | 'desc' | null,
     *   limit : ?int,
     * } $conditions
     * @return Collection<int, Stock>|LengthAwarePaginator<int, Stock>
     */
    public function getByConditions(array $conditions): Collection|LengthAwarePaginator;

}
