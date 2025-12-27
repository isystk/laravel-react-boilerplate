<?php

namespace Tests\Unit\Services\Admin\Stock;

use App\Services\Admin\Stock\DestroyService;
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

    public function test_delete(): void
    {
        $stock = $this->createDefaultStock();
        $this->service->delete($stock->id);

        // データが削除されたことをテスト
        $this->assertDatabaseMissing('stocks', ['id' => $stock->id]);
    }
}
