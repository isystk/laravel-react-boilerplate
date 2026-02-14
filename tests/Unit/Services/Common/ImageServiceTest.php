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
        $file = UploadedFile::fake()->image('test.jpg');
        $type = PhotoType::Stock;
        $fileName = 'stock_image.jpg';

        $result = $this->service->store($file, $type, $fileName);

        $this->assertInstanceOf(Image::class, $result);
        $this->assertSame($fileName, $result->file_name);

        Storage::disk('s3')->assertExists("{$type->type()}/{$fileName}");
    }
}
