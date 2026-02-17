<?php

namespace App\Dto\Request\Admin\User;

use App\Http\Requests\Admin\User\UpdateRequest;
use Illuminate\Http\UploadedFile;

class UpdateDto
{
    // 名前
    public ?string $name;

    // メールアドレス
    public ?string $email;

    // アップロードされたアバター画像
    public ?UploadedFile $avatar;

    public function __construct(
        UpdateRequest $request
    ) {
        $this->name   = (string) $request->input('name');
        $this->email  = (string) $request->input('email');
        $this->avatar = $request->file('avatar');
    }
}
