<?php

namespace Tests\Unit\Dto\Request\Admin\Staff;

use App\Dto\Request\Admin\Staff\CreateDto;
use App\Enums\AdminRole;
use App\Http\Requests\Admin\Staff\StoreRequest;
use Illuminate\Support\Facades\Hash;
use Tests\BaseTest;

class CreateDtoTest extends BaseTest
{
    public function test_construct_リクエストから各プロパティが正しく設定されること(): void
    {
        $request = StoreRequest::create('/', 'POST', [
            'name'     => 'テスト管理者',
            'email'    => 'staff@example.com',
            'password' => 'password123',
        ]);

        $dto = new CreateDto($request);

        $this->assertSame('テスト管理者', $dto->name);
        $this->assertSame('staff@example.com', $dto->email);
        $this->assertTrue(Hash::check('password123', $dto->password));
        $this->assertSame(AdminRole::Staff, $dto->role);
    }

    public function test_construct_パスワードがハッシュ化されること(): void
    {
        $request = StoreRequest::create('/', 'POST', [
            'name'     => 'テスト',
            'email'    => 'test@example.com',
            'password' => 'secret',
        ]);

        $dto = new CreateDto($request);

        $this->assertNotSame('secret', $dto->password);
        $this->assertTrue(Hash::check('secret', $dto->password));
    }

    public function test_construct_roleが常にManagerに設定されること(): void
    {
        $request = StoreRequest::create('/', 'POST', [
            'name'     => 'テスト',
            'email'    => 'test@example.com',
            'password' => 'password',
        ]);

        $dto = new CreateDto($request);

        $this->assertSame(AdminRole::Staff, $dto->role);
    }
}
