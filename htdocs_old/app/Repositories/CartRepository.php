<?php

namespace App\Repositories;

use App\Models\Cart;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Prettus\Repository\Eloquent\BaseRepository;

class CartRepository extends BaseRepository
{

    /**
     * @return string
     */
    function model()
    {
        return Cart::class;
    }

    /**
     * @param string $userId
     * @param array<string, mixed>|array<int, string> $options
     * @return Collection|LengthAwarePaginator
     */
    public function findAll(string $userId, array $options = []): Collection|LengthAwarePaginator
    {
        $query = $this->getModel()->with($this->__with($options))
            ->where([
                'user_id' => $userId,
            ]);

        $limit = !empty($options['limit']) ? (int)$options['limit'] : null;
        return $limit > 0 ? $query->paginate($limit) : $query->get();
    }

    /**
     * @param array<string, mixed>|array<int, string> $options
     * @return array<int, string>
     */
    private function __with($options = [])
    {
        $with = [];
        if (!empty($options['with:stock'])) {
            $with[] = 'stock';
        }
        return $with;
    }

}
