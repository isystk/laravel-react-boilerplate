<?php

namespace App\Dto\Request\Admin\Staff;

use App\Enums\AdminRole;
use App\Http\Requests\Admin\Staff\StoreRequest;
use Illuminate\Support\Facades\Hash;

class CreateDto
{
    // 名前
    public ?string $name;

    // メールアドレス
    public ?string $email;

    // パスワード
    public ?string $password;

    // 役割
    public ?AdminRole $role;

    public function __construct(
        StoreRequest $request
    ) {
        $this->name     = $request->name;
        $this->email    = $request->email;
        $this->password = Hash::make($request->password);
        $this->role     = AdminRole::Staff;
    }
}
