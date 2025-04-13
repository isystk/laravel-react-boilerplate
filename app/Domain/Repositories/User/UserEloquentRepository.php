<?php

namespace App\Domain\Repositories\User;

use App\Domain\Entities\User;
use App\Domain\Repositories\BaseEloquentRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class UserEloquentRepository extends BaseEloquentRepository implements UserRepository
{
    /**
     * @return string
     */
    protected function model(): string
    {
        return User::class;
    }

    /**
     * 検索条件からデータを取得します。
     * @param array{
     *   name : ?string,
     *   email : ?string,
     *   sort_name : ?string,
     *   sort_direction : 'asc' | 'desc' | null,
     *   limit : ?int,
     * } $conditions
     * @return Collection<int, User>|LengthAwarePaginator<int, User>
     */
    public function getByConditions(array $conditions): Collection|LengthAwarePaginator
    {
        $query = $this->model->select();

        if (null !== $conditions['name']) {
            $query->where('name', 'like', '%' . $conditions['name'] . '%');
        }
        if (null !== $conditions['email']) {
            $query->where('email', 'like', '%' . $conditions['email'] . '%');
        }

        if (null !== $conditions['sort_name']) {
            $query->orderBy($conditions['sort_name'], $conditions['sort_direction'] ?? 'asc');
        }

        return null !== $conditions['limit'] ? $query->paginate($conditions['limit']) : $query->get();
    }

}
