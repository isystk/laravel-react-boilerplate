<?php

namespace App\Services\Admin\ContactForm;

use App\Domain\Entities\ContactForm;
use App\Domain\Repositories\ContactForm\ContactFormRepository;
use App\Dto\Request\Admin\ContactForm\UpdateDto;
use App\Enums\PhotoType;
use App\Services\BaseService;

class UpdateService extends BaseService
{
    public function __construct(private readonly ContactFormRepository $contactFormRepository) {}

    /**
     * お問い合わせを更新します。
     */
    public function update(ContactForm $contactForm, UpdateDto $dto): ContactForm
    {
        $contactForm = $this->contactFormRepository->update([
            'user_name'       => $dto->userName,
            'title'           => $dto->title,
            'email'           => $dto->email,
            'url'             => $dto->url,
            'gender'          => $dto->gender,
            'age'             => $dto->age,
            'contact'         => $dto->contact,
            'image_file_name' => $dto->imageFile?->getClientOriginalName(),
        ], $contactForm->id);

        if (!is_null($dto->imageFile)) {
            $dto->imageFile->storeAs(PhotoType::Contact->type(), $dto->imageFile->getClientOriginalName(), 's3');
        }

        return $contactForm;
    }
}
