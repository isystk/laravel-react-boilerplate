<?php

namespace Tests\Unit\Services\Admin\Staff;

use App\Services\Admin\Staff\DestroyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class DestroyServiceTest extends BaseTest
{
    use RefreshDatabase;

    private DestroyService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(DestroyService::class);
    }

    /**
     * deleteのテスト
     */
    public function test_delete(): void
    {
        $admin = $this->createDefaultAdmin();

        $this->service->delete($admin->id);

        // データが削除されたことをテスト
        $this->assertDatabaseMissing('admins', ['id' => $admin->id]);
    }
}
