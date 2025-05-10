<?php

namespace Http\Controllers\Admin\Stock;

use App\Enums\AdminRole;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    /**
     * 商品一覧画面表示のテスト
     */
    public function test_index(): void
    {
        $this->createDefaultStock(['name' => 'stock1']);
        $this->createDefaultStock(['name' => 'stock2']);

        $admin = $this->createDefaultAdmin([
            'name' => 'admin1',
            'role' => AdminRole::Manager->value,
        ]);
        $this->actingAs($admin, 'admin');

        $response = $this->get(route('admin.stock') . '?sort_name=id&sort_direction=asc');
        $response->assertSuccessful();
        $response->assertSeeInOrder(['stock1', 'stock2']);
    }
}
