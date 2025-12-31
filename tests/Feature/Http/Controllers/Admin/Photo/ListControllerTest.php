<?php

namespace Http\Controllers\Admin\Photo;

use App\Enums\AdminRole;
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
        Storage::fake('s3');
        $storage = Storage::disk('s3');

        $admin = $this->createDefaultAdmin([
            'name' => '管理者A',
            'role' => AdminRole::Manager,
        ]);
        $this->actingAs($admin, 'admin');

        // テスト用のファイルを作成
        $storage->put('contact\contact1.jpg', 'dummy');
        $storage->put('stock\stock1.jpg', 'dummy');
        $storage->put('stock\stock2.jpg', 'dummy');

        $response = $this->get(route('admin.photo'));
        $response->assertSuccessful();
        $response->assertSeeInOrder([
            'stock/stock1.jpg',
            'stock/stock2.jpg',
            'contact/contact1.jpg',
        ]);
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

        // テスト用のファイルを作成
        $storage->put('contact\contact1.jpg', 'dummy');
        $storage->put('stock\stock1.jpg', 'dummy');

        // manager権限ではアクセスできないことのテスト
        $response = $this->delete(route('admin.photo.destroy'), []);
        $response->assertForbidden();

        $admin2 = $this->createDefaultAdmin([
            'name' => '管理者2',
            'role' => AdminRole::HighManager,
        ]);
        $this->actingAs($admin2, 'admin');

        $redirectResponse = $this->delete(route('admin.photo.destroy'), [
            'fileName' => 'contact\contact1.jpg',
        ]);
        $response = $this->get($redirectResponse->headers->get('Location'));
        $response->assertSuccessful();
        // ファイルが削除されたことを確認
        $this->assertFalse($storage->exists('contact\contact1.jpg'));

        $redirectResponse = $this->delete(route('admin.photo.destroy'), [
            'fileName' => 'stock\stock1.jpg',
        ]);
        $response = $this->get($redirectResponse->headers->get('Location'));
        $response->assertSuccessful();
        // ファイルが削除されたことを確認
        $this->assertFalse($storage->exists('stock\stock1.jpg'));
    }
}
