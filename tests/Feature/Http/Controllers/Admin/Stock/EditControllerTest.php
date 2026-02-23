<?php

namespace Tests\Feature\Http\Controllers\Admin\Stock;

use App\Enums\AdminRole;
use App\Services\Admin\Stock\UpdateService;
use Exception;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\BaseTest;

class EditControllerTest extends BaseTest
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_edit(): void
    {
        $admin1 = $this->createDefaultAdmin([
            'name' => '管理者A',
            'role' => AdminRole::Manager,
        ]);
        $this->actingAs($admin1, 'admin');

        $stock = $this->createDefaultStock([
            'name' => 'aaa',
        ]);

        // manager権限ではアクセスできないことのテスト
        $response = $this->get(route('admin.stock.edit', $stock));
        $response->assertForbidden();

        $admin2 = $this->createDefaultAdmin([
            'name' => '管理者2',
            'role' => AdminRole::HighManager,
        ]);
        $this->actingAs($admin2, 'admin');

        $response = $this->get(route('admin.stock.edit', $stock));
        $response->assertSuccessful();
    }

    public function test_edit_管理者ロール別アクセス権限検証(): void
    {
        $cases = [
            ['role' => AdminRole::HighManager, 'status' => 200],
            ['role' => AdminRole::Manager,     'status' => 403],
        ];

        $stock = $this->createDefaultStock([
            'name' => 'aaa',
        ]);

        foreach ($cases as $case) {
            $admin = $this->createDefaultAdmin([
                'role' => $case['role']->value,
            ]);

            $this->actingAs($admin, 'admin')
                ->get(route('admin.stock.edit', $stock))
                ->assertStatus($case['status']);
        }
    }

    public function test_update(): void
    {
        Storage::fake('s3');

        $admin1 = $this->createDefaultAdmin([
            'name'  => '管理者1',
            'email' => 'admin1@test.com',
            'role'  => AdminRole::Manager,
        ]);
        $this->actingAs($admin1, 'admin');

        $stock = $this->createDefaultStock([
            'name'     => 'aaa',
            'detail'   => 'aaaの説明',
            'price'    => 111,
            'quantity' => 1,
        ]);

        // manager権限ではアクセスできないことのテスト
        $response = $this->put(route('admin.stock.update', $stock), []);
        $response->assertForbidden();

        $admin2 = $this->createDefaultAdmin([
            'name'  => '管理者2',
            'email' => 'admin2@test.com',
            'role'  => AdminRole::HighManager,
        ]);
        $this->actingAs($admin2, 'admin');

        $image2           = UploadedFile::fake()->image('image2.jpg');
        $base64String     = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($image2->path()));
        $redirectResponse = $this->put(route('admin.stock.update', $stock), [
            'name'            => 'bbb',
            'detail'          => 'bbbの説明',
            'price'           => 222,
            'quantity'        => 2,
            'image_file_name' => 'image2.jpg',
            'image_base_64'   => $base64String,
        ]);
        $response = $this->get($redirectResponse->headers->get('Location'));
        $response->assertSuccessful();

        // データが更新されたことをテスト
        $stock = $stock->fresh();
        $this->assertSame('bbb', $stock->name);
        $this->assertSame('bbbの説明', $stock->detail);
        $this->assertSame(222, $stock->price);
        $this->assertSame(2, $stock->quantity);

        $image = $stock->image;
        $this->assertSame('image2.jpg', $image->file_name);

        // ファイルが存在することをテスト
        Storage::disk('s3')->assertExists($image->getS3Path());
    }

    public function test_edit_not_found(): void
    {
        $admin = $this->createDefaultAdmin([
            'role' => AdminRole::HighManager,
        ]);
        $this->actingAs($admin, 'admin');

        $this->get(route('admin.stock.edit', ['stock' => 999]))
            ->assertNotFound();
    }

    public function test_update_not_found(): void
    {
        $admin = $this->createDefaultAdmin([
            'role' => AdminRole::HighManager,
        ]);
        $this->actingAs($admin, 'admin');

        $this->put(route('admin.stock.update', ['stock' => 999]), [
            'name'  => 'bbb',
            'price' => 222,
        ])->assertNotFound();
    }

    public function test_update_validation_error(): void
    {
        $admin = $this->createDefaultAdmin([
            'role' => AdminRole::HighManager,
        ]);
        $this->actingAs($admin, 'admin');

        $stock = $this->createDefaultStock();

        $response = $this->put(route('admin.stock.update', $stock), [
            'name'     => '',
            'price'    => 'not-a-number',
            'detail'   => '',
            'quantity' => 'not-a-number',
        ]);

        $response->assertSessionHasErrors(['name', 'price', 'detail', 'quantity']);
    }

    public function test_guest_cannot_access(): void
    {
        $stock = $this->createDefaultStock();

        $this->get(route('admin.stock.edit', $stock))
            ->assertRedirect(route('login'));

        $this->put(route('admin.stock.update', $stock))
            ->assertRedirect(route('login'));
    }

    public function test_update_service_error(): void
    {
        $admin = $this->createDefaultAdmin([
            'role' => AdminRole::HighManager,
        ]);
        $this->actingAs($admin, 'admin');

        $stock = $this->createDefaultStock();

        $this->mock(UpdateService::class, function ($mock) {
            $mock->shouldReceive('update')->andThrow(new Exception('Service Error'));
        });

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Service Error');

        $this->put(route('admin.stock.update', $stock), [
            'name'     => 'bbb',
            'detail'   => 'bbbの説明',
            'price'    => 222,
            'quantity' => 2,
        ]);
    }
}
