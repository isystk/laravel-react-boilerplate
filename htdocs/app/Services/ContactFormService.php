<?php

namespace App\Services;

use App\Domain\Entities\ContactForm;
use App\Domain\Entities\ContactFormImage;
use App\Domain\Repositories\ContactForm\ContactFormEloquentEloquentRepository;
use App\Domain\Repositories\ContactForm\ContactFormImageRepository;
use App\Domain\Repositories\ContactForm\ContactFormRepository;
use App\Enums\ErrorType;
use App\Utils\UploadImage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactFormService extends BaseService
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
     * @param int $limit
     * @return Collection|LengthAwarePaginator|array<string>
     */
    public function list(int $limit = 20): Collection|LengthAwarePaginator|array
    {
        return $this->contactFormRepository->findAll($this->request()->search, [
            'with:images' => true,
            'limit' => $limit,
        ]);
    }

    /**
     * @param int $contactFormId
     * @return object|null
     */
    public function find(int $contactFormId): object|null
    {
        return $this->contactFormRepository->getById($contactFormId);
    }

    /**
     * @param int|null $contactFormId
     * @return ContactForm
     */
    public function save(int $contactFormId = null): ContactForm
    {
        // 画像ファイルを公開ディレクトリへ配置する。
        if ($this->request()->has('imageBase64') && $this->request()->imageBase64 !== null) {
            $file = UploadImage::convertBase64($this->request()->imageBase64);
            $fileName = time() . $this->request()->fileName;

            //s3に画像をアップロード
            $file->storeAs('', $fileName);

            // $target_path = public_path('uploads/');
            // $file->move($target_path, $fileName);
        } else {
            $fileName = "";
        }

        $model = [
            'your_name' => $this->request()->input('your_name'),
            'title' => $this->request()->input('title'),
            'email' => $this->request()->input('email'),
            'url' => $this->request()->input('url'),
            'gender' => $this->request()->input('gender'),
            'age' => $this->request()->input('age'),
            'contact' => $this->request()->input('contact'),
        ];

        if ($contactFormId) {
            // 変更

            $contactForm = $this->contactFormRepository->update(
                $contactFormId,
                $model
            );

            // お問い合わせ画像テーブルを登録（Delete→Insert）
            if ($fileName !== "") {
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
            }
        } else {
            // 新規登録

            $contactForm = $this->contactFormRepository->create(
                $model
            );

            $contactFormId = $contactForm['id'];

            // お問い合わせ画像テーブルを登録（Insert）
            if ($fileName !== "") {
                $this->contactFormImageRepository->create(
                    [
                        'contact_form_id' => $contactFormId,
                        'file_name' => $fileName,
                    ]
                );
            }
        }

        return $contactForm;
    }

    /**
     * @param int $id
     */
    public function delete(int $id): void
    {
        // お問い合わせ画像テーブルを削除
        $contactFormImages = $this->contactFormImageRepository->findAll($id);
        foreach ($contactFormImages as $contactFormImage) {
            if (!$contactFormImage instanceof ContactFormImage) {
                throw new \RuntimeException('An unexpected error occurred.');
            }
            $this->contactFormImageRepository->delete($contactFormImage->id);
        }
        // お問い合わせテーブルを削除
        $this->contactFormRepository->delete($id);
    }
}
