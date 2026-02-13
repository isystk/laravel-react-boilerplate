<?php

namespace App\Dto\Request\Admin\ContactForm;

use App\Enums\Age;
use App\Enums\Gender;
use App\Http\Requests\Admin\ContactForm\UpdateRequest;
use App\Utils\UploadImage;
use Illuminate\Http\UploadedFile;

class UpdateDto
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

    // 画像ファイル名
    public ?string $imageFileName;

    // 画像ファイル
    public ?UploadedFile $imageFile;

    public function __construct(
        UpdateRequest $request
    ) {
        $this->userName      = (string) $request->input('user_name');
        $this->title         = (string) $request->input('title');
        $this->email         = (string) $request->input('email');
        $this->url           = (string) $request->input('url');
        $this->gender        = Gender::from($request->input('gender'));
        $this->age           = Age::from($request->input('age'));
        $this->contact       = (string) $request->input('contact');
        $this->imageFileName = (string) $request->input('image_file_name');
        $this->imageFile     = null;
        if ($request->has('image_base_64') && $request->image_base_64 !== null) {
            $this->imageFile = UploadImage::convertBase64($request->image_base_64);
        }
    }
}
