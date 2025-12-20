<?php

namespace App\Domain\Repositories\ContactForm;

use App\Domain\Entities\ContactForm;
use App\Domain\Repositories\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ContactFormRepository extends BaseRepository
{
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
     * @return Collection<int, ContactForm>|LengthAwarePaginator<int, ContactForm>
     */
    public function getByConditions(array $conditions): Collection|LengthAwarePaginator;
}
