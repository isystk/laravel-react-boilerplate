<?php

namespace App\Domain\Repositories\ContactForm;

use App\Domain\Entities\ContactFormImage;
use App\Domain\Repositories\BaseRepository;
use Illuminate\Support\Collection;

interface ContactFormImageRepository extends BaseRepository
{
    /**
     * contactFormId からデータを取得します。
     *
     * @return Collection<int, ContactFormImage>
     */
    public function getByContactFormId(int $contactFormId): Collection;
}
