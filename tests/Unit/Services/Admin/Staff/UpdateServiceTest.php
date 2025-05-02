<?php

namespace Tests\Unit\Services\Admin\Staff;

use App\Domain\Entities\Admin;
use App\Enums\AdminRole;
use App\Http\Requests\Admin\Staff\UpdateRequest;
use App\Services\Admin\Staff\UpdateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateServiceTest extends TestCase
{

    use RefreshDatabase;

    private UpdateService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(UpdateService::class);
    }

    /**
     * updateのテスト
     */
    public function testUpdate(): void
    {
        $admin1 = $this->createDefaultAdmin([
            'name' => 'aaa',
            'email' => 'aaa@test.com',
            'role' => AdminRole::Manager->value,
        ]);

        $request = new UpdateRequest();
        $request['name'] = 'bbb';
        $request['email'] = 'bbb@test.com';
        $request['role'] = AdminRole::HighManager->value;
        $this->service->update($admin1->id, $request);

        // データが更新されたことをテスト
        $updatedAdmin = Admin::find($admin1->id);
        $this->assertEquals('bbb', $updatedAdmin->name);
        $this->assertEquals('bbb@test.com', $updatedAdmin->email);
        $this->assertEquals(AdminRole::HighManager->value, $updatedAdmin->role);
    }
}
