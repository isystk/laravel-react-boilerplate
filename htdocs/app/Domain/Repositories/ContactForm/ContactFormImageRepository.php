<?php

namespace App\Domain\Repositories\ContactForm;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use App\Domain\Repositories\BaseRepository;

interface ContactFormImageRepository extends BaseRepository
{
    /**
     * @param int $contactFormId
     * @param array<string, mixed>|array<int, string> $options
     * @return Collection|LengthAwarePaginator
     */
    public function findAll(int $contactFormId, array $options = []): Collection|LengthAwarePaginator;
}
