<?php

namespace Tests\Unit\Services\Api\Profile;

use App\Domain\Entities\Image;
use App\Dto\Response\Api\Profile\UpdateDto;
use App\Services\Api\Profile\UpdateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\BaseTest;

class UpdateServiceTest extends BaseTest
{
    use RefreshDatabase;

    private UpdateService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(UpdateService::class);
    }

    public function test_update_名前のみ更新する場合(): void
    {
        $user = $this->createDefaultUser([
            'name'       => '更新前',
            'avatar_url' => 'https://example.com/old-avatar.png',
        ]);

        $dto = $this->service->update($user, '更新後', null);

        $this->assertInstanceOf(UpdateDto::class, $dto);
        $this->assertSame('更新後', $dto->name, 'DTOにはupdate呼び出し前のuser->nameが入る');
        $this->assertSame('https://example.com/old-avatar.png', $dto->avatarUrl);
    }

    public function test_update_アバター画像を更新する場合(): void
    {
        Storage::fake('s3');
        $user = $this->createDefaultUser([
            'name'            => 'テストユーザー',
            'avatar_url'      => 'https://example.com/old-avatar.png',
            'avatar_image_id' => null,
        ]);

        // 1x1 pixel PNG base64
        $base64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';
        $dto    = $this->service->update($user, 'テストユーザー', $base64);

        $this->assertInstanceOf(UpdateDto::class, $dto);
        $image = Image::first();
        $this->assertSame($image->getImageUrl(), $dto->avatarUrl);
    }
}
