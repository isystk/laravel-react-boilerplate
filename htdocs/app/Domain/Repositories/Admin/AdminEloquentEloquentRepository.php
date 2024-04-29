<?php

namespace App\Domain\Repositories\Admin;

use App\Domain\Entities\Admin;
use App\Domain\Repositories\BaseEloquentRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class AdminEloquentEloquentRepository extends BaseEloquentRepository implements AdminRepository
{

    /**
     * @return string
     */
    protected function model(): string
    {
        return Admin::class;
    }

    /**
     * @param array<string, mixed>|array<int, string> $options
     * @return Collection|LengthAwarePaginator
     */
    public function findAll($options = []): Collection|LengthAwarePaginator
    {
        $query = $this->model->with($this->__with($options));

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
        return $with;
    }

}
