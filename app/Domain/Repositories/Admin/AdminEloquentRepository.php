<?php

namespace App\Domain\Repositories\Admin;

use App\Domain\Entities\Admin;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Domain\Repositories\BaseEloquentRepository;
use Illuminate\Database\Eloquent\Collection;

class AdminEloquentRepository extends BaseEloquentRepository implements AdminRepository
{

    /**
     * @return string
     */
    protected function model(): string
    {
        return Admin::class;
    }

    /**
     * 検索条件からデータを取得します。
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

        if (null !== $conditions['name']) {
            $query->where('name', 'like', '%' . $conditions['name'] . '%');
        }
        if (null !== $conditions['email']) {
            $query->where('email', 'like', '%' . $conditions['email'] . '%');
        }
        if (null !== $conditions['role']) {
            $query->where('role', $conditions['role']);
        }

        if (null !== $conditions['sort_name']) {
            $query->orderBy($conditions['sort_name'], $conditions['sort_direction'] ?? 'asc');
        }

        return null !== $conditions['limit'] ? $query->paginate($conditions['limit']) : $query->get();
    }

    /**
     * メールアドレスからレコードを取得します。
     * @param string $email
     * @return Admin|null
     */
    public function getByEmail(string $email): ?Admin {
        /** @var Admin */
        return $this->model->select()
            ->where('email', $email)
            ->first();
    }

    /**
     * すべてのデータをIDの昇順で取得します。
     * @return Collection<int, Admin>
     */
    public function getAllOrderById(): Collection
    {
        $query = $this->model->select();
        $query = $query->orderBy('id', 'asc');
        /** @var Collection<int, Admin> */
        return $query->get();
    }

}
