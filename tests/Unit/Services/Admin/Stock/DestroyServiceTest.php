<?php

namespace Tests\Unit\Services\Admin\Stock;

use App\Domain\Entities\Stock;
use App\Services\Admin\Stock\DestroyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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
        /** @var Stock $stock */
        $stock = Stock::factory()->create();

        $this->service->delete($stock->id);

        // データが削除されたことをテスト
        $this->assertDatabaseMissing('stocks', ['id' => $stock->id]);
    }
}
