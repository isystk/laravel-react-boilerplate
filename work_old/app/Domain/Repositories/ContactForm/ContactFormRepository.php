<?php

namespace App\Domain\Repositories\ContactForm;

use App\Domain\Entities\ContactForm;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use App\Domain\Repositories\BaseRepository;

interface ContactFormRepository extends BaseRepository
{
    /**
     * 検索条件からデータを取得します。
     * @param array{
     *   user_name : ?string,
     *   title : ?string,
     *   sort_name : ?string,
     *   sort_direction : 'asc' | 'desc' | null,
     *   limit : ?int,
     * } $conditions
     * @return Collection<int, ContactForm>|LengthAwarePaginator<ContactForm>
     */
    public function getByConditions(array $conditions): Collection|LengthAwarePaginator;

}
