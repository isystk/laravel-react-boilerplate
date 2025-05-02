<?php

namespace Http\Controllers\Api;

use App\Enums\Age;
use App\Enums\Gender;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ContactFormControllerTest extends TestCase
{

    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    /**
     * お問い合わせ登録APIのテスト
     */
    public function testStore(): void
    {
        $user1 = $this->createDefaultUser([
            'name' => 'user1',
            'email' => 'user1@test.com',
        ]);
        $this->actingAs($user1);

        $response = $this->post(route('api.contact.store'), [
            'user_name' => $user1->name,
            'email' => $user1->email,
            'title' => 'タイトル1',
            'url' => 'https://aaa.test.com',
            'gender' => Gender::Female->value,
            'age' => Age::Over40->value,
            'contact' => 'お問い合わせ1',
            'imageFile' => [
                UploadedFile::fake()->image('image1.jpg'),
                UploadedFile::fake()->image('image2.jpg'),
                UploadedFile::fake()->image('image3.jpg'),
            ],
            'caution' => 1,
        ]);
        $response->assertSuccessful();

        // テスト後にファイルを削除
        Storage::delete('contact/image1.jpg');
        Storage::delete('contact/image2.jpg');
        Storage::delete('contact/image3.jpg');
    }

}
