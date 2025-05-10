<?php

namespace Http\Controllers\Admin\Stock;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CreateControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    /**
     * 商品新規登録画面表示のテスト
     */
    public function test_create(): void
    {
        $admin1 = $this->createDefaultAdmin([
            'name' => '管理者1',
            'role' => 'manager',
        ]);
        $this->actingAs($admin1, 'admin');

        // manager権限ではアクセスできないことのテスト
        $response = $this->get(route('admin.stock.create'));
        $response->assertForbidden();

        $admin2 = $this->createDefaultAdmin([
            'name' => '管理者2',
            'role' => 'high-manager',
        ]);
        $this->actingAs($admin2, 'admin');

        $response = $this->get(route('admin.stock.create'));
        $response->assertSuccessful();
    }

    /**
     * 商品新規登録画面 登録のテスト
     */
    public function test_store(): void
    {
        Storage::fake();

        $admin1 = $this->createDefaultAdmin([
            'name' => '管理者1',
            'role' => 'manager',
        ]);
        $this->actingAs($admin1, 'admin');

        // manager権限ではアクセスできないことのテスト
        $response = $this->post(route('admin.stock.store'), []);
        $response->assertForbidden();

        $admin2 = $this->createDefaultAdmin([
            'name' => '管理者2',
            'role' => 'high-manager',
        ]);
        $this->actingAs($admin2, 'admin');

        $image1 = UploadedFile::fake()->image('image1.jpg');
        $base64String = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($image1->path()));
        $redirectResponse = $this->post(route('admin.stock.store'), [
            'name' => 'aaa',
            'detail' => 'aaaの説明',
            'price' => 111,
            'quantity' => 1,
            'imageFile' => $image1,
            'fileName' => 'image1.jpg',
            'imageBase64' => $base64String,
        ]);
        $response = $this->get($redirectResponse->headers->get('Location'));
        $response->assertSuccessful();

        // データが登録されたことをテスト
        $this->assertDatabaseHas('stocks', [
            'name' => 'aaa',
            'detail' => 'aaaの説明',
            'price' => 111,
            'quantity' => 1,
            'imgpath' => 'image1.jpg',
        ]);
    }
}
