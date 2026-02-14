<?php

namespace Tests\Unit\Dto\Request\Admin\Contact;

use App\Dto\Request\Admin\Contact\UpdateDto;
use App\Enums\Age;
use App\Enums\Gender;
use App\Http\Requests\Admin\Contact\UpdateRequest;
use Illuminate\Http\UploadedFile;
use Tests\BaseTest;

class UpdateDtoTest extends BaseTest
{
    public function test_construct_リクエストから各プロパティが正しく設定されること(): void
    {
        $request = UpdateRequest::create('/', 'POST', [
            'user_name' => 'テストユーザー',
            'title'     => 'お問い合わせ件名',
            'email'     => 'contact@example.com',
            'url'       => 'https://example.com',
            'gender'    => 0,
            'age'       => 1,
            'contact'   => 'お問い合わせ内容です',
        ]);

        $dto = new UpdateDto($request);

        $this->assertSame('テストユーザー', $dto->userName);
        $this->assertSame('お問い合わせ件名', $dto->title);
        $this->assertSame('contact@example.com', $dto->email);
        $this->assertSame('https://example.com', $dto->url);
        $this->assertSame(Gender::Male, $dto->gender);
        $this->assertSame(Age::Under19, $dto->age);
        $this->assertSame('お問い合わせ内容です', $dto->contact);
        $this->assertNull($dto->imageFile);
    }

    public function test_construct_genderがFemaleの場合正しく設定されること(): void
    {
        $request = UpdateRequest::create('/', 'POST', [
            'user_name' => 'テスト',
            'title'     => '件名',
            'email'     => 'test@example.com',
            'url'       => '',
            'gender'    => 1,
            'age'       => 3,
            'contact'   => '内容',
        ]);

        $dto = new UpdateDto($request);

        $this->assertSame(Gender::Female, $dto->gender);
        $this->assertSame(Age::Over30, $dto->age);
    }

    public function test_construct_imageBase64がある場合UploadedFileに変換されること(): void
    {
        $base64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

        $request = UpdateRequest::create('/', 'POST', [
            'user_name'     => 'テスト',
            'title'         => '件名',
            'email'         => 'test@example.com',
            'url'           => '',
            'gender'        => 0,
            'age'           => 1,
            'contact'       => '内容',
            'image_base_64' => $base64,
        ]);

        $dto = new UpdateDto($request);

        $this->assertInstanceOf(UploadedFile::class, $dto->imageFile);
    }

    public function test_construct_imageBase64がない場合imageFileがnullになること(): void
    {
        $request = UpdateRequest::create('/', 'POST', [
            'user_name' => 'テスト',
            'title'     => '件名',
            'email'     => 'test@example.com',
            'url'       => '',
            'gender'    => 0,
            'age'       => 1,
            'contact'   => '内容',
        ]);

        $dto = new UpdateDto($request);

        $this->assertNull($dto->imageFile);
    }
}
