<?php

namespace Requests\Api\ContactForm;

use App\Enums\Age;
use App\Enums\Gender;
use App\Http\Requests\Api\ContactForm\StoreRequest;
use Exception;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class StoreRequestTest extends TestCase
{
    private StoreRequest $request;
    /**
     * @var array<string, string>
     */
    private array $baseRequest;

    public function setUp(): void
    {
        parent::setUp();
        $this->request = new StoreRequest();
        $this->baseRequest = [
            'user_name' => 'user1',
            'title' => 'titleA',
            'email' => 'user1@test.com',
            'gender' => Gender::Male->value,
            'age' => Age::Over20->value,
            'contact' => '1234567890abcdef',
            'url' => 'https://test.co.jp',
            'caution' => 'caution',
        ];
    }

    /**
     * @test
     * @group validate
     * @param array<string> $attrs 変更する値の配列
     * @param boolean $expect 期待されるバリデーション結果
     * @param string $attribute 属性の名称
     * @param array<string> $messages 期待されるエラーメッセージ
     * @throws Exception
     * @dataProvider dataValidate
     */
    public function validate(array $attrs, bool $expect, string $attribute, array $messages): void
    {
        //リクエストデータ作成
        $requestData = [...$this->baseRequest, ...$attrs];
        //バリデーションルール取得
        $rules     = $this->request->rules();
        $validator = Validator::make($requestData, $rules, $this->request->messages(), $this->request->attributes());

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
     * @return array<string, mixed>
     */
    public static function dataValidate(): array
    {
        return [
            'NG : user_name 必須条件を満たさない' => [
                'attrs'     => ['user_name' => null],
                'expect'    => false,
                'attribute' => 'user_name',
                'messages' => [
                    '氏名を入力してください。'
                ]
            ],
            'NG : user_name 文字数上限を超えている' => [
                'attrs'     => ['user_name' => implode("", range(1, 51))],
                'expect'    => false,
                'attribute' => 'user_name',
                'messages' => [
                    '氏名には50文字以下の文字列を指定してください。'
                ]
            ],
            'OK : user_name が正常' => [
                'attrs'     => ['user_name' => 'user1'],
                'expect'    => true,
                'attribute' => 'user_name',
                'messages' => []
            ],
            'NG : title 必須条件を満たさない' => [
                'attrs'     => ['title' => null],
                'expect'    => false,
                'attribute' => 'title',
                'messages' => [
                    '件名を入力してください。'
                ]
            ],
            'NG : title 文字数上限を超えている' => [
                'attrs'     => ['title' => implode("", range(1, 51))],
                'expect'    => false,
                'attribute' => 'title',
                'messages' => [
                    '件名には50文字以下の文字列を指定してください。'
                ]
            ],
            'OK : title が正常' => [
                'attrs'     => ['title' => 'titleA'],
                'expect'    => true,
                'attribute' => 'title',
                'messages' => []
            ],
            'NG : email 必須条件を満たさない' => [
                'attrs'     => ['email' => null],
                'expect'    => false,
                'attribute' => 'email',
                'messages' => [
                    'メールアドレスを入力してください。'
                ]
            ],
            'OK : email 文字数上限を超えている' => [
                'attrs'     => ['email' => implode("", range(1, 56)) . '@test.com'],
                'expect'    => false,
                'attribute' => 'email',
                'messages' => [
                    'メールアドレスには64文字以下の文字列を指定してください。'
                ]
            ],
            'NG : email メールアドレスとして不正' => [
                'attrs'     => ['email' => 'abcde'],
                'expect'    => false,
                'attribute' => 'email',
                'messages' => [
                    'メールアドレスには正しい形式のメールアドレスを指定してください。'
                ]
            ],
            'OK : email が正常' => [
                'attrs'     => ['email' => 'user1@test.com'],
                'expect'    => true,
                'attribute' => 'email',
                'messages' => []
            ],
            'NG : gender 必須条件を満たさない' => [
                'attrs'     => ['gender' => null],
                'expect'    => false,
                'attribute' => 'gender',
                'messages' => [
                    '性別を入力してください。'
                ]
            ],
            'NG : gender 不正な文字' => [
                'attrs'     => ['gender' => '不正'],
                'expect'    => false,
                'attribute' => 'gender',
                'messages' => [
                    '性別 の値が不正です。'
                ]
            ],
            'OK : gender が正常' => [
                'attrs'     => ['gender' => Gender::Male->value],
                'expect'    => true,
                'attribute' => 'gender',
                'messages' => []
            ],
            'NG : age 必須条件を満たさない' => [
                'attrs'     => ['age' => null],
                'expect'    => false,
                'attribute' => 'age',
                'messages' => [
                    '年齢を入力してください。'
                ]
            ],
            'NG : age 不正な文字' => [
                'attrs'     => ['age' => '不正'],
                'expect'    => false,
                'attribute' => 'age',
                'messages' => [
                    '年齢 の値が不正です。'
                ]
            ],
            'OK : age が正常' => [
                'attrs'     => ['age' => Age::Over20->value],
                'expect'    => true,
                'attribute' => 'age',
                'messages' => []
            ],
            'NG : contact 必須条件を満たさない' => [
                'attrs'     => ['contact' => null],
                'expect'    => false,
                'attribute' => 'contact',
                'messages' => [
                    'お問い合わせ内容を入力してください。'
                ]
            ],
            'NG : contact 文字数上限を超えている' => [
                'attrs'     => ['contact' => implode("", range(1, 201))],
                'expect'    => false,
                'attribute' => 'contact',
                'messages' => [
                    'お問い合わせ内容には200文字以下の文字列を指定してください。'
                ]
            ],
            'OK : contact が正常' => [
                'attrs'     => ['contact' => 'abcde'],
                'expect'    => true,
                'attribute' => 'contact',
                'messages' => []
            ],
            'OK : url 文字数上限を超えている' => [
                'attrs'     => ['url' => 'dummy'],
                'expect'    => false,
                'attribute' => 'url',
                'messages' => [
                    'ホームページURLには正しい形式のURLを指定してください。'
                ]
            ],
            'OK : url が正常' => [
                'attrs'     => ['url' => 'https://111.test.com'],
                'expect'    => true,
                'attribute' => 'url',
                'messages' => []
            ],
            'NG : caution 必須条件を満たさない' => [
                'attrs'     => ['caution' => null],
                'expect'    => false,
                'attribute' => 'caution',
                'messages' => [
                    '注意事項を入力してください。'
                ]
            ],
            'OK : caution が正常' => [
                'attrs'     => ['caution' => 'caution'],
                'expect'    => true,
                'attribute' => 'caution',
                'messages' => []
            ],

        ];
    }
}
