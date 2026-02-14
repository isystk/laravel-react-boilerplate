<?php

namespace Tests\Feature\Http\Controllers\Admin\ContactForm;

use App\Domain\Entities\Admin;
use App\Enums\AdminRole;
use App\Enums\Age;
use App\Enums\Gender;
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

        $contactForm = $this->createDefaultContactForm([
            'user_name' => 'user1',
            'title'     => 'title1',
            'email'     => '111@test.com',
            'url'       => 'https://test.com',
            'gender'    => Gender::Female->value,
            'age'       => Age::Over40->value,
            'contact'   => 'お問い合わせ内容',
        ]);

        // manager権限ではアクセスできないことのテスト
        $response = $this->get(route('admin.contact.edit', $contactForm));
        $response->assertForbidden();

        $admin2 = $this->createDefaultAdmin([
            'name' => '管理者2',
            'role' => AdminRole::HighManager,
        ]);
        $this->actingAs($admin2, 'admin');

        $response = $this->get(route('admin.contact.edit', $contactForm));
        $response->assertSuccessful();
    }

    public function test_edit_管理者ロール別アクセス権限検証(): void
    {
        $cases = [
            ['role' => AdminRole::HighManager, 'status' => 200],
            ['role' => AdminRole::Manager,     'status' => 403],
        ];

        $contactForm = $this->createDefaultContactForm();

        foreach ($cases as $case) {
            $admin = $this->createDefaultAdmin([
                'role' => $case['role']->value,
            ]);

            $this->actingAs($admin, 'admin')
                ->get(route('admin.contact.edit', $contactForm))
                ->assertStatus($case['status']);
        }
    }

    public function test_update(): void
    {
        Storage::fake('s3');

        $admin1 = $this->createDefaultAdmin([
            'name' => '管理者A',
            'role' => AdminRole::Manager,
        ]);
        $this->actingAs($admin1, 'admin');

        $contactForm = $this->createDefaultContactForm([
            'user_name' => 'aaa',
            'title'     => 'タイトル1',
            'email'     => 'aaa@test.com',
            'url'       => 'https://aaa.test.com',
            'gender'    => Gender::Male->value,
            'age'       => Age::Over30->value,
            'contact'   => 'お問い合わせ1',
        ]);

        // manager権限ではアクセスできないことのテスト
        $response = $this->put(route('admin.contact.update', $contactForm), []);
        $response->assertForbidden();

        /** @var Admin $admin2 */
        $admin2 = Admin::factory()->create([
            'name' => '管理者2',
            'role' => AdminRole::HighManager,
        ]);
        $this->actingAs($admin2, 'admin');

        $imageFile        = UploadedFile::fake()->image('image2.jpg');
        $redirectResponse = $this->put(route('admin.contact.update', $contactForm), [
            'user_name'       => 'bbb',
            'title'           => 'タイトル2',
            'email'           => 'bbb@test.com',
            'url'             => 'https://bbb.test.com',
            'gender'          => Gender::Female->value,
            'age'             => Age::Over40->value,
            'contact'         => 'お問い合わせ2',
            'image_file_name' => 'image2.jpg',
            'image_base_64'   => 'data:image/jpeg;base64,' . base64_encode(file_get_contents($imageFile->path())),
        ]);
        $response = $this->get($redirectResponse->headers->get('Location'));
        $response->assertSuccessful();

        // データが更新されたことをテスト
        $contactForm->refresh();
        $this->assertSame('bbb', $contactForm->user_name);
        $this->assertSame('タイトル2', $contactForm->title);
        $this->assertSame('https://bbb.test.com', $contactForm->url);
        $this->assertSame(Gender::Female, $contactForm->gender);
        $this->assertSame(Age::Over40, $contactForm->age);
        $this->assertSame('お問い合わせ2', $contactForm->contact);

        // 画像が更新されたことをテスト
        $image = $contactForm->image;
        $this->assertEquals('image2.jpg', $image->file_name);
        Storage::disk('s3')->assertExists($image->getS3Path());
    }

    public function test_update_例外発生時にロールバックされ例外がスローされること(): void
    {
        $this->withoutExceptionHandling();

        $contactForm = $this->createDefaultContactForm();

        $admin = $this->createDefaultAdmin([
            'role' => AdminRole::HighManager,
        ]);
        $this->actingAs($admin, 'admin');

        $this->mock(\App\Services\Admin\ContactForm\UpdateService::class, function ($mock) {
            $mock->shouldReceive('update')
                ->once()
                ->andThrow(new \Exception('Update Error'));
        });

        // 例外がスローされることを期待
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Update Error');

        $this->put(route('admin.contact.update', $contactForm), [
            'user_name' => 'test-update',
            'title'     => 'test-title',
            'email'     => 'test@test.com',
            'gender'    => Gender::Male->value,
            'age'       => Age::Over30->value,
            'contact'   => 'test-content',
        ]);
    }
}
