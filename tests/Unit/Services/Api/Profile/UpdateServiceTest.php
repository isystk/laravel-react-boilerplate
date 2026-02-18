<?php

namespace Tests\Unit\Services\Api\Profile;

use App\Domain\Entities\Image;
use App\Dto\Response\Api\Profile\UpdateDto;
use App\Enums\ImageType;
use App\Services\Api\Profile\UpdateService;
use App\Services\Common\ImageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Mockery;
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
        $this->assertSame('更新前', $dto->name, 'DTOにはupdate呼び出し前のuser->nameが入る');
        $this->assertSame('https://example.com/old-avatar.png', $dto->avatarUrl);

        $user->refresh();
        $this->assertSame('更新後', $user->name);
    }

    public function test_update_アバター画像を更新する場合(): void
    {
        $user = $this->createDefaultUser([
            'name'       => 'テストユーザー',
            'avatar_url' => 'https://example.com/old-avatar.png',
        ]);

        // ImageServiceをモック
        $mockImage     = new Image;
        $mockImage->id = 99;
        $mockImage->forceFill([
            'id'        => 99,
            'file_name' => 'test.png',
            'type'      => ImageType::User,
        ]);

        $imageService = Mockery::mock(ImageService::class);
        $imageService->shouldReceive('store')
            ->once()
            ->withArgs(fn (UploadedFile $file, ImageType $type, string $fileName) => $type === ImageType::User)
            ->andReturn($mockImage);

        $this->app->instance(ImageService::class, $imageService);

        // 1x1 pixel PNG base64
        $base64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';
        $dto    = $this->service->update($user, 'テストユーザー', $base64);

        $this->assertInstanceOf(UpdateDto::class, $dto);

        $user->refresh();
        $this->assertSame(99, $user->avatar_image_id);
        $this->assertNull($user->avatar_url, 'アバター画像が設定された場合、avatar_urlはnullになる');
    }
}
