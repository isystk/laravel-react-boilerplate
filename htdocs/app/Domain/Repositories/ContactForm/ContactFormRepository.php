<?php

namespace App\Domain\Repositories\ContactForm;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use App\Domain\Repositories\BaseRepository;

interface ContactFormRepository extends BaseRepository
{
    /**
     * @param string|null $yourName
     * @param array<string, mixed>|array<int, string> $options
     * @return Collection|LengthAwarePaginator
     */
    public function findAll(?string $yourName, array $options = []): Collection|LengthAwarePaginator;

}
