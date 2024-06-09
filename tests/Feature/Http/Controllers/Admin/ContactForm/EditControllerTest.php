<?php

namespace Feature\Http\Controllers\Admin\ContactForm;

use App\Domain\Entities\Admin;
use App\Domain\Entities\ContactForm;
use App\Domain\Entities\ContactFormImage;
use App\Enums\Age;
use App\Enums\Gender;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EditControllerTest extends TestCase
{
    /**
     * 各テストの実行後にテーブルを空にする。
     */
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
        /** @var Admin $admin1 */
        $admin1 = Admin::factory()->create([
            'name' => '管理者A',
            'role' => 'manager'
        ]);
        $this->actingAs($admin1, 'admin');

        /** @var ContactForm $contactForm */
        $contactForm = ContactForm::factory([
            'user_name' => 'user1',
            'title' => 'title1',
            'email' => '111@test.com',
            'url' => 'https://test.com',
            'gender' => Gender::Female->value,
            'age' => Age::Over40->value,
            'contact' => 'お問い合わせ内容',
        ])->create();
        ContactFormImage::factory(['contact_form_id' => $contactForm->id, 'file_name' => 'image1.jpg'])->create();
        ContactFormImage::factory(['contact_form_id' => $contactForm->id, 'file_name' => 'image2.jpg'])->create();

        // manager権限ではアクセスできないことのテスト
        $response = $this->get(route('admin.contact.edit', $contactForm));
        $response->assertForbidden();

        /** @var Admin $admin2 */
        $admin2 = Admin::factory()->create([
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
        /** @var Admin $admin1 */
        $admin1 = Admin::factory()->create([
            'name' => '管理者A',
            'role' => 'manager'
        ]);
        $this->actingAs($admin1, 'admin');

        /** @var ContactForm $contactForm */
        $contactForm = ContactForm::factory([
            'user_name' => 'user1',
            'title' => 'title1',
            'email' => '111@test.com',
            'url' => 'https://test.com',
            'gender' => Gender::Female->value,
            'age' => Age::Over40->value,
            'contact' => 'お問い合わせ内容',
        ])->create();
        /** @var ContactFormImage $contactFormImage */
        $contactFormImage = ContactFormImage::factory(['contact_form_id' => $contactForm->id, 'file_name' => 'image1.jpg'])->create();

        // manager権限ではアクセスできないことのテスト
        $response = $this->post(route('admin.contact.update', $contactForm), []);
        $response->assertForbidden();

        /** @var Admin $admin2 */
        $admin2 = Admin::factory()->create([
            'name' => '管理者2',
            'role' => 'high-manager'
        ]);
        $this->actingAs($admin2, 'admin');

        $redirectResponse = $this->post(route('admin.contact.update', $contactForm), [
            'user_name' => 'user2',
            'title' => 'title2',
            'email' => '222@test.com',
            'url' => 'https://test2.com',
            'gender' => Gender::Male->value,
            'age' => Age::Over50->value,
            'contact' => 'お問い合わせ内容2',
            'image_files' => [
                UploadedFile::fake()->image('image2.jpg'),
                UploadedFile::fake()->image('image3.jpg')
            ]
        ]);
        $response = $this->get($redirectResponse->headers->get('Location'));
        $response->assertSuccessful();

        // データが変更されたことをテスト
        $this->assertDatabaseHas('contact_forms', ['id' => $contactForm->id, 'user_name' => 'user2']);
        $this->assertDatabaseHas('contact_forms', ['id' => $contactForm->id, 'title' => 'title2']);
        $this->assertDatabaseHas('contact_forms', ['id' => $contactForm->id, 'email' => '222@test.com']);
        $this->assertDatabaseHas('contact_forms', ['id' => $contactForm->id, 'url' => 'https://test2.com']);
        $this->assertDatabaseHas('contact_forms', ['id' => $contactForm->id, 'gender' =>  Gender::Male->value]);
        $this->assertDatabaseHas('contact_forms', ['id' => $contactForm->id, 'age' => Age::Over50->value]);
        $this->assertDatabaseHas('contact_forms', ['id' => $contactForm->id, 'contact' => 'お問い合わせ内容2']);

        // 元の画像が削除されたことをテスト
        $this->assertDatabaseMissing('contact_form_images', ['id' => $contactFormImage->id]);

        // 新しい画像が登録されたことをテスト
        $this->assertDatabaseMissing('contact_form_images', ['id' => $contactFormImage->id]);
        $updatedContactFormImage = ContactFormImage::where(['contact_form_id' => $contactForm->id]);
        $fileNames = $updatedContactFormImage->pluck('file_name')->all();
        $this->assertSame(['image2.jpg', 'image3.jpg'], $fileNames);

        // テスト後にファイルを削除
        Storage::delete('contact/image2.jpg');
        Storage::delete('contact/image3.jpg');
    }

}