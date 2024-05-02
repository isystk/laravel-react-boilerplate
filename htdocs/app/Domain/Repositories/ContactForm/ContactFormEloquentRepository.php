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
     *   your_name : ?string,
     *   title : ?string,
     *   limit : ?int,
     * } $conditions
     * @return Collection|LengthAwarePaginator
     */
    public function getByConditions(array $conditions): Collection|LengthAwarePaginator
    {
        $query = $this->model
            ->orderBy('updated_at', 'desc')
            ->orderBy('id', 'asc');

        if (null !== $conditions['your_name']) {
            $query->where('your_name', 'like', '%' . $conditions['your_name'] . '%');
        }
        if (null !== $conditions['title']) {
            $query->where('title', 'like', '%' . $conditions['title'] . '%');
        }

        return null !== $conditions['limit'] ? $query->paginate($conditions['limit']) : $query->get();
    }

}