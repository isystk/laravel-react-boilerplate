<?php

namespace Tests\Unit\Services\Admin\Photo;

use App\Services\Admin\Photo\DestroyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
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
        // テスト用のファイルを作成
        $filePath = 'stock\test.jpg';
        Storage::put($filePath, '');

        $this->service->delete($filePath);

        // ファイルが削除されたかを確認
        $this->assertFileDoesNotExist(storage_path('app/' . $filePath));
    }
}
