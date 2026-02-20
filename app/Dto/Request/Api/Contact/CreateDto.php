<?php

namespace App\Dto\Request\Api\Contact;

use App\Enums\ContactType;
use App\Http\Requests\Api\Contact\StoreRequest;
use App\Utils\UploadImage;
use Illuminate\Http\UploadedFile;

class CreateDto
{
    // 件名
    public ?string $title;

    // お問い合わせ種類
    public ContactType $type;

    // お問い合わせ内容
    public string $message;

    // 画像ファイル
    public ?UploadedFile $imageFile;

    public function __construct(
        StoreRequest $request
    ) {
        $this->title   = (string) $request->input('title');
        $this->type    = ContactType::from($request->input('type'));
        $this->message = (string) $request->input('message');
        // imageBase64パラメータがあればUploadedFileオブジェクトに変換してimageFileパラメータに上書きする。
        $this->imageFile = null;
        if (!is_null($request->image_base_64)) {
            $this->imageFile = UploadImage::convertBase64($request->image_base_64);
        }
    }
}
