<?php

namespace App\Domain\Repositories\Admin;

use App\Domain\Entities\Admin;
use App\Domain\Repositories\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class AdminRepository extends BaseRepository implements AdminRepositoryInterface
{
    protected function model(): string
    {
        return Admin::class;
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
        if (!is_null($conditions['email'] ?? null)) {
            $query->where('email', 'like', '%' . $conditions['email'] . '%');
        }
        if (!is_null($conditions['role'] ?? null)) {
            $query->where('role', $conditions['role']);
        }

        $sortColumn = $this->validateSortColumn(
            $conditions['sort_name'] ?? '',
            ['id', 'name', 'email', 'role', 'created_at', 'updated_at'],
        );
        if ($sortColumn !== null) {
            $query->orderBy($sortColumn, $conditions['sort_direction'] ?? 'asc');
        }

        if (!is_null($conditions['limit'] ?? null)) {
            /** @var LengthAwarePaginator<int, Admin> */
            return $query->paginate($conditions['limit']);
        }

        /** @var Collection<int, Admin> */
        return $query->get();
    }

    /**
     * {@inheritDoc}
     */
    public function getByEmail(string $email): ?Admin
    {
        /** @var ?Admin */
        return $this->model
            ->where('email', $email)
            ->first();
    }

    /**
     * {@inheritDoc}
     */
    public function getAllOrderById(): Collection
    {
        /** @var Collection<int, Admin> */
        return $this->model
            ->orderBy('id', 'asc')
            ->get();
    }
}
