<?php

namespace App\Services\Admin\Contact;

use App\Domain\Entities\Contact;
use App\Domain\Repositories\Contact\ContactRepository;
use App\Dto\Request\Admin\Contact\UpdateDto;
use App\Enums\ImageType;
use App\Services\BaseService;
use App\Services\Common\ImageService;

class UpdateService extends BaseService
{
    public function __construct(
        private readonly ContactRepository $contactRepository,
        private readonly ImageService $imageService,
    ) {}

    /**
     * お問い合わせを更新します。
     * * 画像の処理ルール:
     * - 既存画像があり、新しいファイル名が空の場合: 画像を紐付け解除（null）
     * - 既存画像があり、新しいファイルがある場合: ImageServiceで画像を更新
     * - 既存画像がなく、新しいファイルがある場合: ImageServiceで新規保存し、IDを紐付け
     */
    public function update(Contact $contact, UpdateDto $dto): Contact
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

        if (!empty($contact->image_id) && !$dto->imageFileName) {
            $data['image_id'] = null;
        } elseif (!empty($contact->image_id) && $dto->imageFile) {
            $oldImage = $contact->image;
            $this->imageService->update($oldImage, $dto->imageFile, $dto->imageFileName);
        } elseif ($dto->imageFile) {
            $image = $this->imageService->store(
                $dto->imageFile,
                ImageType::Contact,
                $dto->imageFileName
            );
            $data['image_id'] = $image->id;
        }

        return $this->contactRepository->update($data, $contact->id);
    }
}
