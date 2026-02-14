<?php

namespace App\Services\Admin\ContactForm;

use App\Domain\Entities\ContactForm;
use App\Domain\Repositories\ContactForm\ContactFormRepository;
use App\Dto\Request\Admin\ContactForm\UpdateDto;
use App\Enums\ImageType;
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
     * * 画像の処理ルール:
     * - 既存画像があり、新しいファイル名が空の場合: 画像を紐付け解除（null）
     * - 既存画像があり、新しいファイルがある場合: ImageServiceで画像を更新
     * - 既存画像がなく、新しいファイルがある場合: ImageServiceで新規保存し、IDを紐付け
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

        if (!empty($contactForm->image_id) && !$dto->imageFileName) {
            $data['image_id'] = null;
        } elseif (!empty($contactForm->image_id) && $dto->imageFile) {
            $oldImage = $contactForm->image;
            $this->imageService->update($oldImage, $dto->imageFile, $dto->imageFileName);
        } elseif ($dto->imageFile) {
            $image = $this->imageService->store(
                $dto->imageFile,
                ImageType::Contact,
                $dto->imageFileName
            );
            $data['image_id'] = $image->id;
        }

        return $this->contactFormRepository->update($data, $contactForm->id);
    }
}
