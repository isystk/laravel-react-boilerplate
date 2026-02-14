<?php

namespace Tests\Unit\Services\Common;

use App\Domain\Entities\Image;
use App\Enums\PhotoType;
use App\Services\Common\ImageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\BaseTest;

class ImageServiceTest extends BaseTest
{
    use RefreshDatabase;

    private ImageService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(ImageService::class);

        Storage::fake('s3');
    }

    public function test_store_画像を保存しレコードを返却すること(): void
    {
        $file     = UploadedFile::fake()->image('test.jpg');
        $type     = PhotoType::Stock;
        $fileName = 'stock_image.jpg';

        $result = $this->service->store($file, $type, $fileName);

        $this->assertInstanceOf(Image::class, $result);
        $this->assertSame($fileName, $result->file_name);

        $hash            = md5((string) $result->id);
        $hashedDirectory = substr($hash, 0, 2) . '/' . substr($hash, 2, 2) . '/' . substr($hash, 4);
        Storage::disk('s3')->assertExists("{$type->type()}/{$hashedDirectory}/{$fileName}");
    }

    public function test_update_既存の画像を削除し新しい画像を保存すること(): void
    {
        $oldFile = UploadedFile::fake()->image('old.jpg');
        $oldType = PhotoType::Stock;
        $oldName = 'old_image.jpg';
        $image   = $this->service->store($oldFile, $oldType, $oldName);

        $oldHash      = md5((string) $image->id);
        $oldHashedDir = substr($oldHash, 0, 2) . '/' . substr($oldHash, 2, 2) . '/' . substr($oldHash, 4);
        $oldPath      = "{$oldType->type()}/{$oldHashedDir}/{$oldName}";

        Storage::disk('s3')->assertExists($oldPath);

        $newFile = UploadedFile::fake()->image('new.jpg');
        $newName = 'new_image.jpg';

        $result = $this->service->update($image, $newFile, $newName);

        $this->assertInstanceOf(Image::class, $result);
        $this->assertSame($newName, $result->file_name);
        $this->assertSame($image->id, $result->id);

        // 古い画像が削除されていることを確認
        Storage::disk('s3')->assertMissing($oldPath);
        // 新しい画像が保存されていることを確認
        Storage::disk('s3')->assertExists($result->getS3Path());
    }

    public function test_delete_物理ファイルとDBレコードを削除すること(): void
    {
        $file     = UploadedFile::fake()->image('delete_target.jpg');
        $type     = PhotoType::Stock;
        $fileName = 'delete_target.jpg';
        $image    = $this->service->store($file, $type, $fileName);

        $path = $image->getS3Path();
        Storage::disk('s3')->assertExists($path);
        $this->assertDatabaseHas('images', ['id' => $image->id]);

        $this->service->delete($image);

        Storage::disk('s3')->assertMissing($path);
        $this->assertDatabaseMissing('images', ['id' => $image->id]);
    }

    public function test_delete_物理ファイルが存在しない場合でもDBレコードを削除すること(): void
    {
        $file     = UploadedFile::fake()->image('no_physical.jpg');
        $type     = PhotoType::Stock;
        $fileName = 'no_physical.jpg';
        $image    = $this->service->store($file, $type, $fileName);

        $path = $image->getS3Path();
        Storage::disk('s3')->delete($path);

        $this->service->delete($image);

        $this->assertDatabaseMissing('images', ['id' => $image->id]);
    }
}
