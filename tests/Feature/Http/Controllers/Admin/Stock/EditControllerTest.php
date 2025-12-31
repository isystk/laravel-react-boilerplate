<?php

namespace Http\Controllers\Admin\Stock;

use App\Enums\AdminRole;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\BaseTest;

class EditControllerTest extends BaseTest
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_edit(): void
    {
        $admin1 = $this->createDefaultAdmin([
            'name' => '管理者A',
            'role' => AdminRole::Manager,
        ]);
        $this->actingAs($admin1, 'admin');

        $stock = $this->createDefaultStock([
            'name' => 'aaa',
        ]);

        // manager権限ではアクセスできないことのテスト
        $response = $this->get(route('admin.stock.edit', $stock));
        $response->assertForbidden();

        $admin2 = $this->createDefaultAdmin([
            'name' => '管理者2',
            'role' => AdminRole::HighManager,
        ]);
        $this->actingAs($admin2, 'admin');

        $response = $this->get(route('admin.stock.edit', $stock));
        $response->assertSuccessful();
    }

    public function test_update(): void
    {
        Storage::fake('s3');

        $admin1 = $this->createDefaultAdmin([
            'name' => '管理者1',
            'email' => 'admin1@test.com',
            'role' => AdminRole::Manager,
        ]);
        $this->actingAs($admin1, 'admin');

        $stock = $this->createDefaultStock([
            'name' => 'aaa',
            'detail' => 'aaaの説明',
            'price' => 111,
            'quantity' => 1,
            'image_file_name' => 'image1.jpg',
        ]);

        // manager権限ではアクセスできないことのテスト
        $response = $this->put(route('admin.stock.update', $stock), []);
        $response->assertForbidden();

        $admin2 = $this->createDefaultAdmin([
            'name' => '管理者2',
            'email' => 'admin2@test.com',
            'role' => AdminRole::HighManager,
        ]);
        $this->actingAs($admin2, 'admin');

        $image2 = UploadedFile::fake()->image('image2.jpg');
        $base64String = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($image2->path()));
        $redirectResponse = $this->put(route('admin.stock.update', $stock), [
            'name' => 'bbb',
            'detail' => 'bbbの説明',
            'price' => 222,
            'quantity' => 2,
            'imageFile' => $image2,
            'fileName' => 'image2.jpg',
            'imageBase64' => $base64String,
        ]);
        $response = $this->get($redirectResponse->headers->get('Location'));
        $response->assertSuccessful();

        // データが更新されたことをテスト
        $this->assertDatabaseHas('stocks', [
            'name' => 'bbb',
            'detail' => 'bbbの説明',
            'price' => 222,
            'quantity' => 2,
            'image_file_name' => 'image2.jpg',
        ]);

        // ファイルが存在することをテスト
        Storage::disk('s3')->assertExists('stock/image2.jpg');
    }
}
