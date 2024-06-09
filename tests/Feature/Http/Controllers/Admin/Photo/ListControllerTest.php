<?php

namespace Feature\Http\Controllers\Admin\Photo;

use App\Domain\Entities\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ListControllerTest extends TestCase
{
    /**
     * 各テストの実行後にテーブルを空にする。
     */
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * 画像一覧画面表示のテスト
     */
    public function testIndex(): void
    {
        /** @var Admin $admin */
        $admin = Admin::factory()->create([
            'name' => '管理者A',
            'role' => 'manager'
        ]);
        $this->actingAs($admin, 'admin');

        // テスト用のファイルを作成
        Storage::put('contact\contact1.jpg', '');
        Storage::put('stock\stock1.jpg', '');
        Storage::put('stock\stock2.jpg', '');

        $response = $this->get(route('admin.photo'));
        $response->assertSuccessful();
        $response->assertSeeInOrder([
            'contact/contact1.jpg',
            'stock/stock1.jpg',
            'stock/stock2.jpg'
        ]);

        // テストファイルを削除
        Storage::delete('contact\contact1.jpg');
        Storage::delete('stock\stock1.jpg');
        Storage::delete('stock\stock2.jpg');
    }

    /**
     * 画像一覧画面 削除のテスト
     */
    public function testDestroy(): void
    {
        /** @var Admin $admin */
        $admin = Admin::factory()->create([
            'name' => '管理者A',
            'role' => 'manager'
        ]);
        $this->actingAs($admin, 'admin');

        // テスト用のファイルを作成
        Storage::put('contact\contact1.jpg', '');
        Storage::put('stock\stock1.jpg', '');

        // manager権限ではアクセスできないことのテスト
        $response = $this->delete(route('admin.photo.destroy'), []);
        $response->assertForbidden();

        /** @var Admin $admin2 */
        $admin2 = Admin::factory()->create([
            'name' => '管理者2',
            'role' => 'high-manager'
        ]);
        $this->actingAs($admin2, 'admin');

        $redirectResponse = $this->delete(route('admin.photo.destroy'), [
            'fileName' => 'contact\contact1.jpg'
        ]);
        $response = $this->get($redirectResponse->headers->get('Location'));
        $response->assertSuccessful();
        // ファイルが削除されたことを確認
        $this->assertFalse(Storage::exists('contact\contact1.jpg'));

        $redirectResponse = $this->delete(route('admin.photo.destroy'), [
            'fileName' => 'stock\stock1.jpg'
        ]);
        $response = $this->get($redirectResponse->headers->get('Location'));
        $response->assertSuccessful();
        // ファイルが削除されたことを確認
        $this->assertFalse(Storage::exists('stock\stock1.jpg'));
    }

}
