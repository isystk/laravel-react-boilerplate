<?php

namespace App\Domain\Repositories\Admin;

use App\Domain\Entities\Admin;
use App\Domain\Repositories\BaseEloquentRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class AdminEloquentRepository extends BaseEloquentRepository implements AdminRepository
{
    protected function model(): string
    {
        return Admin::class;
    }

    /**
     * 検索条件からデータを取得します。
     *
     * @param array{
     *   name : ?string,
     *   email : ?string,
     *   role : ?string,
     *   sort_name : ?string,
     *   sort_direction : 'asc' | 'desc' | null,
     *   limit : ?int,
     * } $conditions
     * @return Collection<int, Admin>|LengthAwarePaginator<int, Admin>
     */
    public function getByConditions(array $conditions): Collection|LengthAwarePaginator
    {
        $query = $this->model->select();

        if (!is_null($conditions['name'] ?? null)) {
            $query->where('name', 'like', '%' . $conditions['name'] . '%');
        }
        if (!is_null($conditions['email'] ?? null)) {
            $query->where('email', 'like', '%' . $conditions['email'] . '%');
        }
        if (!is_null($conditions['role'] ?? null)) {
            $query->where('role', $conditions['role']);
        }

        if (!is_null($conditions['sort_name'] ?? null)) {
            $query->orderBy($conditions['sort_name'], $conditions['sort_direction'] ?? 'asc');
        }

        if (!is_null($conditions['limit'] ?? null)) {
            /** @var LengthAwarePaginator<int, Admin> */
            return $query->paginate($conditions['limit']);
        }

        /** @var Collection<int, Admin> */
        return $query->get();
    }

    /**
     * メールアドレスからレコードを取得します。
     */
    public function getByEmail(string $email): ?Admin
    {
        /** @var ?Admin */
        return $this->model
            ->where('email', $email)
            ->first();
    }

    /**
     * すべてのデータをIDの昇順で取得します。
     *
     * @return Collection<int, Admin>
     */
    public function getAllOrderById(): Collection
    {
        /** @var Collection<int, Admin> */
        return $this->model
            ->orderBy('id', 'asc')
            ->get();
    }
}
