<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Domain\Entities\Contact;
use App\Enums\ContactType;
use App\Services\Api\Contact\StoreService;
use Exception;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\BaseTest;

class ContactControllerTest extends BaseTest
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_store_画像あり(): void
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
            'title'         => 'タイトル1',
            'type'          => ContactType::Service->value,
            'message'       => 'お問い合わせ1',
            'image_base_64' => $base64Image,
            'caution'       => 1,
        ]);
        $response->assertSuccessful();

        $contact = Contact::first();
        $this->assertSame($user1->id, $contact->user->id);
        $this->assertSame('タイトル1', $contact->title);
        $this->assertSame(ContactType::Service, $contact->type);
        $this->assertSame('お問い合わせ1', $contact->message);

        // ファイルが存在することをテスト
        $image = $contact->image;
        $this->assertNotNull($image);
        Storage::disk('s3')->assertExists($image->getS3Path());
    }

    public function test_store_画像なし(): void
    {
        $user1 = $this->createDefaultUser([
            'name'  => 'user1',
            'email' => 'user1@test.com',
        ]);
        $this->actingAs($user1);

        $response = $this->post(route('api.contact.store'), [
            'title'         => 'タイトル1',
            'type'          => ContactType::Service->value,
            'message'       => 'お問い合わせ1',
            'image_base_64' => null,
            'caution'       => 1,
        ]);
        $response->assertSuccessful();

        $contact = Contact::first();
        $this->assertSame($user1->id, $contact->user->id);
        $this->assertSame('タイトル1', $contact->title);
        $this->assertSame(ContactType::Service, $contact->type);
        $this->assertSame('お問い合わせ1', $contact->message);
    }

    public function test_store_error(): void
    {
        $user = $this->createDefaultUser();
        $this->actingAs($user);

        $this->mock(StoreService::class, function ($mock) {
            $mock->shouldReceive('save')->andThrow(new Exception('Store error'));
        });

        $response = $this->postJson(route('api.contact.store'), [
            'title'   => 'タイトル1',
            'type'    => ContactType::Service->value,
            'message' => 'お問い合わせ1',
            'caution' => 1,
        ]);

        $response->assertStatus(500);
        $response->assertJson([
            'result' => false,
            'error'  => ['messages' => ['Store error']],
        ]);
    }
}
