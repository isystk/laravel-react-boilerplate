<?php

namespace Tests\Unit\Utils;

use App\Utils\UploadImage;
use Illuminate\Http\UploadedFile;
use Tests\BaseTest;

class UploadImageTest extends BaseTest
{
    public function test_convertBase64(): void
    {
        // 赤い1x1ピクセルのJPG画像のbase64
        $base64 = 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAgGBgcGBQgHBwcJCQgKDBQNDAsLDBkSEw8UHRofHh0aHBwgJC4nICIsIxwcKDcpLDAxNDQ0Hyc5PTgyPC4zNDL/2wBDAQkJCQwLDBgNDRgyIRwhMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjL/wAARCAABAAEDAREAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAf/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFAEBAAAAAAAAAAAAAAAAAAAAAP/EABQRAQAAAAAAAAAAAAAAAAAAAAH/2gAMAwEAAhEAPwCfAQA/2Q==';

        $result = UploadImage::convertBase64($base64);

        $this->assertInstanceOf(UploadedFile::class, $result);
        $this->assertSame('image/jpeg', $result->getMimeType());
        $this->assertGreaterThan(0, $result->getSize());

        // ファイル名が img_ で始まっていることを確認 (tempnam の接頭辞)
        $this->assertStringStartsWith('img_', $result->getFilename());
    }

    public function test_convertBase64_without_header(): void
    {
        // ヘッダーなしのbase64
        $base64Data = '/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAgGBgcGBQgHBwcJCQgKDBQNDAsLDBkSEw8UHRofHh0aHBwgJC4nICIsIxwcKDcpLDAxNDQ0Hyc5PTgyPC4zNDL/2wBDAQkJCQwLDBgNDRgyIRwhMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjL/wAARCAABAAEDAREAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAf/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFAEBAAAAAAAAAAAAAAAAAAAAAP/EABQRAQAAAAAAAAAAAAAAAAAAAAH/2gAMAwEAAhEAPwCfAQA/2Q==';

        $result = UploadImage::convertBase64($base64Data);

        $this->assertInstanceOf(UploadedFile::class, $result);
        $this->assertSame('image/jpeg', $result->getMimeType());
    }
}
