<?php

namespace Tests\Unit\Services\Admin\ContactForm;

use App\Domain\Entities\ContactForm;
use App\Domain\Entities\ContactFormImage;
use App\Enums\Age;
use App\Enums\Gender;
use App\Services\Admin\ContactForm\UpdateService;
use App\Http\Requests\Admin\ContactForm\UpdateRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UpdateServiceTest extends TestCase
{

    use RefreshDatabase;

    private UpdateService $service;

    /**
     * 各テストの実行前に起動する。
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(UpdateService::class);
    }

    /**
     * インスタンスがテスト対象のクラスであることのテスト
     */
    public function testInstanceOf(): void
    {
        $this->assertInstanceOf(UpdateService::class, $this->service);
    }

    /**
     * updateのテスト
     */
    public function testUpdate(): void
    {
        /** @var ContactForm $contactForm */
        $contactForm = ContactForm::factory()->create([
            'user_name' => 'aaa',
            'title' => 'タイトル1',
            'email' => 'aaa@test.com',
            'url' => 'https://aaa.test.com',
            'gender' => Gender::Male->value,
            'age' => Age::Over30->value,
            'contact' => 'お問い合わせ1'
        ]);
        /** @var ContactFormImage $contactFormImage */
        $contactFormImage = ContactFormImage::factory()->create([
            'contact_form_id' => $contactForm->id,
            'file_name' => 'file1.jpg'
        ]);

        $request = new UpdateRequest();
        $request['user_name'] = 'bbb';
        $request['title'] = 'タイトル2';
        $request['email'] = 'bbb@test.com';
        $request['url'] = 'https://bbb.test.com';
        $request['gender'] =  Gender::Female->value;
        $request['age'] = Age::Over40->value;
        $request['contact'] = 'お問い合わせ2';
        $request['image_files'] = [
            UploadedFile::fake()->image('file2.jpg'),
            UploadedFile::fake()->image('file3.jpg')
        ];
        $this->service->update($contactForm->id, $request);

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
        $this->assertDatabaseHas('contact_form_images', ['contact_form_id' => $contactForm->id, 'file_name' => 'file2.jpg']);
        $this->assertDatabaseHas('contact_form_images', ['contact_form_id' => $contactForm->id, 'file_name' => 'file3.jpg']);

        // テスト後にファイルを削除
        Storage::delete('contact/file2.jpg');
        Storage::delete('contact/file3.jpg');
    }
}
