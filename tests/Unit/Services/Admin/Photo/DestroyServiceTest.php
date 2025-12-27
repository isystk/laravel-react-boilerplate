<?php

namespace Tests\Unit\Services\Admin\Photo;

use App\Services\Admin\Photo\DestroyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
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
        Storage::fake('s3');
        $storage = Storage::disk('s3');

        // テスト用のファイルを作成
        $filePath = 'stock\test.jpg';
        $storage->put($filePath, 'dummy');

        $this->service->delete($filePath);

        // ファイルが削除されたかを確認
        $this->assertFalse($storage->exists($filePath));
    }
}
