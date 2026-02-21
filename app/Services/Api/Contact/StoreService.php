<?php

namespace App\Services\Api\Contact;

use App\Domain\Entities\Contact;
use App\Domain\Entities\User;
use App\Domain\Repositories\Contact\ContactRepositoryInterface;
use App\Dto\Request\Api\Contact\CreateDto;
use App\Enums\ImageType;
use App\Services\BaseService;
use App\Services\Common\ImageService;

class StoreService extends BaseService
{
    public function __construct(
        private readonly ContactRepositoryInterface $contactRepository,
        private readonly ImageService $imageService,
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

        return $this->contactRepository->create([
            'user_id'  => $user->id,
            'title'    => $dto->title,
            'type'     => $dto->type,
            'message'  => $dto->message,
            'image_id' => $image?->id,
        ]);
    }
}
