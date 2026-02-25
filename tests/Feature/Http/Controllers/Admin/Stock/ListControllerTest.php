<?php

namespace Tests\Feature\Http\Controllers\Admin\Stock;

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
        $this->createDefaultStock(['name' => 'stock1']);
        $this->createDefaultStock(['name' => 'stock2']);

        $admin = $this->createDefaultAdmin([
            'name' => 'admin1',
            'role' => AdminRole::Staff->value,
        ]);
        $this->actingAs($admin, 'admin');

        $response = $this->get(route('admin.stock'));
        $response->assertSuccessful();
        $response->assertSeeInOrder(['stock2', 'stock1']);
    }

    public function test_index_管理者ロール別アクセス権限検証(): void
    {
        $cases = [
            ['role' => AdminRole::SuperAdmin, 'status' => 200],
            ['role' => AdminRole::Staff,     'status' => 200],
        ];

        foreach ($cases as $case) {
            $admin = $this->createDefaultAdmin([
                'role' => $case['role']->value,
            ]);

            $this->actingAs($admin, 'admin')
                ->get(route('admin.stock'))
                ->assertStatus($case['status']);
        }
    }

    public function test_export(): void
    {
        $admin = $this->createDefaultAdmin();
        $this->actingAs($admin, 'admin');

        $this->get(route('admin.stock.export', ['file_type' => 'csv']))
            ->assertSuccessful()
            ->assertHeader('content-disposition', 'attachment; filename=stocks.csv');

        $this->get(route('admin.stock.export', ['file_type' => 'xlsx']))
            ->assertSuccessful()
            ->assertHeader('content-disposition', 'attachment; filename=stocks.xlsx');

        $this->get(route('admin.stock.export', ['file_type' => 'pdf']))
            ->assertSuccessful()
            ->assertHeader('content-disposition', 'attachment; filename=stocks.pdf');
    }

    public function test_export_invalid_type(): void
    {
        $admin = $this->createDefaultAdmin();
        $this->actingAs($admin, 'admin');

        $this->get(route('admin.stock.export', ['file_type' => 'invalid']))
            ->assertStatus(400);
    }

    public function test_guest_cannot_access(): void
    {
        $this->get(route('admin.stock'))
            ->assertRedirect(route('login'));

        $this->get(route('admin.stock.export', ['file_type' => 'csv']))
            ->assertRedirect(route('login'));
    }
}
