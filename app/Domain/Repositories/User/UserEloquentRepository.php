<?php

namespace App\Domain\Repositories\User;

use App\Domain\Entities\User;
use App\Domain\Repositories\BaseEloquentRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class UserEloquentRepository extends BaseEloquentRepository implements UserRepository
{
    protected function model(): string
    {
        return User::class;
    }

    /**
     * {@inheritDoc}
     */
    public function getByConditions(array $conditions): Collection|LengthAwarePaginator
    {
        $query = $this->model->with('avatarImage')->select();

        if (!is_null($conditions['name'] ?? null)) {
            $query->where('name', 'like', '%' . $conditions['name'] . '%');
        }
        if (!is_null($conditions['email'] ?? null)) {
            $query->where('email', 'like', '%' . $conditions['email'] . '%');
        }

        $sortColumn = $this->validateSortColumn(
            $conditions['sort_name'] ?? '',
            ['id', 'name', 'email', 'created_at', 'updated_at'],
        );
        if ($sortColumn !== null) {
            $query->orderBy($sortColumn, $conditions['sort_direction'] ?? 'asc');
        }

        if (!is_null($conditions['limit'] ?? null)) {
            /** @var LengthAwarePaginator<int, User> */
            return $query->paginate($conditions['limit']);
        }

        /** @var Collection<int, User> */
        return $query->get();
    }

    /**
     * {@inheritDoc}
     */
    public function findByGoogleIdWithTrashed(string $googleId): ?User
    {
        /** @var ?User */
        return User::withTrashed()->where('google_id', $googleId)->first();
    }
}
