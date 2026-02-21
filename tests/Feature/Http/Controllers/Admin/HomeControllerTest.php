<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Enums\AdminRole;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class HomeControllerTest extends BaseTest
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
            'role' => AdminRole::HighManager,
        ]);
        $this->actingAs($admin, 'admin');

        $response = $this->get(route('admin.home'));
        $response->assertSuccessful();
        $response->assertViewIs('admin.home');
        $response->assertViewHasAll([
            'unrepliedContactsCount',
            'salesByMonth',
            'latestOrders',
            'usersByMonth',
        ]);
    }

    public function test_index_未返信件数がビューに渡される(): void
    {
        $admin = $this->createDefaultAdmin(['role' => AdminRole::HighManager]);
        $this->actingAs($admin, 'admin');

        // 未返信: 2件、返信済み: 1件
        $this->createDefaultContact();
        $this->createDefaultContact();
        $repliedContact = $this->createDefaultContact();
        $this->createDefaultContactReply(['contact_id' => $repliedContact->id]);

        $response = $this->get(route('admin.home'));
        $response->assertSuccessful();
        $response->assertViewHas('unrepliedContactsCount', 2);
    }

    public function test_index_月別ユーザーデータがビューに渡される(): void
    {
        $admin = $this->createDefaultAdmin(['role' => AdminRole::HighManager]);
        $this->actingAs($admin, 'admin');

        $this->createDefaultUser(['created_at' => now()->startOfMonth()]);

        $response = $this->get(route('admin.home'));
        $response->assertSuccessful();

        /** @var \Illuminate\Support\Collection<int, array{year_month: string, count: int}> $usersByMonth */
        $usersByMonth = $response->viewData('usersByMonth');
        $this->assertGreaterThanOrEqual(1, $usersByMonth->count());
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
                ->get(route('admin.home'))
                ->assertStatus($case['status']);
        }
    }
}
