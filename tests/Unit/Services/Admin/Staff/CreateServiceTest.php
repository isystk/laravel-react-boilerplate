<?php

namespace Tests\Unit\Services\Admin\Staff;

use App\Domain\Entities\Admin;
use App\Enums\AdminRole;
use App\Http\Requests\Admin\Staff\StoreRequest;
use App\Services\Admin\Staff\CreateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateServiceTest extends TestCase
{
    use RefreshDatabase;

    private CreateService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(CreateService::class);
    }

    /**
     * saveのテスト
     */
    public function test_save(): void
    {
        $request = new StoreRequest;
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
