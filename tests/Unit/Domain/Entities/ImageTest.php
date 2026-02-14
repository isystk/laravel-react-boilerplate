<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\Image;
use App\Enums\PhotoType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\BaseTest;

class ImageTest extends BaseTest
{
    use RefreshDatabase;

    private Image $sub;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sub = new Image;
    }

    public function test_正しくキャストされる事(): void
    {
        $model = $this->createDefaultImage();

        $this->assertInstanceOf(PhotoType::class, $model->type);
        $this->assertInstanceOf(Carbon::class, $model->created_at);
        $this->assertInstanceOf(Carbon::class, $model->updated_at);
    }

    public function test_getS3Path_正しいパスを返却する事(): void
    {
        $model = $this->createDefaultImage([
            'file_name' => 'test.jpg',
            'type'      => PhotoType::Stock,
        ]);

        $hash            = md5((string) $model->id);
        $hashedDirectory = substr($hash, 0, 2) . '/' . substr($hash, 2, 2) . '/' . substr($hash, 4);
        $expected        = PhotoType::Stock->type() . '/' . $hashedDirectory . '/test.jpg';
        $this->assertSame($expected, $model->getS3Path());
    }

    public function test_getHashedDirectory_正しいハッシュパスを返却する事(): void
    {
        $model = $this->createDefaultImage();

        $hash     = md5((string) $model->id);
        $expected = substr($hash, 0, 2) . '/' . substr($hash, 2, 2) . '/' . substr($hash, 4);
        $this->assertSame($expected, $model->getHashedDirectory());
    }

    public function test_getImageUrl_正しいURLを返却する事(): void
    {
        $configAppUrl = config('app.url');

        $model = $this->createDefaultImage([
            'file_name' => 'test.jpg',
            'type'      => PhotoType::Stock,
        ]);

        $hash            = md5((string) $model->id);
        $hashedDirectory = substr($hash, 0, 2) . '/' . substr($hash, 2, 2) . '/' . substr($hash, 4);
        $expected        = $configAppUrl . '/uploads/' . PhotoType::Stock->type() . '/' . $hashedDirectory . '/test.jpg';
        $this->assertSame($expected, $model->getImageUrl());
    }

    public function test_fillable_属性が正しく設定されている事(): void
    {
        $fillable = ['file_name', 'type'];
        $this->assertSame($fillable, $this->sub->getFillable());
    }
}
