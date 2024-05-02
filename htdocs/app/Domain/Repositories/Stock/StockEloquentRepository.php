<?php

namespace App\Domain\Repositories\Stock;

use App\Domain\Entities\Stock;
use App\Domain\Repositories\BaseEloquentRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class StockEloquentRepository extends BaseEloquentRepository implements StockRepository
{

    /**
     * @return string
     */
    protected function model(): string
    {
        return Stock::class;
    }

    /**
     * @param int $limit
     * @return LengthAwarePaginator
     */
    public function getByLimit(int $limit = 0): LengthAwarePaginator
    {
        return $this->model
            ->orderBy('updated_at', 'desc')
            ->orderBy('id', 'asc')
            ->paginate($limit);
    }

    /**
     * 検索条件からデータを取得します。
     * @param array{
     *   name : ?string,
     *   limit : ?int,
     * } $conditions
     * @return Collection|LengthAwarePaginator
     */
    public function getByConditions(array $conditions): Collection|LengthAwarePaginator
    {
        $query = $this->model
            ->orderBy('updated_at', 'desc')
            ->orderBy('id', 'asc');

        if (null !== $conditions['name']) {
            $query->where('name', 'like', '%' . $conditions['name'] . '%');
        }

        return null !== $conditions['limit'] ? $query->paginate($conditions['limit']) : $query->get();
    }

}
