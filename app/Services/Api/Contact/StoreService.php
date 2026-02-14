<?php

namespace App\Services\Api\Contact;

use App\Domain\Entities\Contact;
use App\Domain\Repositories\Contact\ContactRepository;
use App\Dto\Request\Api\Contact\CreateDto;
use App\Enums\ImageType;
use App\Services\BaseService;
use App\Services\Common\ImageService;

class StoreService extends BaseService
{
    public function __construct(
        private readonly ContactRepository $contactRepository,
        private readonly ImageService $imageService,
    ) {}

    /**
     * お問い合わせを登録します。
     */
    public function save(CreateDto $dto): Contact
    {
        $image = null;
        if ($dto->imageFile) {
            $image = $this->imageService->store(
                $dto->imageFile,
                ImageType::Contact,
                $dto->imageFile->getClientOriginalName()
            );
        }

        return $this->contactRepository->create([
            'user_name' => $dto->userName,
            'title'     => $dto->title,
            'email'     => $dto->email,
            'url'       => $dto->url,
            'gender'    => $dto->gender,
            'age'       => $dto->age,
            'contact'   => $dto->contact,
            'image_id'  => $image?->id,
        ]);
    }
}
