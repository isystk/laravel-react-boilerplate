<?php

namespace App\Domain\Repositories\ContactForm;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use App\Domain\Repositories\BaseRepository;

interface ContactFormImageRepository extends BaseRepository
{
    /**
     * contactFormId からデータを取得します。
     * @param int $contactFormId
     * @return Collection
     */
    public function getByContactFormId(int $contactFormId): Collection;

}
