<?php

namespace App\Services\Admin\ContactForm;

use App\Domain\Entities\ContactForm;
use App\Domain\Repositories\ContactForm\ContactFormRepository;
use App\Dto\Request\Admin\ContactForm\UpdateDto;
use App\Enums\PhotoType;
use App\Services\BaseService;
use App\Services\Common\ImageService;

class UpdateService extends BaseService
{
    public function __construct(
        private readonly ContactFormRepository $contactFormRepository,
        private readonly ImageService $imageService,
    ) {}

    /**
     * お問い合わせを更新します。
     */
    public function update(ContactForm $contactForm, UpdateDto $dto): ContactForm
    {
        $data = [
            'user_name' => $dto->userName,
            'title'     => $dto->title,
            'email'     => $dto->email,
            'url'       => $dto->url,
            'gender'    => $dto->gender,
            'age'       => $dto->age,
            'contact'   => $dto->contact,
        ];

        if (!empty($contactForm->image_id) && $dto->imageFile) {
            $oldImage = $contactForm->image;
            $this->imageService->update($oldImage, $dto->imageFile, $dto->imageFileName);
        } elseif ($dto->imageFile) {
            $image = $this->imageService->store(
                $dto->imageFile,
                PhotoType::Contact,
                $dto->imageFileName
            );
            $data['image_id'] = $image->id;
        }

        return $this->contactFormRepository->update($data, $contactForm->id);
    }
}
