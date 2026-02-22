<?php

namespace App\Services\Api\Contact;

use App\Domain\Entities\Contact;
use App\Domain\Entities\User;
use App\Domain\Repositories\Contact\ContactRepositoryInterface;
use App\Dto\Request\Api\Contact\CreateDto;
use App\Enums\ImageType;
use App\Enums\OperationLogType;
use App\Services\BaseService;
use App\Services\Common\ImageService;
use App\Services\Common\OperationLogService;

class StoreService extends BaseService
{
    public function __construct(
        private readonly ContactRepositoryInterface $contactRepository,
        private readonly ImageService $imageService,
        private readonly OperationLogService $operationLogService,
    ) {}

    /**
     * お問い合わせを登録します。
     */
    public function save(User $user, CreateDto $dto): Contact
    {
        $image = null;
        if (!is_null($dto->imageFile)) {
            $image = $this->imageService->store(
                $dto->imageFile,
                ImageType::Contact,
                $dto->imageFile->getClientOriginalName()
            );
        }

        $contact = $this->contactRepository->create([
            'user_id'  => $user->id,
            'title'    => $dto->title,
            'type'     => $dto->type,
            'message'  => $dto->message,
            'image_id' => $image?->id,
        ]);

        $this->operationLogService->logUserAction(
            $user->id,
            OperationLogType::UserContactSend,
            "お問い合わせを送信 (お問い合わせID: {$contact->id})",
            request()->ip()
        );

        return $contact;
    }
}
