<?php

namespace Tests\Unit\Services\Admin\Photo;

use App\Enums\PhotoType;
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

        // テスト用のImageレコードを作成
        $image = $this->createDefaultImage(['file_name' => 'test.jpg', 'type' => PhotoType::Stock->value]);

        // S3にファイルを配置
        $s3Path = $image->getS3Path();
        $storage->put($s3Path, 'dummy');

        $this->service->delete($image->id);

        // S3ファイルが削除されたことを確認
        $this->assertFalse($storage->exists($s3Path));

        // DBレコードが削除されたことを確認
        $this->assertDatabaseMissing('images', ['id' => $image->id]);
    }
}
