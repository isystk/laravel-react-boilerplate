<?php

namespace App\Domain\Repositories\Stock;

use App\Domain\Entities\Stock;
use App\Domain\Repositories\BaseEloquentRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class StockEloquentRepository extends BaseEloquentRepository implements StockRepository
{
    protected function model(): string
    {
        return Stock::class;
    }

    /**
     * 指定した件数のデータを最新順に取得します。
     *
     * @return LengthAwarePaginator<int, Stock>
     */
    public function getByLimit(int $limit = 0): LengthAwarePaginator
    {
        /** @var LengthAwarePaginator<int, Stock> */
        return $this->model
            ->orderBy('id', 'desc')
            ->paginate($limit);
    }

    /**
     * 検索条件からデータを取得します。
     *
     * @param array{
     *   name : ?string,
     *   sort_name : ?string,
     *   sort_direction : 'asc' | 'desc' | null,
     *   limit ?: ?int,
     * } $conditions
     * @return Collection<int, Stock>|LengthAwarePaginator<int, Stock>
     */
    public function getByConditions(array $conditions): Collection|LengthAwarePaginator
    {
        $query = $this->model->select();

        if (!is_null($conditions['name'] ?? null)) {
            $query->where('name', 'like', '%' . $conditions['name'] . '%');
        }

        if (!is_null($conditions['sort_name'] ?? null)) {
            $query->orderBy($conditions['sort_name'], $conditions['sort_direction'] ?? 'asc');
        }

        if (!is_null($conditions['limit'] ?? null)) {
            /** @var LengthAwarePaginator<int, Stock> */
            return $query->paginate($conditions['limit']);
        }

        /** @var Collection<int, Stock> */
        return $query->get();
    }
}
