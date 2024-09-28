<?php

namespace App\Domain\Repositories\ContactForm;

use App\Domain\Entities\ContactFormImage;
use App\Domain\Repositories\BaseEloquentRepository;
use Illuminate\Database\Eloquent\Collection;

class ContactFormImageEloquentRepository extends BaseEloquentRepository implements ContactFormImageRepository
{

    /**
     * @return string
     */
    protected function model(): string
    {
        return ContactFormImage::class;
    }

    /**
     * contactFormId からデータを取得します。
     * @param int $contactFormId
     * @return Collection<int, ContactFormImage>
     */
    public function getByContactFormId(int $contactFormId): Collection
    {
        /** @var Collection<int, ContactFormImage> $items */
        $items = $this->model
            ->where('contact_form_id', $contactFormId)
            ->orderBy('id', 'asc')
            ->get();
        return $items;
    }

}
