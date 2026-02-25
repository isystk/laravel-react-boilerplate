<?php

namespace Tests\Unit\Services\Admin\Staff;

use App\Domain\Entities\Admin;
use App\Dto\Request\Admin\Staff\CreateDto;
use App\Enums\AdminRole;
use App\Http\Requests\Admin\Staff\StoreRequest;
use App\Services\Admin\Staff\CreateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\BaseTest;

class CreateServiceTest extends BaseTest
{
    use RefreshDatabase;

    private CreateService $service;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('log');
        $actingAdmin = $this->createDefaultAdmin();
        $this->actingAs($actingAdmin, 'admin');
        $this->service = app(CreateService::class);
    }

    public function test_save(): void
    {
        $request             = new StoreRequest;
        $request['name']     = 'aaa';
        $request['email']    = 'aaa@test.com';
        $request['password'] = 'password';
        $dto                 = new CreateDto($request);
        $admin               = $this->service->save($dto);

        // データが登録されたことをテスト
        $createdAdmin = Admin::find($admin->id);
        $this->assertEquals('aaa', $createdAdmin->name);
        $this->assertEquals('aaa@test.com', $createdAdmin->email);
        //        $this->assertEquals(Hash::make('password'), $createdAdmin->password); // TODO ハッシュ値が常に変わるためテストできない
        $this->assertEquals(AdminRole::Staff, $createdAdmin->role);
    }
}
