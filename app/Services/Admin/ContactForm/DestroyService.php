<?php

namespace App\Services\Admin\ContactForm;

use App\Domain\Repositories\ContactForm\ContactFormImageRepository;
use App\Domain\Repositories\ContactForm\ContactFormRepository;
use App\Services\BaseService;

class DestroyService extends BaseService
{
    public function __construct(private readonly ContactFormRepository $contactFormRepository, private readonly ContactFormImageRepository $contactFormImageRepository) {}

    /**
     * お問い合わせを削除します。
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
