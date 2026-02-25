<?php

namespace Tests\Unit\Dto\Request\Admin\Staff;

use App\Dto\Request\Admin\Staff\UpdateDto;
use App\Enums\AdminRole;
use App\Http\Requests\Admin\Staff\UpdateRequest;
use Tests\BaseTest;

class UpdateDtoTest extends BaseTest
{
    public function test_construct_リクエストから各プロパティが正しく設定されること(): void
    {
        $request = UpdateRequest::create('/', 'POST', [
            'name'  => '更新管理者',
            'email' => 'updated@example.com',
            'role'  => AdminRole::Staff->value,
        ]);

        $dto = new UpdateDto($request);

        $this->assertSame('更新管理者', $dto->name);
        $this->assertSame('updated@example.com', $dto->email);
        $this->assertSame(AdminRole::Staff, $dto->role);
    }

    public function test_construct_roleがSuperAdminの場合正しく設定されること(): void
    {
        $request = UpdateRequest::create('/', 'POST', [
            'name'  => 'テスト',
            'email' => 'test@example.com',
            'role'  => AdminRole::SuperAdmin->value,
        ]);

        $dto = new UpdateDto($request);

        $this->assertSame(AdminRole::SuperAdmin, $dto->role);
    }
}
