<?php

namespace Tests\Feature\Http\Controllers\Admin\Contact;

use App\Enums\AdminRole;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
            'role' => AdminRole::Manager->value,
        ]);
        $this->actingAs($admin, 'admin');

        $user1    = $this->createDefaultUser();
        $user2    = $this->createDefaultUser();
        $contact1 = $this->createDefaultContact(['user_id' => $user1->id, 'title' => 'title1']);
        $contact2 = $this->createDefaultContact(['user_id' => $user2->id, 'title' => 'title2']);

        $response = $this->get(route('admin.contact'));
        $response->assertSuccessful();
        $response->assertSeeInOrder(['title2', 'title1']);
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
                ->get(route('admin.contact'))
                ->assertStatus($case['status']);
        }
    }

    public function test_index_初回アクセス時は未返信のみ表示されること(): void
    {
        $admin = $this->createDefaultAdmin(['role' => AdminRole::Manager->value]);
        $this->actingAs($admin, 'admin');

        $user1     = $this->createDefaultUser();
        $user2     = $this->createDefaultUser();
        $unreplied = $this->createDefaultContact(['user_id' => $user1->id, 'title' => '未返信件名']);
        $replied   = $this->createDefaultContact(['user_id' => $user2->id, 'title' => '返信済み件名']);
        $this->createDefaultContactReply(['contact_id' => $replied->id]);

        $response = $this->get(route('admin.contact'));

        $response->assertSuccessful();
        $response->assertSee('未返信件名');
        $response->assertDontSee('返信済み件名');
    }

    public function test_index_searched指定時にonly_unrepliedチェックで未返信のみ表示されること(): void
    {
        $admin = $this->createDefaultAdmin(['role' => AdminRole::Manager->value]);
        $this->actingAs($admin, 'admin');

        $user1     = $this->createDefaultUser();
        $user2     = $this->createDefaultUser();
        $unreplied = $this->createDefaultContact(['user_id' => $user1->id, 'title' => '未返信件名']);
        $replied   = $this->createDefaultContact(['user_id' => $user2->id, 'title' => '返信済み件名']);
        $this->createDefaultContactReply(['contact_id' => $replied->id]);

        $response = $this->get(route('admin.contact', [
            'searched'       => '1',
            'only_unreplied' => '1',
        ]));

        $response->assertSuccessful();
        $response->assertSee('未返信件名');
        $response->assertDontSee('返信済み件名');
    }

    public function test_index_searched指定時にonly_unrepliedなしで全件表示されること(): void
    {
        $admin = $this->createDefaultAdmin(['role' => AdminRole::Manager->value]);
        $this->actingAs($admin, 'admin');

        $user1     = $this->createDefaultUser();
        $user2     = $this->createDefaultUser();
        $unreplied = $this->createDefaultContact(['user_id' => $user1->id, 'title' => '未返信件名']);
        $replied   = $this->createDefaultContact(['user_id' => $user2->id, 'title' => '返信済み件名']);
        $this->createDefaultContactReply(['contact_id' => $replied->id]);

        $response = $this->get(route('admin.contact', [
            'searched' => '1',
        ]));

        $response->assertSuccessful();
        $response->assertSee('未返信件名');
        $response->assertSee('返信済み件名');
    }

    public function test_index_keywordで名前検索ができること(): void
    {
        $admin = $this->createDefaultAdmin(['role' => AdminRole::Manager->value]);
        $this->actingAs($admin, 'admin');

        $user1 = $this->createDefaultUser(['name' => 'yamada taro']);
        $user2 = $this->createDefaultUser(['name' => 'suzuki hanako']);
        $this->createDefaultContact(['user_id' => $user1->id, 'title' => '山田のお問い合わせ']);
        $this->createDefaultContact(['user_id' => $user2->id, 'title' => '鈴木のお問い合わせ']);

        $response = $this->get(route('admin.contact', [
            'searched' => '1',
            'keyword'  => 'yamada',
        ]));

        $response->assertSuccessful();
        $response->assertSee('山田のお問い合わせ');
        $response->assertDontSee('鈴木のお問い合わせ');
    }

    public function test_index_keywordでメールアドレス検索ができること(): void
    {
        $admin = $this->createDefaultAdmin(['role' => AdminRole::Manager->value]);
        $this->actingAs($admin, 'admin');

        $user1 = $this->createDefaultUser(['email' => 'target@example.com']);
        $user2 = $this->createDefaultUser(['email' => 'other@example.com']);
        $this->createDefaultContact(['user_id' => $user1->id, 'title' => 'ターゲットユーザーのお問い合わせ']);
        $this->createDefaultContact(['user_id' => $user2->id, 'title' => '他ユーザーのお問い合わせ']);

        $response = $this->get(route('admin.contact', [
            'searched' => '1',
            'keyword'  => 'target@example.com',
        ]));

        $response->assertSuccessful();
        $response->assertSee('ターゲットユーザーのお問い合わせ');
        $response->assertDontSee('他ユーザーのお問い合わせ');
    }

    public function test_index_keywordでID検索ができること(): void
    {
        $admin = $this->createDefaultAdmin(['role' => AdminRole::Manager->value]);
        $this->actingAs($admin, 'admin');

        $user1   = $this->createDefaultUser();
        $user2   = $this->createDefaultUser();
        $contact = $this->createDefaultContact(['user_id' => $user1->id, 'title' => 'ID検索対象']);
        $this->createDefaultContact(['user_id' => $user2->id, 'title' => '他のお問い合わせ']);

        $response = $this->get(route('admin.contact', [
            'searched' => '1',
            'keyword'  => (string) $contact->id,
        ]));

        $response->assertSuccessful();
        $response->assertSee('ID検索対象');
        $response->assertDontSee('他のお問い合わせ');
    }
}
