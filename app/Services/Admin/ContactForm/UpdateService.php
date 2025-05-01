<?php

namespace App\Services\Admin\ContactForm;

use App\Domain\Entities\ContactForm;
use App\Domain\Repositories\ContactForm\ContactFormImageRepository;
use App\Domain\Repositories\ContactForm\ContactFormRepository;
use App\Enums\PhotoType;
use App\Http\Requests\Admin\ContactForm\UpdateRequest;
use App\Services\BaseService;
use Illuminate\Support\Facades\Storage;

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
    ) {
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

        // お問い合わせ画像テーブルを登録（差分チェックして更新）
        $contactFormImages = $this->contactFormImageRepository->getByContactFormId($contactFormId);
        foreach ([$request->delete_image_1, $request->delete_image_2, $request->delete_image_3] as $i => $isDelete) {
            if ('1' !== $isDelete) {
                continue;
            }
            $this->contactFormImageRepository->delete($contactFormImages[$i]->id);
            Storage::delete(PhotoType::Contact->dirName() . '/' . $contactFormImages[$i]->file_name);
        }

        foreach ([$request->image_file_1, $request->image_file_2, $request->image_file_3] as $i => $imageFile) {
            $contactFormImage = $contactFormImages[$i] ?? null;
            if (is_null($imageFile)) {
                continue;
            }
            $newFileName = $imageFile->getClientOriginalName();

            // 変更がある場合のみ削除と再登録
            if (!$contactFormImage || $contactFormImage->file_name !== $newFileName) {
                if ($contactFormImage) {
                    $this->contactFormImageRepository->delete($contactFormImage->id);
                    Storage::delete(PhotoType::Contact->dirName() . '/' . $contactFormImage->file_name);
                }

                $this->contactFormImageRepository->create([
                    'contact_form_id' => $contactFormId,
                    'file_name' => $newFileName,
                ]);

                // s3に画像をアップロード
                $imageFile->storeAs(PhotoType::Contact->dirName() . '/', $newFileName);
            }
        }


        return $contactForm;
    }

}
