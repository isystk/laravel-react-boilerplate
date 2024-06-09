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
     * 注文一覧画面表示のテスト
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

}
