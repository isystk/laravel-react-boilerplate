<?php

namespace App\Domain\Repositories\ContactForm;

use App\Domain\Entities\ContactFormImage;
use App\Domain\Repositories\BaseEloquentRepository;
use Illuminate\Support\Collection;

class ContactFormImageEloquentRepository extends BaseEloquentRepository implements ContactFormImageRepository
{
    protected function model(): string
    {
        return ContactFormImage::class;
    }

    /**
     * contactFormId からデータを取得します。
     *
     * @return Collection<int, ContactFormImage>
     */
    public function getByContactFormId(int $contactFormId): Collection
    {
        /** @var Collection<int, ContactFormImage> */
        return $this->model
            ->where('contact_form_id', $contactFormId)
            ->orderBy('id', 'asc')
            ->get();
    }
}
