<?php

namespace App\Domain\Repositories\Stock;

use App\Domain\Entities\Stock;
use App\Domain\Repositories\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class StockRepository extends BaseRepository implements StockRepositoryInterface
{
    protected function model(): string
    {
        return Stock::class;
    }

    /**
     * {@inheritDoc}
     */
    public function getByLimit(int $limit = 0): LengthAwarePaginator
    {
        /** @var LengthAwarePaginator<int, Stock> */
        return $this->model
            ->orderBy('id', 'desc')
            ->paginate($limit);
    }

    /**
     * {@inheritDoc}
     */
    public function getByConditions(array $conditions): Collection|LengthAwarePaginator
    {
        $query = $this->model->select();

        if (!is_null($conditions['name'] ?? null)) {
            $query->where('name', 'like', '%' . $conditions['name'] . '%');
        }

        $sortColumn = $this->validateSortColumn(
            $conditions['sort_name'] ?? '',
            ['id', 'name', 'detail', 'price', 'quantity', 'created_at', 'updated_at'],
        );
        if ($sortColumn !== null) {
            $query->orderBy($sortColumn, $conditions['sort_direction'] ?? 'asc');
        }

        if (!is_null($conditions['limit'] ?? null)) {
            /** @var LengthAwarePaginator<int, Stock> */
            return $query->paginate($conditions['limit']);
        }

        /** @var Collection<int, Stock> */
        return $query->get();
    }

    /**
     * {@inheritDoc}
     */
    public function getByIds(array $ids): Collection
    {
        /** @var Collection<int, Stock> */
        return $this->model
            ->whereIn('id', $ids)
            ->get();
    }
}
