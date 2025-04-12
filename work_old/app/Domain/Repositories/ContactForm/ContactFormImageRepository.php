<?php

namespace App\Domain\Repositories\ContactForm;

use App\Domain\Entities\ContactFormImage;
use Illuminate\Database\Eloquent\Collection;
use App\Domain\Repositories\BaseRepository;

interface ContactFormImageRepository extends BaseRepository
{
    /**
     * contactFormId からデータを取得します。
     * @param int $contactFormId
     * @return Collection<int, ContactFormImage>
     */
    public function getByContactFormId(int $contactFormId): Collection;

}
