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

        $user1 = $this->createDefaultUser();
        $user2 = $this->createDefaultUser();
        $this->createDefaultContact(['user_id' => $user1->id, 'title' => 'title1']);
        $this->createDefaultContact(['user_id' => $user2->id, 'title' => 'title2']);

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
}
