<?php

namespace Http\Controllers\Admin\ContactForm;

use App\Enums\Age;
use App\Enums\Gender;
use App\Services\Admin\ContactForm\DestroyService;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class DetailControllerTest extends BaseTest
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_show(): void
    {
        $admin = $this->createDefaultAdmin([
            'name' => '管理者A',
            'role' => 'manager',
        ]);
        $this->actingAs($admin, 'admin');

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

    public function test_destroy(): void
    {
        $contactForm = $this->createDefaultContactForm([
            'user_name' => 'user1',
            'title' => 'title1',
        ]);
        $contactFormImage1 = $this->createDefaultContactFormImage(['contact_form_id' => $contactForm->id]);
        $contactFormImage2 = $this->createDefaultContactFormImage(['contact_form_id' => $contactForm->id]);

        $admin1 = $this->createDefaultAdmin([
            'name' => '管理者1',
            'role' => 'manager',
        ]);
        $this->actingAs($admin1, 'admin');

        // manager権限ではアクセスできないことのテスト
        $response = $this->delete(route('admin.contact.destroy', $contactForm));
        $response->assertForbidden();

        $admin2 = $this->createDefaultAdmin([
            'name' => '管理者2',
            'role' => 'high-manager',
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

    public function test_destroy_例外発生時にロールバックされ例外がスローされること(): void
    {
        $this->withoutExceptionHandling();

        $contactForm = $this->createDefaultContactForm();

        $admin = $this->createDefaultAdmin([
            'role' => 'high-manager',
        ]);
        $this->actingAs($admin, 'admin');

        $this->mock(DestroyService::class, function ($mock) {
            $mock->shouldReceive('delete')
                ->once()
                ->andThrow(new \Exception('Database Error'));
        });

        // 例外がスローされることを期待
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Database Error');

        $this->delete(route('admin.contact.destroy', $contactForm));
    }
}
