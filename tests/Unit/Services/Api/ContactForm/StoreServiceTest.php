<?php

namespace Tests\Unit\Services\Api\ContactForm;

use App\Domain\Entities\ContactForm;
use App\Domain\Entities\ContactFormImage;
use App\Enums\Age;
use App\Enums\Gender;
use App\Http\Requests\Api\ContactForm\StoreRequest;
use App\Services\Api\ContactForm\StoreService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\BaseTest;

class StoreServiceTest extends BaseTest
{
    use RefreshDatabase;

    private StoreService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(StoreService::class);
    }

    public function test_update(): void
    {
        Storage::fake('s3');

        $request = new StoreRequest;
        $request['user_name'] = 'aaa';
        $request['title'] = 'タイトル1';
        $request['email'] = 'aaa@test.com';
        $request['url'] = 'https://aaa.test.com';
        $request['gender'] = Gender::Male->value;
        $request['age'] = Age::Over30->value;
        $request['contact'] = 'お問い合わせ1';
        $request['image_files'] = [
            UploadedFile::fake()->image('file1.jpg'),
            UploadedFile::fake()->image('file2.jpg'),
            UploadedFile::fake()->image('file3.jpg'),
        ];
        $contactForm = $this->service->save($request);

        // データが登録されたことをテスト
        $updatedContactForm = ContactForm::find($contactForm->id);
        $this->assertEquals('aaa', $updatedContactForm->user_name);
        $this->assertEquals('タイトル1', $updatedContactForm->title);
        $this->assertEquals('aaa@test.com', $updatedContactForm->email);
        $this->assertEquals('https://aaa.test.com', $updatedContactForm->url);
        $this->assertEquals(Gender::Male, $updatedContactForm->gender);
        $this->assertEquals(Age::Over30, $updatedContactForm->age);
        $this->assertEquals('お問い合わせ1', $updatedContactForm->contact);

        // 新しい画像が登録されたことをテスト
        $createdContactFormImage = ContactFormImage::where(['contact_form_id' => $contactForm->id]);
        $fileNames = $createdContactFormImage->pluck('file_name')->all();
        $this->assertSame(['file1.jpg', 'file2.jpg', 'file3.jpg'], $fileNames);
        Storage::disk('s3')->assertExists('contact/file1.jpg');
        Storage::disk('s3')->assertExists('contact/file2.jpg');
        Storage::disk('s3')->assertExists('contact/file3.jpg');
    }
}
