<?php

namespace Feature\Http\Controllers\Admin\ContactForm;

use App\Domain\Entities\Admin;
use App\Domain\Entities\ContactForm;
use App\Domain\Entities\ContactFormImage;
use App\Enums\Age;
use App\Enums\Gender;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use tests\TestCase;

class DetailControllerTest extends TestCase
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
     * お問い合わせ詳細画面表示のテスト
     */
    public function testShow(): void
    {
        /** @var Admin $admin */
        $admin = Admin::factory()->create([
            'name' => '管理者A',
            'role' => 'manager'
        ]);
        $this->actingAs($admin, 'admin');

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

        $response = $this->get(route('admin.contact.show', $contactForm));
        $response->assertSuccessful();
        $response->assertSee('user1');
        $response->assertSee('title1');
        $response->assertSee('111@test.com');
        $response->assertSee('https://test.com');
        $response->assertSee(Gender::Female->label());
        $response->assertSee(Age::Over40->label());
        $response->assertSee('お問い合わせ内容');
        $response->assertSee('contact/image1.jpg');
        $response->assertSee('contact/image2.jpg');
    }

    /**
     * お問い合わせ詳細画面 削除のテスト
     */
    public function testDestroy(): void
    {
        /** @var ContactForm $contactForm */
        $contactForm = ContactForm::factory([
            'user_name' => 'user1',
            'title' => 'title1',
        ])->create();
        /** @var ContactFormImage $contactFormImage1 */
        $contactFormImage1 = ContactFormImage::factory(['contact_form_id' => $contactForm->id])->create();
        /** @var ContactFormImage $contactFormImage2 */
        $contactFormImage2 = ContactFormImage::factory(['contact_form_id' => $contactForm->id])->create();

        /** @var Admin $admin1 */
        $admin1 = Admin::factory()->create([
            'name' => '管理者1',
            'role' => 'manager'
        ]);
        $this->actingAs($admin1, 'admin');

        // manager権限ではアクセスできないことのテスト
        $response = $this->delete(route('admin.contact.destroy', $contactForm));
        $response->assertForbidden();

        /** @var Admin $admin2 */
        $admin2 = Admin::factory()->create([
            'name' => '管理者2',
            'role' => 'high-manager'
        ]);
        $this->actingAs($admin2, 'admin');

        $redirectResponse = $this->delete(route('admin.contact.destroy', $contactForm));
        $response = $this->get($redirectResponse->headers->get('Location'));
        $response->assertSuccessful();

        // データが削除されたことをテスト
        $this->assertDatabaseMissing('contact_forms', ['id' => $contactForm->id]);
        $this->assertDatabaseMissing('contact_form_images', ['id' => $contactFormImage1->id]);
        $this->assertDatabaseMissing('contact_form_images', ['id' => $contactFormImage2->id]);
    }
}
