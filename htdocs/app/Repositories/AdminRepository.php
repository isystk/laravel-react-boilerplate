<?php

namespace App\Repositories;

use App\Models\Admin;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class AdminRepository extends BaseRepository
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
