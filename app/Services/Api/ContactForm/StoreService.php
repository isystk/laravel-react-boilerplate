<?php

namespace App\Services\Api\ContactForm;

use App\Domain\Entities\ContactForm;
use App\Domain\Entities\ContactFormImage;
use App\Domain\Repositories\ContactForm\ContactFormImageRepository;
use App\Domain\Repositories\ContactForm\ContactFormRepository;
use App\Enums\PhotoType;
use App\Http\Requests\Api\ContactForm\StoreRequest;
use App\Services\BaseService;
use App\Utils\UploadImage;
use Illuminate\Http\Request;

class StoreService extends BaseService
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
        StoreRequest $storeRequest,
        ContactFormRepository $contactFormRepository,
        ContactFormImageRepository $contactFormImageRepository
    )
    {
        parent::__construct($storeRequest);
        $this->contactFormRepository = $contactFormRepository;
        $this->contactFormImageRepository = $contactFormImageRepository;
    }

    /**
     * @return ContactForm
     */
    public function save(): ContactForm
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
            //s3に画像をアップロード
            $imageFile->storeAs(PhotoType::Contact->dirName() . '/', $fileName);
        }

        return $contactForm;
    }

}
