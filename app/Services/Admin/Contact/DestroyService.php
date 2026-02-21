<?php

namespace App\Services\Admin\Contact;

use App\Domain\Repositories\Contact\ContactRepositoryInterface;
use App\Services\BaseService;

class DestroyService extends BaseService
{
    public function __construct(private readonly ContactRepositoryInterface $contactRepository) {}

    /**
     * お問い合わせを削除します。
     */
    public function delete(int $contactId): void
    {
        $this->contactRepository->delete($contactId);
    }
}
