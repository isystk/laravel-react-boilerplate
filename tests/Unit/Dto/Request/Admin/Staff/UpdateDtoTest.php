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
            'role'  => 'manager',
        ]);

        $dto = new UpdateDto($request);

        $this->assertSame('更新管理者', $dto->name);
        $this->assertSame('updated@example.com', $dto->email);
        $this->assertSame(AdminRole::Manager, $dto->role);
    }

    public function test_construct_roleがHighManagerの場合正しく設定されること(): void
    {
        $request = UpdateRequest::create('/', 'POST', [
            'name'  => 'テスト',
            'email' => 'test@example.com',
            'role'  => 'high-manager',
        ]);

        $dto = new UpdateDto($request);

        $this->assertSame(AdminRole::HighManager, $dto->role);
    }
}
