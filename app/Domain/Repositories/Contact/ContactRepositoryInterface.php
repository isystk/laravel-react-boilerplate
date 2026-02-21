<?php

namespace App\Domain\Repositories\Contact;

use App\Domain\Entities\Contact;
use App\Domain\Repositories\BaseRepositoryInterface;
use Carbon\CarbonImmutable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ContactRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * 検索条件からデータを取得します。
     *
     * @param array{
     *   keyword : ?string,
     *   title : ?string,
     *   contact_date_from : ?CarbonImmutable,
     *   contact_date_to : ?CarbonImmutable,
     *   only_unreplied : bool,
     *   sort_name : ?string,
     *   sort_direction : 'asc' | 'desc' | null,
     *   limit : ?int,
     * } $conditions
     * @return Collection<int, Contact>|LengthAwarePaginator<int, Contact>
     */
    public function getByConditions(array $conditions): Collection|LengthAwarePaginator;

    /**
     * 未返信のお問い合わせ件数を返します。
     */
    public function countUnreplied(): int;
}
