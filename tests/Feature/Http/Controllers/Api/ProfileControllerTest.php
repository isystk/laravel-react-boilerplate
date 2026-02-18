<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Domain\Entities\Image;
use App\Enums\ImageType;
use App\Services\Common\ImageService;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Mockery;
use Tests\BaseTest;

class ProfileControllerTest extends BaseTest
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_update_名前のみ更新する場合(): void
    {
        $user = $this->createDefaultUser([
            'name'  => '更新前',
            'email' => 'test@test.com',
        ]);
        $this->actingAs($user);

        $response = $this->postJson(route('api.profile.update'), [
            'name' => '更新後',
        ]);

        $response->assertOk();
        $response->assertJson([
            'name' => '更新前',
        ]);

        $user->refresh();
        $this->assertSame('更新後', $user->name);
    }

    public function test_update_アバター画像を更新する場合(): void
    {
        $user = $this->createDefaultUser([
            'name'       => 'テストユーザー',
            'avatar_url' => 'https://example.com/old.png',
        ]);
        $this->actingAs($user);

        $mockImage = new Image;
        $mockImage->forceFill([
            'id'        => 99,
            'file_name' => 'avatar.png',
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

        $response = $this->postJson(route('api.profile.update'), [
            'name'   => 'テストユーザー',
            'avatar' => $base64,
        ]);

        $response->assertOk();

        $user->refresh();
        $this->assertSame(99, $user->avatar_image_id);
        $this->assertNull($user->avatar_url);
    }

    public function test_update_バリデーションエラー_名前が空の場合(): void
    {
        $user = $this->createDefaultUser();
        $this->actingAs($user);

        $response = $this->postJson(route('api.profile.update'), [
            'name' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    public function test_update_未認証の場合は401が返却されること(): void
    {
        $response = $this->postJson(route('api.profile.update'), [
            'name' => 'テスト',
        ]);

        $response->assertStatus(401);
    }

    public function test_destroy_アカウントを削除できること(): void
    {
        $user = $this->createDefaultUser();
        $this->actingAs($user);

        $response = $this->postJson(route('api.profile.destroy'));

        $response->assertOk();
        $response->assertJson(['result' => true]);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
