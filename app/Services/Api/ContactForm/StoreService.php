<?php

namespace App\Services\Api\ContactForm;

use App\Domain\Entities\ContactForm;
use App\Domain\Repositories\ContactForm\ContactFormRepository;
use App\Dto\Request\Api\ContactForm\CreateDto;
use App\Enums\ImageType;
use App\Services\BaseService;
use App\Services\Common\ImageService;

class StoreService extends BaseService
{
    public function __construct(
        private readonly ContactFormRepository $contactFormRepository,
        private readonly ImageService $imageService,
    ) {}

    /**
     * お問い合わせを登録します。
     */
    public function save(CreateDto $dto): ContactForm
    {
        $image = null;
        if ($dto->imageFile) {
            $image = $this->imageService->store(
                $dto->imageFile,
                ImageType::Contact,
                $dto->imageFile->getClientOriginalName()
            );
        }

        return $this->contactFormRepository->create([
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
