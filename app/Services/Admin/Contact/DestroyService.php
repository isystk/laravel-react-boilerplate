<?php

namespace App\Services\Admin\Contact;

use App\Domain\Repositories\Contact\ContactRepository;
use App\Services\BaseService;

class DestroyService extends BaseService
{
    public function __construct(private readonly ContactRepository $contactRepository) {}

    /**
     * お問い合わせを削除します。
     */
    public function delete(int $contactId): void
    {
        $this->contactRepository->delete($contactId);
    }
}
