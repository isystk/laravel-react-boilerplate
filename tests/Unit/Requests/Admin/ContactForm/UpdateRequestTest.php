<?php

namespace Tests\Unit\Request\Admin\ContactForm;

use App\Enums\Age;
use App\Enums\Gender;
use App\Http\Requests\Admin\ContactForm\UpdateRequest;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class UpdateRequestTest extends TestCase
{
    private UpdateRequest $request;
    private array $baseRequest;

    public function setUp(): void
    {
        parent::setUp();
        $this->request = new UpdateRequest();
        $this->baseRequest = [
            'user_name' => 'user1',
            'title' => 'titleA',
            'email' => 'user1@test.com',
            'gender' => Gender::Male->value,
            'age' => Age::Over20->value,
            'contact' => '1234567890abcdef',
            'url' => 'https://test.co.jp',
        ];
    }

    /**
     * @test
     * @group validate
     * @param array $attrs 変更する値の配列
     * @param boolean $expect 期待されるバリデーション結果
     * @param string $attribute 属性の名称
     * @param array $messages 期待されるエラーメッセージ
     * @throws Exception
     * @dataProvider dataValidate
     */
    public function validate(array $attrs, bool $expect, string $attribute, array $messages): void
    {
        //リクエストデータ作成
        $requestData = [...$this->baseRequest, ...$attrs];
        //バリデーションルール取得
        $rules     = $this->request->rules();
        $validator = Validator::make($requestData, $rules);

        // 結果保証
        $this->assertEquals($expect, $validator->passes());

        if (!$expect) {
            // 対象attributeでエラーになっているかテスト
            $this->assertTrue($validator->errors()->hasAny($attribute));

            // エラーメッセージの内容が正しいかテスト
            $this->assertSame($messages, $validator->errors()->messages()[$attribute]);
        }
    }

    /**
     * バリデーションテストデータ
     * @return array
     */
    public static function dataValidate(): array
    {
        return [
            'NG : user_name 必須条件を満たさない' => [
                'attrs'     => ['user_name' => null],
                'expect'    => false,
                'attribute' => 'csv_file',
                'messages' => [
                    'user_nameを入力してください。'
                ]
            ],
        ];
    }
}
