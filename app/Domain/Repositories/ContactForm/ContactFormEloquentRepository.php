<?php

namespace App\Domain\Repositories\ContactForm;

use App\Domain\Entities\ContactForm;
use App\Domain\Repositories\BaseEloquentRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ContactFormEloquentRepository extends BaseEloquentRepository implements ContactFormRepository
{

    /**
     * @return string
     */
    protected function model(): string
    {
        return ContactForm::class;
    }

    /**
     * 検索条件からデータを取得します。
     * @param array{
     *   user_name : ?string,
     *   title : ?string,
     *   sort_name : ?string,
     *   sort_direction : 'asc' | 'desc' | null,
     *   limit : ?int,
     * } $conditions
     * @return Collection|LengthAwarePaginator
     */
    public function getByConditions(array $conditions): Collection|LengthAwarePaginator
    {
        $query = $this->model->select();

        if (null !== $conditions['user_name']) {
            $query->where('user_name', 'like', '%' . $conditions['user_name'] . '%');
        }
        if (null !== $conditions['title']) {
            $query->where('title', 'like', '%' . $conditions['title'] . '%');
        }

        if (null !== $conditions['sort_name']) {
            $query->orderBy($conditions['sort_name'], $conditions['sort_direction'] ?? 'asc');
        }

        return null !== $conditions['limit'] ? $query->paginate($conditions['limit']) : $query->get();
    }

}
