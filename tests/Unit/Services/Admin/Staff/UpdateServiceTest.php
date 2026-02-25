<?php

namespace Tests\Unit\Services\Admin\Staff;

use App\Domain\Entities\Admin;
use App\Dto\Request\Admin\Staff\UpdateDto;
use App\Enums\AdminRole;
use App\Http\Requests\Admin\Staff\UpdateRequest;
use App\Services\Admin\Staff\UpdateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\BaseTest;

class UpdateServiceTest extends BaseTest
{
    use RefreshDatabase;

    private UpdateService $service;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('log');
        $actingAdmin = $this->createDefaultAdmin();
        $this->actingAs($actingAdmin, 'admin');
        $this->service = app(UpdateService::class);
    }

    public function test_update(): void
    {
        $admin1 = $this->createDefaultAdmin([
            'name'  => 'aaa',
            'email' => 'aaa@test.com',
            'role'  => AdminRole::Staff->value,
        ]);

        $request          = new UpdateRequest;
        $request['name']  = 'bbb';
        $request['email'] = 'bbb@test.com';
        $request['role']  = AdminRole::SuperAdmin->value;
        $dto              = new UpdateDto($request);
        $this->service->update($admin1->id, $dto);

        // データが更新されたことをテスト
        $updatedAdmin = Admin::find($admin1->id);
        $this->assertEquals('bbb', $updatedAdmin->name);
        $this->assertEquals('bbb@test.com', $updatedAdmin->email);
        $this->assertEquals(AdminRole::SuperAdmin, $updatedAdmin->role);
    }
}
