<?php

namespace Tests\Unit\Services\Admin\Staff;

use App\Domain\Entities\Admin;
use App\Services\Admin\Staff\DestroyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use tests\TestCase;

class DestroyServiceTest extends TestCase
{

    use RefreshDatabase;

    private DestroyService $service;

    /**
     * 各テストの実行前に起動する。
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(DestroyService::class);
    }

    /**
     * インスタンスがテスト対象のクラスであることのテスト
     */
    public function testInstanceOf(): void
    {
        $this->assertInstanceOf(DestroyService::class, $this->service);
    }

    /**
     * deleteのテスト
     */
    public function testDelete(): void
    {
        /** @var Admin $admin */
        $admin = Admin::factory()->create();

        $this->service->delete($admin->id);

        // データが削除されたことをテスト
        $this->assertDatabaseMissing('admins', ['id' => $admin->id]);
    }
}
