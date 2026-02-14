<?php

namespace Tests\Feature\Http\Controllers\Admin\Contact;

use App\Enums\AdminRole;
use App\Enums\Age;
use App\Enums\Gender;
use App\Services\Admin\Contact\DestroyService;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class DetailControllerTest extends BaseTest
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_show(): void
    {
        $admin = $this->createDefaultAdmin([
            'name' => '管理者A',
            'role' => AdminRole::Manager,
        ]);
        $this->actingAs($admin, 'admin');

        $contact = $this->createDefaultContact([
            'user_name' => 'user1',
            'title'     => 'title1',
            'email'     => '111@test.com',
            'url'       => 'https://test.com',
            'gender'    => Gender::Female->value,
            'age'       => Age::Over40->value,
            'contact'   => 'お問い合わせ内容',
        ]);

        $response = $this->get(route('admin.contact.show', $contact));
        $response->assertSuccessful();
        $response->assertSee('user1');
        $response->assertSee('title1');
        $response->assertSee('111@test.com');
        $response->assertSee('https://test.com');
        $response->assertSee(Gender::Female->label());
        $response->assertSee(Age::Over40->label());
        $response->assertSee('お問い合わせ内容');
    }

    public function test_show_管理者ロール別アクセス権限検証(): void
    {
        $cases = [
            ['role' => AdminRole::HighManager, 'status' => 200],
            ['role' => AdminRole::Manager,     'status' => 200],
        ];

        $contact = $this->createDefaultContact();

        foreach ($cases as $case) {
            $admin = $this->createDefaultAdmin([
                'role' => $case['role']->value,
            ]);

            $this->actingAs($admin, 'admin')
                ->get(route('admin.contact.show', $contact))
                ->assertStatus($case['status']);
        }
    }

    public function test_destroy(): void
    {
        $contact = $this->createDefaultContact([
            'user_name' => 'user1',
            'title'     => 'title1',
        ]);

        $admin1 = $this->createDefaultAdmin([
            'name' => '管理者1',
            'role' => AdminRole::Manager,
        ]);
        $this->actingAs($admin1, 'admin');

        // manager権限ではアクセスできないことのテスト
        $response = $this->delete(route('admin.contact.destroy', $contact));
        $response->assertForbidden();

        $admin2 = $this->createDefaultAdmin([
            'name' => '管理者2',
            'role' => AdminRole::HighManager,
        ]);
        $this->actingAs($admin2, 'admin');

        $redirectResponse = $this->delete(route('admin.contact.destroy', $contact));
        $response         = $this->get($redirectResponse->headers->get('Location'));
        $response->assertSuccessful();

        // データが削除されたことをテスト
        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    }

    public function test_destroy_例外発生時にロールバックされ例外がスローされること(): void
    {
        $this->withoutExceptionHandling();

        $contact = $this->createDefaultContact();

        $admin = $this->createDefaultAdmin([
            'role' => AdminRole::HighManager,
        ]);
        $this->actingAs($admin, 'admin');

        $this->mock(DestroyService::class, function ($mock) {
            $mock->shouldReceive('delete')
                ->once()
                ->andThrow(new \Exception('Database Error'));
        });

        // 例外がスローされることを期待
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Database Error');

        $this->delete(route('admin.contact.destroy', $contact));
    }
}
