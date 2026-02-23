<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Domain\Entities\Image;
use App\Services\Api\Profile\DestroyService;
use App\Services\Api\Profile\UpdateService;
use Exception;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
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
            'name' => '更新後',
        ]);
    }

    public function test_update_アバター画像を更新する場合(): void
    {
        Storage::fake('s3');
        $user = $this->createDefaultUser([
            'name'       => 'テストユーザー',
            'avatar_url' => 'https://example.com/old.png',
        ]);
        $this->actingAs($user);

        // 1x1 pixel PNG base64
        $base64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

        $response = $this->postJson(route('api.profile.update'), [
            'name'   => 'テストユーザー',
            'avatar' => $base64,
        ]);

        $response->assertOk();

        $user->refresh();
        $image = Image::first();
        $this->assertSame($image->id, $user->avatar_image_id);
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

        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }

    public function test_update_error(): void
    {
        $user = $this->createDefaultUser();
        $this->actingAs($user);

        $this->mock(UpdateService::class, function ($mock) {
            $mock->shouldReceive('update')->andThrow(new Exception('Update error'));
        });

        $response = $this->postJson(route('api.profile.update'), [
            'name' => 'テスト',
        ]);

        $response->assertStatus(500);
        $response->assertJson([
            'result' => false,
            'error'  => ['messages' => ['Update error']],
        ]);
    }

    public function test_destroy_error(): void
    {
        $user = $this->createDefaultUser();
        $this->actingAs($user);

        $this->mock(DestroyService::class, function ($mock) {
            $mock->shouldReceive('destroy')->andThrow(new Exception('Destroy error'));
        });

        $response = $this->postJson(route('api.profile.destroy'));

        $response->assertStatus(500);
        $response->assertJson([
            'result' => false,
            'error'  => ['messages' => ['Destroy error']],
        ]);
    }
}
