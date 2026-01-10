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
        $response->assertSee('ようこそ！管理者A');
    }
}
