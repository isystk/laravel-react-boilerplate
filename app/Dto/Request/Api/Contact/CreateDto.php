<?php

namespace App\Dto\Request\Api\Contact;

use App\Enums\Age;
use App\Enums\Gender;
use App\Http\Requests\Api\Contact\StoreRequest;
use App\Utils\UploadImage;
use Illuminate\Http\UploadedFile;

class CreateDto
{
    // ユーザー名
    public ?string $userName;

    // 件名
    public ?string $title;

    // メールアドレス
    public ?string $email;

    // URL
    public ?string $url;

    // 性別
    public Gender $gender;

    // 年齢
    public Age $age;

    // お問い合わせ内容
    public ?string $contact;

    // 画像ファイル
    public ?UploadedFile $imageFile;

    public function __construct(
        StoreRequest $request
    ) {
        $this->userName = (string) $request->input('user_name');
        $this->title    = (string) $request->input('title');
        $this->email    = (string) $request->input('email');
        $this->url      = (string) $request->input('url');
        $this->gender   = Gender::from($request->input('gender'));
        $this->age      = Age::from($request->input('age'));
        $this->contact  = (string) $request->input('contact');
        // imageBase64パラメータがあればUploadedFileオブジェクトに変換してimageFileパラメータに上書きする。
        $this->imageFile = null;
        if ($request->has('image_base_64')) {
            $this->imageFile = UploadImage::convertBase64($request->image_base_64);
        }
    }
}
