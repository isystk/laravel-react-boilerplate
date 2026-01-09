<?php

namespace Tests\Feature\Http\Controllers\Api;

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

        $response = $this->post(route('api.contact.store'), [
            'user_name'   => $user1->name,
            'email'       => $user1->email,
            'title'       => 'タイトル1',
            'url'         => 'https://aaa.test.com',
            'gender'      => Gender::Female->value,
            'age'         => Age::Over40->value,
            'contact'     => 'お問い合わせ1',
            'image_files' => [
                UploadedFile::fake()->image('image1.jpg'),
                UploadedFile::fake()->image('image2.jpg'),
                UploadedFile::fake()->image('image3.jpg'),
            ],
            'caution' => 1,
        ]);
        $response->assertSuccessful();

        // ファイルが存在することをテスト
        Storage::disk('s3')->assertExists('contact/image1.jpg');
        Storage::disk('s3')->assertExists('contact/image2.jpg');
        Storage::disk('s3')->assertExists('contact/image3.jpg');
    }
}
