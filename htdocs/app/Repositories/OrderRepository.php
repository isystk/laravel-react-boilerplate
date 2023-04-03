<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Prettus\Repository\Eloquent\BaseRepository;

class OrderRepository extends BaseRepository
{

    /**
     * @return string
     */
    function model()
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
        $query = $this->getModel()->with($this->__with($options))
            ->whereHas('user', function ($query) use ($userName) {
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
