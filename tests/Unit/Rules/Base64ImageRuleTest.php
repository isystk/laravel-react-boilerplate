<?php

namespace Rules;

use App\Rules\Base64ImageRule;
use Illuminate\Http\UploadedFile;
use Tests\BaseTest;

class Base64ImageRuleTest extends BaseTest
{
    public function test_ファイルが画像でない場合、エラーとなること(): void
    {
        $rule = new Base64ImageRule;
        $this->assertFalse($rule->passes('file', null));
        $this->assertFalse($rule->passes('file', ''));
        $this->assertFalse($rule->passes('file', 'dummy'));
    }

    public function test_ファイルがjpegの場合(): void
    {
        $image = UploadedFile::fake()->image('test.jpg');
        $base64 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($image->getPathname()));

        $rule = new Base64ImageRule(['png']);
        $this->assertFalse($rule->passes('image', $base64), '想定と異なる拡張子なのでFail');

        $rule = new Base64ImageRule(['jpeg']);
        $this->assertTrue($rule->passes('image', $base64), '想定通り拡張子なのでPass');
    }

    public function test_ファイルがpngの場合(): void
    {
        $image = UploadedFile::fake()->image('test.png');
        $base64 = 'data:image/png;base64,' . base64_encode(file_get_contents($image->getPathname()));

        $rule = new Base64ImageRule(['gif']);
        $this->assertFalse($rule->passes('image', $base64), '想定と異なる拡張子なのでFail');

        $rule = new Base64ImageRule(['png']);
        $this->assertTrue($rule->passes('image', $base64), '想定通り拡張子なのでPass');
    }

    public function test_ファイルがgifの場合(): void
    {
        $image = imagecreatetruecolor(100, 100);
        ob_start();
        imagegif($image);
        $base64 = 'data:image/gif;base64,' . base64_encode(ob_get_clean());

        $rule = new Base64ImageRule(['jpeg']);
        $this->assertFalse($rule->passes('image', $base64), '想定と異なる拡張子なのでFail');

        $rule = new Base64ImageRule(['gif']);
        $this->assertTrue($rule->passes('image', $base64), '想定通り拡張子なのでPass');
    }
}
