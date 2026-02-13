<?php

namespace App\Dto\Request\Admin\Staff;

use App\Enums\AdminRole;
use App\Http\Requests\Admin\Staff\UpdateRequest;

class UpdateDto
{
    // 名前
    public ?string $name;

    // メールアドレス
    public ?string $email;

    // 役割
    public ?AdminRole $role;

    public function __construct(
        UpdateRequest $request
    ) {
        $this->name  = $request->name;
        $this->email = $request->email;
        $this->role  = AdminRole::from($request->role);
    }
}
