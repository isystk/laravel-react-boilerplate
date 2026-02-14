<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Domain\Entities\ContactForm;
use App\Enums\Age;
use App\Enums\Gender;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\BaseTest;

class ContactFormControllerTest extends BaseTest
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_store(): void
    {
        Storage::fake('s3');

        $user1 = $this->createDefaultUser([
            'name'  => 'user1',
            'email' => 'user1@test.com',
        ]);
        $this->actingAs($user1);

        // テスト用の画像を生成
        $image = UploadedFile::fake()->image('image1.jpg');

        // Base64に変換（Data URI形式）
        $base64Image = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($image->getPathname()));

        $response = $this->post(route('api.contact.store'), [
            'user_name'     => $user1->name,
            'email'         => $user1->email,
            'title'         => 'タイトル1',
            'url'           => 'https://aaa.test.com',
            'gender'        => Gender::Female->value,
            'age'           => Age::Over40->value,
            'contact'       => 'お問い合わせ1',
            'image_base_64' => $base64Image,
            'caution'       => 1,
        ]);
        $response->assertSuccessful();

        // ファイルが存在することをテスト
        $contactForm = ContactForm::where('email', $user1->email)->first();
        $this->assertNotNull($contactForm->image_id);
        Storage::disk('s3')->assertExists('contact/' . $contactForm->image->file_name);
    }
}
