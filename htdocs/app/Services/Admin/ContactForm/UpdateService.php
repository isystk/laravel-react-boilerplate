<?php

namespace App\Services\Admin\ContactForm;

use App\Domain\Entities\ContactForm;
use App\Domain\Entities\ContactFormImage;
use App\Domain\Repositories\ContactForm\ContactFormImageRepository;
use App\Domain\Repositories\ContactForm\ContactFormRepository;
use App\Services\BaseService;
use App\Utils\UploadImage;
use Illuminate\Http\Request;

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
        Request $request,
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
            'your_name' => $request->your_name,
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
        if (null !== $request->imageBase64) {
            $file = UploadImage::convertBase64($request->imageBase64);
            $fileName = time() . $request->fileName;

            $contactFormImages = $this->contactFormImageRepository->findAll($contactFormId);
            foreach ($contactFormImages as $contactFormImage) {
                if (!$contactFormImage instanceof ContactFormImage) {
                    throw new \RuntimeException('An unexpected error occurred.');
                }
                $this->contactFormImageRepository->delete($contactFormImage->id);
            }
            $this->contactFormImageRepository->create(
                [
                    'contact_form_id' => $contactFormId,
                    'file_name' => $fileName,
                ]
            );

            //s3に画像をアップロード
            $file->storeAs('contact/', $fileName);
        }

        return $contactForm;
    }

}
