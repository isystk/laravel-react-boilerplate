<?php

namespace Tests\Feature\Http\Controllers\Admin\Photo;

use App\Enums\AdminRole;
use App\Enums\ImageType;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\BaseTest;

class ListControllerTest extends BaseTest
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_index(): void
    {
        $admin = $this->createDefaultAdmin([
            'name' => '管理者A',
            'role' => AdminRole::Manager,
        ]);
        $this->actingAs($admin, 'admin');

        // テスト用のImageレコードを作成
        $this->createDefaultImage(['file_name' => 'stock1.jpg', 'type' => ImageType::Stock->value]);
        $this->createDefaultImage(['file_name' => 'stock2.jpg', 'type' => ImageType::Stock->value]);
        $this->createDefaultImage(['file_name' => 'contact1.jpg', 'type' => ImageType::Contact->value]);

        $response = $this->get(route('admin.photo'));
        $response->assertSuccessful();
        $response->assertSeeInOrder([
            'contact1.jpg',
            'stock2.jpg',
            'stock1.jpg',
        ]);
    }

    public function test_index_管理者ロール別アクセス権限検証(): void
    {
        $cases = [
            ['role' => AdminRole::HighManager, 'status' => 200],
            ['role' => AdminRole::Manager,     'status' => 200],
        ];

        foreach ($cases as $case) {
            $admin = $this->createDefaultAdmin([
                'role' => $case['role']->value,
            ]);

            $this->actingAs($admin, 'admin')
                ->get(route('admin.photo'))
                ->assertStatus($case['status']);
        }
    }

    public function test_destroy(): void
    {
        Storage::fake('s3');
        $storage = Storage::disk('s3');

        $admin = $this->createDefaultAdmin([
            'name' => '管理者A',
            'role' => AdminRole::Manager,
        ]);
        $this->actingAs($admin, 'admin');

        // テスト用のImageレコードを作成
        $image1 = $this->createDefaultImage(['file_name' => 'contact1.jpg', 'type' => ImageType::Contact->value]);
        $image2 = $this->createDefaultImage(['file_name' => 'stock1.jpg', 'type' => ImageType::Stock->value]);

        // S3にファイルを配置
        $storage->put($image1->getS3Path(), 'dummy');
        $storage->put($image2->getS3Path(), 'dummy');

        // manager権限ではアクセスできないことのテスト
        $response = $this->delete(route('admin.photo.destroy'), []);
        $response->assertForbidden();

        $admin2 = $this->createDefaultAdmin([
            'name' => '管理者2',
            'role' => AdminRole::HighManager,
        ]);
        $this->actingAs($admin2, 'admin');

        $redirectResponse = $this->delete(route('admin.photo.destroy'), [
            'imageId' => $image1->id,
        ]);
        $response = $this->get($redirectResponse->headers->get('Location'));
        $response->assertSuccessful();
        // S3ファイルが削除されたことを確認
        $this->assertFalse($storage->exists($image1->getS3Path()));
        // DBレコードが削除されたことを確認
        $this->assertDatabaseMissing('images', ['id' => $image1->id]);

        $redirectResponse = $this->delete(route('admin.photo.destroy'), [
            'imageId' => $image2->id,
        ]);
        $response = $this->get($redirectResponse->headers->get('Location'));
        $response->assertSuccessful();
        // S3ファイルが削除されたことを確認
        $this->assertFalse($storage->exists($image2->getS3Path()));
        // DBレコードが削除されたことを確認
        $this->assertDatabaseMissing('images', ['id' => $image2->id]);
    }
}
