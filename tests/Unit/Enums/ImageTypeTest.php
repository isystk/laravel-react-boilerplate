<?php

namespace Tests\Unit\Enums;

use App\Enums\ImageType;
use Tests\BaseTest;

class ImageTypeTest extends BaseTest
{
    public function test_label_各ケースのラベルが返却されること(): void
    {
        $this->assertSame(__('enums.ImageType1'), ImageType::Stock->label());
        $this->assertSame(__('enums.ImageType2'), ImageType::Contact->label());
        $this->assertSame(__('enums.ImageType3'), ImageType::User->label());
    }

    public function test_type_各ケースのタイプ文字列が返却されること(): void
    {
        $this->assertSame('stock', ImageType::Stock->type());
        $this->assertSame('contact', ImageType::Contact->type());
        $this->assertSame('user', ImageType::User->type());
    }

    public function test_getByType_タイプ文字列からEnumが返却されること(): void
    {
        $this->assertSame(ImageType::Stock, ImageType::getByType('stock'));
        $this->assertSame(ImageType::Contact, ImageType::getByType('contact'));
        $this->assertSame(ImageType::User, ImageType::getByType('user'));
    }

    public function test_getByType_不正なタイプの場合は例外がスローされること(): void
    {
        $this->expectException(\RuntimeException::class);
        ImageType::getByType('invalid');
    }
}
