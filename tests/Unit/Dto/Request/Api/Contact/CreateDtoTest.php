<?php

namespace Tests\Unit\Dto\Request\Api\Contact;

use App\Dto\Request\Api\Contact\CreateDto;
use App\Enums\ContactType;
use App\Http\Requests\Api\Contact\StoreRequest;
use Illuminate\Http\UploadedFile;
use Tests\BaseTest;

class CreateDtoTest extends BaseTest
{
    public function test_construct_リクエストから各プロパティが正しく設定されること(): void
    {
        $request = StoreRequest::create('/', 'POST', [
            'title'   => 'お問い合わせ件名',
            'type'    => ContactType::Service->value,
            'message' => 'APIからのお問い合わせ',
        ]);

        $dto = new CreateDto($request);

        $this->assertSame('お問い合わせ件名', $dto->title);
        $this->assertSame(ContactType::Service, $dto->type);
        $this->assertSame('APIからのお問い合わせ', $dto->message);
        $this->assertNull($dto->imageFile);
    }

    public function test_construct_genderがFemaleでageがOver60の場合正しく設定されること(): void
    {
        $request = StoreRequest::create('/', 'POST', [
            'title'   => '件名',
            'type'    => ContactType::Service->value,
            'message' => '内容',
        ]);

        $dto = new CreateDto($request);

        $this->assertSame(ContactType::Service, $dto->type);
    }

    public function test_construct_imageBase64がある場合UploadedFileに変換されること(): void
    {
        $base64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

        $request = StoreRequest::create('/', 'POST', [
            'title'         => '件名',
            'type'          => ContactType::Service->value,
            'message'       => '内容',
            'image_base_64' => $base64,
        ]);

        $dto = new CreateDto($request);

        $this->assertInstanceOf(UploadedFile::class, $dto->imageFile);
    }

    public function test_construct_imageBase64がない場合imageFileがnullになること(): void
    {
        $request = StoreRequest::create('/', 'POST', [
            'title'   => '件名',
            'type'    => ContactType::Service->value,
            'message' => '内容',
        ]);

        $dto = new CreateDto($request);

        $this->assertNull($dto->imageFile);
    }
}
