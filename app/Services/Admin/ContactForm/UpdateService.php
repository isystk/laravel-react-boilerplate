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
    /**
     * @var ContactFormRepository
     */
    protected ContactFormRepository $contactFormRepository;

    /**
     * @var ContactFormImageRepository
     */
    protected ContactFormImageRepository $contactFormImageRepository;

    public function __construct(
        UpdateRequest $request,
        ContactFormRepository $contactFormRepository,
        ContactFormImageRepository $contactFormImageRepository
    )
    {
        parent::__construct($request);
        $this->contactFormRepository = $contactFormRepository;
        $this->contactFormImageRepository = $contactFormImageRepository;
    }

    /**
     * @param int $contactFormId
     * @return ContactForm
     */
    public function update(int $contactFormId): ContactForm
    {
        $request = $this->request();
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
