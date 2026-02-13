<?php

namespace App\Services\Api\ContactForm;

use App\Domain\Entities\ContactForm;
use App\Domain\Repositories\ContactForm\ContactFormRepository;
use App\Dto\Request\Api\ContactForm\CreateDto;
use App\Enums\PhotoType;
use App\Services\BaseService;

class StoreService extends BaseService
{
    public function __construct(private readonly ContactFormRepository $contactFormRepository) {}

    /**
     * お問い合わせを登録します。
     */
    public function save(CreateDto $dto): ContactForm
    {
        $contactForm = $this->contactFormRepository->create(
            [
                'user_name'       => $dto->userName,
                'title'           => $dto->title,
                'email'           => $dto->email,
                'url'             => $dto->url,
                'gender'          => $dto->gender,
                'age'             => $dto->age,
                'contact'         => $dto->contact,
                'image_file_name' => $dto->imageFile?->getClientOriginalName(),
            ]
        );

        if (!is_null($dto->imageFile)) {
            $dto->imageFile->storeAs(PhotoType::Contact->type(), $dto->imageFile->getClientOriginalName(), 's3');
        }

        return $contactForm;
    }
}
