<?php

namespace Feature\Http\Controllers\Admin\Stock;

use App\Domain\Entities\Admin;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CreateControllerTest extends TestCase
{
    /**
     * 各テストの実行後にテーブルを空にする。
     */
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    /**
     * 商品新規登録画面表示のテスト
     */
    public function testCreate(): void
    {
        /** @var Admin $admin1 */
        $admin1 = Admin::factory()->create([
            'name' => '管理者1',
            'role' => 'manager'
        ]);
        $this->actingAs($admin1, 'admin');

        // manager権限ではアクセスできないことのテスト
        $response = $this->get(route('admin.stock.create'));
        $response->assertForbidden();

        /** @var Admin $admin2 */
        $admin2 = Admin::factory()->create([
            'name' => '管理者2',
            'role' => 'high-manager'
        ]);
        $this->actingAs($admin2, 'admin');

        $response = $this->get(route('admin.stock.create'));
        $response->assertSuccessful();
    }

    /**
     * 商品新規登録画面 登録のテスト
     */
    public function testStore(): void
    {
        /** @var Admin $admin1 */
        $admin1 = Admin::factory()->create([
            'name' => '管理者1',
            'role' => 'manager'
        ]);
        $this->actingAs($admin1, 'admin');

        // manager権限ではアクセスできないことのテスト
        $response = $this->post(route('admin.stock.store'), []);
        $response->assertForbidden();

        /** @var Admin $admin2 */
        $admin2 = Admin::factory()->create([
            'name' => '管理者2',
            'role' => 'high-manager'
        ]);
        $this->actingAs($admin2, 'admin');

        $image1 = UploadedFile::fake()->image('image1.jpg');
        $base64String = base64_encode(file_get_contents($image1->path()));
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

        // テスト後にファイルを削除
        Storage::delete('stock/image1.jpg');
    }

}
