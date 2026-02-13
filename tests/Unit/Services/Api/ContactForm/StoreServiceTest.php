<?php

namespace Tests\Unit\Services\Api\ContactForm;

use App\Domain\Entities\ContactForm;
use App\Dto\Request\Api\ContactForm\CreateDto;
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

        $request              = new StoreRequest;
        $request['user_name'] = 'aaa';
        $request['title']     = 'タイトル1';
        $request['email']     = 'aaa@test.com';
        $request['url']       = 'https://aaa.test.com';
        $request['gender']    = Gender::Male->value;
        $request['age']       = Age::Over30->value;
        $request['contact']   = 'お問い合わせ1';
        $dto                  = new CreateDto($request);
        $dto->imageFile      = UploadedFile::fake()->image('file1.jpg');
        $contactForm = $this->service->save($dto);

        // データが登録されたことをテスト
        $updatedContactForm = ContactForm::find($contactForm->id);
        $this->assertEquals('aaa', $updatedContactForm->user_name);
        $this->assertEquals('タイトル1', $updatedContactForm->title);
        $this->assertEquals('aaa@test.com', $updatedContactForm->email);
        $this->assertEquals('https://aaa.test.com', $updatedContactForm->url);
        $this->assertEquals(Gender::Male, $updatedContactForm->gender);
        $this->assertEquals(Age::Over30, $updatedContactForm->age);
        $this->assertEquals('お問い合わせ1', $updatedContactForm->contact);
        $this->assertEquals('file1.jpg', $updatedContactForm->image_file_name);

        // 新しい画像が登録されたこと
        Storage::disk('s3')->assertExists('contact/file1.jpg');
    }
}
