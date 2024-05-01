<?php

namespace App\Domain\Repositories\Order;

use App\Domain\Entities\Order;
use App\Domain\Repositories\BaseEloquentRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class OrderEloquentEloquentRepository extends BaseEloquentRepository implements OrderRepository
{

    /**
     * @return string
     */
    protected function model(): string
    {
        return Order::class;
    }

    /**
     * @param string|null $userName
     * @param array<string, mixed>|array<int, string> $options
     * @return Collection|LengthAwarePaginator
     */
    public function findAll(?string $userName, array $options = []): Collection|LengthAwarePaginator
    {
        $query = $this->model->with($this->__with($options))
            ->whereHas('user', function ($query) use ($userName)
            {
                $query->where('name', 'like', "%$userName%");
            })
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'asc');

        $limit = !empty($options['limit']) ? (int)$options['limit'] : null;
        return $limit > 0 ? $query->paginate($limit) : $query->get();
    }

    /**
     * @param array<string, mixed>|array<int, string> $options
     * @return array<int, string>
     */
    private function __with(array $options = [])
    {
        $with = [];
        if (!empty($options['with:user'])) {
            $with[] = 'user';
        }
        if (!empty($options['with:stock'])) {
            $with[] = 'stock';
        }
        return $with;
    }

}
