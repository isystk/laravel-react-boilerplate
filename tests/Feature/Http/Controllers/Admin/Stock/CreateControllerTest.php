<?php

namespace Tests\Feature\Http\Controllers\Admin\Stock;

use App\Domain\Entities\Stock;
use App\Enums\AdminRole;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\BaseTest;

class CreateControllerTest extends BaseTest
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_create(): void
    {
        $admin1 = $this->createDefaultAdmin([
            'name' => '管理者1',
            'role' => AdminRole::Manager,
        ]);
        $this->actingAs($admin1, 'admin');

        // manager権限ではアクセスできないことのテスト
        $response = $this->get(route('admin.stock.create'));
        $response->assertForbidden();

        $admin2 = $this->createDefaultAdmin([
            'name' => '管理者2',
            'role' => AdminRole::HighManager,
        ]);
        $this->actingAs($admin2, 'admin');

        $response = $this->get(route('admin.stock.create'));
        $response->assertSuccessful();
    }

    public function test_create_管理者ロール別アクセス権限検証(): void
    {
        $cases = [
            ['role' => AdminRole::HighManager, 'status' => 200],
            ['role' => AdminRole::Manager,     'status' => 403],
        ];

        foreach ($cases as $case) {
            $admin = $this->createDefaultAdmin([
                'role' => $case['role']->value,
            ]);

            $this->actingAs($admin, 'admin')
                ->get(route('admin.stock.create'))
                ->assertStatus($case['status']);
        }
    }

    public function test_store(): void
    {
        Storage::fake('s3');

        $admin1 = $this->createDefaultAdmin([
            'name' => '管理者1',
            'role' => AdminRole::Manager,
        ]);
        $this->actingAs($admin1, 'admin');

        // manager権限ではアクセスできないことのテスト
        $response = $this->post(route('admin.stock.store'), []);
        $response->assertForbidden();

        $admin2 = $this->createDefaultAdmin([
            'name' => '管理者2',
            'role' => AdminRole::HighManager,
        ]);
        $this->actingAs($admin2, 'admin');

        $image1           = UploadedFile::fake()->image('image1.jpg');
        $base64String     = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($image1->path()));
        $redirectResponse = $this->post(route('admin.stock.store'), [
            'name'            => 'aaa',
            'detail'          => 'aaaの説明',
            'price'           => 111,
            'quantity'        => 1,
            'image_file_name' => 'image1.jpg',
            'image_base_64'   => $base64String,
        ]);
        $response = $this->get($redirectResponse->headers->get('Location'));
        $response->assertSuccessful();

        // データが登録されたことをテスト
        $stock = Stock::first();
        $this->assertSame('aaa', $stock->name);
        $this->assertSame('aaaの説明', $stock->detail);
        $this->assertSame(111, $stock->price);
        $this->assertSame(1, $stock->quantity);

        $image = $stock->image;
        $this->assertSame('image1.jpg', $image->file_name);

        // ファイルが存在することをテスト
        Storage::disk('s3')->assertExists($image->getS3Path());
    }

    public function test_store_validation_error(): void
    {
        $admin = $this->createDefaultAdmin([
            'role' => AdminRole::HighManager,
        ]);
        $this->actingAs($admin, 'admin');

        $response = $this->post(route('admin.stock.store'), [
            'name'            => '',
            'price'           => 'not-a-number',
            'detail'          => '',
            'quantity'        => 'not-a-number',
            'image_file_name' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'price', 'detail', 'quantity', 'image_file_name']);
    }

    public function test_guest_cannot_access(): void
    {
        $this->get(route('admin.stock.create'))
            ->assertRedirect(route('login'));

        $this->post(route('admin.stock.store'))
            ->assertRedirect(route('login'));
    }
}
