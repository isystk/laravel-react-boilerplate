<?php

namespace Http\Controllers\Admin\ContactForm;

use App\Domain\Entities\Admin;
use App\Enums\Age;
use App\Enums\Gender;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EditControllerTest extends TestCase
{

    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    /**
     * お問い合わせ編集画面表示のテスト
     */
    public function testEdit(): void
    {
        $admin1 = $this->createDefaultAdmin([
            'name' => '管理者A',
            'role' => 'manager'
        ]);
        $this->actingAs($admin1, 'admin');

        $contactForm = $this->createDefaultContactForm([
            'user_name' => 'user1',
            'title' => 'title1',
            'email' => '111@test.com',
            'url' => 'https://test.com',
            'gender' => Gender::Female->value,
            'age' => Age::Over40->value,
            'contact' => 'お問い合わせ内容',
        ]);
        $this->createDefaultContactFormImage(['contact_form_id' => $contactForm->id, 'file_name' => 'image1.jpg']);
        $this->createDefaultContactFormImage(['contact_form_id' => $contactForm->id, 'file_name' => 'image2.jpg']);

        // manager権限ではアクセスできないことのテスト
        $response = $this->get(route('admin.contact.edit', $contactForm));
        $response->assertForbidden();

        $admin2 = $this->createDefaultAdmin([
            'name' => '管理者2',
            'role' => 'high-manager'
        ]);
        $this->actingAs($admin2, 'admin');

        $response = $this->get(route('admin.contact.edit', $contactForm));
        $response->assertSuccessful();
    }

    /**
     * お問い合わせ編集画面 変更のテスト
     */
    public function testUpdate(): void
    {
        Storage::fake();

        $admin1 = $this->createDefaultAdmin([
            'name' => '管理者A',
            'role' => 'manager'
        ]);
        $this->actingAs($admin1, 'admin');

        $contactForm = $this->createDefaultContactForm([
            'user_name' => 'aaa',
            'title' => 'タイトル1',
            'email' => 'aaa@test.com',
            'url' => 'https://aaa.test.com',
            'gender' => Gender::Male->value,
            'age' => Age::Over30->value,
            'contact' => 'お問い合わせ1'
        ]);
        $contactFormImage = $this->createDefaultContactFormImage(['contact_form_id' => $contactForm->id, 'file_name' => 'image1.jpg']);

        // manager権限ではアクセスできないことのテスト
        $response = $this->put(route('admin.contact.update', $contactForm), []);
        $response->assertForbidden();

        /** @var Admin $admin2 */
        $admin2 = Admin::factory()->create([
            'name' => '管理者2',
            'role' => 'high-manager'
        ]);
        $this->actingAs($admin2, 'admin');

        $redirectResponse = $this->put(route('admin.contact.update', $contactForm), [
            'user_name' => 'bbb',
            'title' => 'タイトル2',
            'email' => 'bbb@test.com',
            'url' => 'https://bbb.test.com',
            'gender' => Gender::Female->value,
            'age' => Age::Over40->value,
            'contact' => 'お問い合わせ2',
            'delete_image_1' => '1',
            'image_file_2' => UploadedFile::fake()->image('image2.jpg'),
            'image_file_3' => UploadedFile::fake()->image('image3.jpg'),
        ]);
        $response = $this->get($redirectResponse->headers->get('Location'));
        $response->assertSuccessful();

        // データが更新されたことをテスト
        $this->assertDatabaseHas('contact_forms', ['id' => $contactForm->id, 'user_name' => 'bbb']);
        $this->assertDatabaseHas('contact_forms', ['id' => $contactForm->id, 'title' => 'タイトル2']);
        $this->assertDatabaseHas('contact_forms', ['id' => $contactForm->id, 'email' => 'bbb@test.com']);
        $this->assertDatabaseHas('contact_forms', ['id' => $contactForm->id, 'url' => 'https://bbb.test.com']);
        $this->assertDatabaseHas('contact_forms', ['id' => $contactForm->id, 'gender' => Gender::Female->value]);
        $this->assertDatabaseHas('contact_forms', ['id' => $contactForm->id, 'age' => Age::Over40->value]);
        $this->assertDatabaseHas('contact_forms', ['id' => $contactForm->id, 'contact' => 'お問い合わせ2']);

        // 元の画像が削除されたことをテスト
        $this->assertDatabaseMissing('contact_form_images', ['id' => $contactFormImage->id]);

        // 新しい画像が登録されたことをテスト
        $this->assertDatabaseHas('contact_form_images', ['contact_form_id' => $contactForm->id, 'file_name' => 'image2.jpg']);
        $this->assertDatabaseHas('contact_form_images', ['contact_form_id' => $contactForm->id, 'file_name' => 'image3.jpg']);
    }

}
