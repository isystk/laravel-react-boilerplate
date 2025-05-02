<?php

namespace App\Services\Admin\ContactForm;

use App\Domain\Repositories\ContactForm\ContactFormImageRepository;
use App\Domain\Repositories\ContactForm\ContactFormRepository;
use App\Services\BaseService;

class DestroyService extends BaseService
{
    private ContactFormRepository $contactFormRepository;
    private ContactFormImageRepository $contactFormImageRepository;

    /**
     * Create a new controller instance.
     *
     * @param ContactFormRepository $contactFormRepository
     * @param ContactFormImageRepository $contactFormImageRepository
     */
    public function __construct(
        ContactFormRepository $contactFormRepository,
        ContactFormImageRepository $contactFormImageRepository
    ) {
        $this->contactFormRepository = $contactFormRepository;
        $this->contactFormImageRepository = $contactFormImageRepository;
    }

    /**
     * お問い合わせを削除します。
     * @param int $contactFormId
     */
    public function delete(int $contactFormId): void
    {
        // お問い合わせ画像テーブルを削除
        $contactFormImages = $this->contactFormImageRepository->getByContactFormId($contactFormId);
        foreach ($contactFormImages as $contactFormImage) {
            $this->contactFormImageRepository->delete($contactFormImage->id);
        }
        // お問い合わせテーブルを削除
        $this->contactFormRepository->delete($contactFormId);
    }
}
