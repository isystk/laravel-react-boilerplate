<?php

namespace Feature\Http\Controllers\Admin\ContactForm;

use App\Domain\Entities\Admin;
use App\Domain\Entities\ContactForm;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    /**
     * お問い合わせ一覧画面表示のテスト
     */
    public function testIndex(): void
    {
        /** @var Admin $admin */
        $admin = Admin::factory()->create([
            'name' => '管理者A',
            'role' => 'manager'
        ]);
        $this->actingAs($admin, 'admin');

        ContactForm::factory(['user_name' => 'user1', 'title' => 'title1'])->create();
        ContactForm::factory(['user_name' => 'user2', 'title' => 'title2'])->create();

        $response = $this->get(route('admin.contact'));
        $response->assertSuccessful();
        $response->assertSeeInOrder(['title1', 'title2']);
    }

}
