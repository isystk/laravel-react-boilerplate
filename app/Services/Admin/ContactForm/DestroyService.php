<?php

namespace App\Services\Admin\ContactForm;

use App\Domain\Repositories\ContactForm\ContactFormRepository;
use App\Services\BaseService;

class DestroyService extends BaseService
{
    public function __construct(private readonly ContactFormRepository $contactFormRepository) {}

    /**
     * お問い合わせを削除します。
     */
    public function delete(int $contactFormId): void
    {
        $this->contactFormRepository->delete($contactFormId);
    }
}
