<?php

namespace App\Services\Admin\ContactForm;

use App\Domain\Entities\ContactForm;
use App\Domain\Entities\ContactFormImage;
use App\Domain\Repositories\ContactForm\ContactFormImageRepository;
use App\Domain\Repositories\ContactForm\ContactFormRepository;
use App\Enums\PhotoType;
use App\Http\Requests\Admin\ContactForm\UpdateRequest;
use App\Services\BaseService;

class UpdateService extends BaseService
{
    private ContactFormRepository $contactFormRepository;
    private ContactFormImageRepository $contactFormImageRepository;

    /**
     * Create a new controller instance.
     *
     * @param ContactFormRepository $contactFormRepository
     * @param ContactFormImageRepository $contactFormImageRepository
     */
    public function __construct(
        ContactFormRepository $contactFormRepository,
        ContactFormImageRepository $contactFormImageRepository
    )
    {
        $this->contactFormRepository = $contactFormRepository;
        $this->contactFormImageRepository = $contactFormImageRepository;
    }

    /**
     * お問い合わせを更新します。
     * @param int $contactFormId
     * @param UpdateRequest $request
     * @return ContactForm
     */
    public function update(int $contactFormId, UpdateRequest $request): ContactForm
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

        $contactForm = $this->contactFormRepository->update(
            $contactFormId,
            $model
        );

        // お問い合わせ画像テーブルを登録（Delete→Insert）
        $contactFormImages = $this->contactFormImageRepository->getByContactFormId($contactFormId)->all();
        foreach ($contactFormImages as $contactFormImage) {
            if (!$contactFormImage instanceof ContactFormImage) {
                throw new \RuntimeException('An unexpected error occurred.');
            }
            $this->contactFormImageRepository->delete($contactFormImage->id);
        }
        foreach ($request['image_files'] as $i => $imageFile) {
            $fileName = $imageFile->getClientOriginalName();
            $this->contactFormImageRepository->create(
                [
                    'contact_form_id' => $contactFormId,
                    'file_name' => $fileName,
                ]
            );
            //s3に画像をアップロード
            $imageFile->storeAs(PhotoType::Contact->dirName() . '/', $fileName);
        }

        return $contactForm;
    }

}
