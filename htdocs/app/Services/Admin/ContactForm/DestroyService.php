<?php

namespace App\Services\Admin\ContactForm;

use App\Domain\Entities\ContactFormImage;
use App\Domain\Repositories\ContactForm\ContactFormImageRepository;
use App\Domain\Repositories\ContactForm\ContactFormRepository;
use App\Services\BaseService;
use Illuminate\Http\Request;

class DestroyService extends BaseService
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
     */
    public function delete(int $contactFormId): void
    {
        // お問い合わせ画像テーブルを削除
        $contactFormImages = $this->contactFormImageRepository->getByContactFormId($contactFormId);
        foreach ($contactFormImages as $contactFormImage) {
            if (!$contactFormImage instanceof ContactFormImage) {
                throw new \RuntimeException('An unexpected error occurred.');
            }
            $this->contactFormImageRepository->delete($contactFormImage->id);
        }
        // お問い合わせテーブルを削除
        $this->contactFormRepository->delete($contactFormId);
    }
}
