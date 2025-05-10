<?php

namespace App\Services\Admin\ContactForm;

use App\Domain\Entities\ContactFormImage;
use App\Domain\Repositories\ContactForm\ContactFormImageRepository;
use App\Services\BaseService;
use Illuminate\Support\Collection;

class EditService extends BaseService
{
    private ContactFormImageRepository $contactFormImageRepository;

    public function __construct(
        ContactFormImageRepository $contactFormImageRepository
    ) {
        $this->contactFormImageRepository = $contactFormImageRepository;
    }

    /**
     * お問い合わせを取得します。
     * @return Collection<int, ContactFormImage>
     */
    public function getContactFormImage(int $contactFormId): Collection
    {
        return $this->contactFormImageRepository->getByContactFormId($contactFormId);
    }
}
