<?php

namespace Tests\Unit\Services\Admin\Staff;

use App\Domain\Entities\Admin;
use App\Enums\AdminRole;
use App\Services\Admin\Staff\CreateService;
use App\Http\Requests\Admin\Staff\StoreRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CreateServiceTest extends TestCase
{

    use RefreshDatabase;

    private CreateService $service;

    /**
     * 各テストの実行前に起動する。
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(CreateService::class);
    }

    /**
     * インスタンスがテスト対象のクラスであることのテスト
     */
    public function testInstanceOf(): void
    {
        $this->assertInstanceOf(CreateService::class, $this->service);
    }

    /**
     * saveのテスト
     */
    public function testSave(): void
    {
        $request = new StoreRequest();
        $request['name'] = 'aaa';
        $request['email'] = 'aaa@test.com';
        $request['password'] = 'password';
        $admin = $this->service->save($request);

        // データが登録されたことをテスト
        $createdAdmin = Admin::find($admin->id);
        $this->assertEquals('aaa', $createdAdmin->name);
        $this->assertEquals('aaa@test.com', $createdAdmin->email);
//        $this->assertEquals(Hash::make('password'), $createdAdmin->password); // TODO ハッシュ値が常に変わるためテストできない
        $this->assertEquals(AdminRole::Manager->value, $createdAdmin->role);
    }
}
