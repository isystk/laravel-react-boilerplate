<?php

namespace App\Services\Api\ContactForm;

use App\Domain\Entities\ContactForm;
use App\Domain\Repositories\ContactForm\ContactFormImageRepository;
use App\Domain\Repositories\ContactForm\ContactFormRepository;
use App\Enums\PhotoType;
use App\Http\Requests\Api\ContactForm\StoreRequest;
use App\Services\BaseService;

class StoreService extends BaseService
{
    private ContactFormRepository $contactFormRepository;

    private ContactFormImageRepository $contactFormImageRepository;

    public function __construct(
        ContactFormRepository $contactFormRepository,
        ContactFormImageRepository $contactFormImageRepository
    ) {
        $this->contactFormRepository = $contactFormRepository;
        $this->contactFormImageRepository = $contactFormImageRepository;
    }

    /**
     * お問い合わせを登録します。
     */
    public function save(StoreRequest $request): ContactForm
    {
        $model = [
            'user_name' => $request->user_name,
            'title' => $request->title,
            'email' => $request->email,
            'url' => $request->url,
            'gender' => $request->gender,
            'age' => $request->age,
            'contact' => $request->contact,
        ];

        $contactForm = $this->contactFormRepository->create(
            $model
        );

        $contactFormId = $contactForm['id'];

        // お問い合わせ画像テーブルを登録
        foreach ($request['image_files'] as $imageFile) {
            $fileName = $imageFile->getClientOriginalName();
            $this->contactFormImageRepository->create(
                [
                    'contact_form_id' => $contactFormId,
                    'file_name' => $fileName,
                ]
            );
            // s3に画像をアップロード
            $imageFile->storeAs(PhotoType::Contact->type() . '/', $fileName);
        }

        return $contactForm;
    }
}
