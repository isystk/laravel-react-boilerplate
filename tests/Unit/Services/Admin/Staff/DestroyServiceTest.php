<?php

namespace Tests\Unit\Services\Admin\Staff;

use App\Services\Admin\Staff\DestroyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DestroyServiceTest extends TestCase
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
    public function testDelete(): void
    {
        $admin = $this->createDefaultAdmin();

        $this->service->delete($admin->id);

        // データが削除されたことをテスト
        $this->assertDatabaseMissing('admins', ['id' => $admin->id]);
    }
}
