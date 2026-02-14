<?php

namespace App\Domain\Repositories\Contact;

use App\Domain\Entities\Contact;
use App\Domain\Repositories\BaseEloquentRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ContactEloquentRepository extends BaseEloquentRepository implements ContactRepository
{
    protected function model(): string
    {
        return Contact::class;
    }

    /**
     * 検索条件からデータを取得します。
     *
     * @param array{
     *   user_name : ?string,
     *   title : ?string,
     *   sort_name : ?string,
     *   sort_direction : 'asc' | 'desc' | null,
     *   limit : ?int,
     * } $conditions
     * @return Collection<int, Contact>|LengthAwarePaginator<int, Contact>
     */
    public function getByConditions(array $conditions): Collection|LengthAwarePaginator
    {
        $query = $this->model->select();

        if (!is_null($conditions['user_name'] ?? null)) {
            $query->where('user_name', 'like', '%' . $conditions['user_name'] . '%');
        }
        if (!is_null($conditions['title'] ?? null)) {
            $query->where('title', 'like', '%' . $conditions['title'] . '%');
        }

        if (!is_null($conditions['sort_name'] ?? null)) {
            $query->orderBy($conditions['sort_name'], $conditions['sort_direction'] ?? 'asc');
        }

        if (!is_null($conditions['limit'] ?? null)) {
            /** @var LengthAwarePaginator<int, Contact> */
            return $query->paginate($conditions['limit']);
        }

        /** @var Collection<int, Contact> */
        return $query->get();
    }
}
