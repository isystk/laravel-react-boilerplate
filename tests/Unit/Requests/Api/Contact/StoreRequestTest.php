<?php

namespace Tests\Unit\Requests\Api\Contact;

use App\Enums\ContactType;
use App\Http\Requests\Api\Contact\StoreRequest;
use Exception;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTest;

class StoreRequestTest extends BaseTest
{
    private StoreRequest $request;

    /**
     * @var array<string, int|string>
     */
    private array $baseRequest;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request     = new StoreRequest;
        $this->baseRequest = [
            'title'   => 'titleA',
            'type'    => ContactType::Service->value,
            'message' => '1234567890abcdef',
            'caution' => 'caution',
        ];
    }

    /**
     * @param array<string> $attrs     変更する値の配列
     * @param bool          $expect    期待されるバリデーション結果
     * @param string        $attribute 属性の名称
     * @param array<string> $messages  期待されるエラーメッセージ
     *
     * @throws Exception
     */
    #[Test]
    #[Group('validate')]
    #[DataProvider('dataValidate')]
    public function validate(array $attrs, bool $expect, string $attribute, array $messages): void
    {
        // リクエストデータ作成
        $requestData = [...$this->baseRequest, ...$attrs];
        // バリデーションルール取得
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
     * @return array<string, mixed>
     */
    public static function dataValidate(): array
    {
        return [
            'NG : title 必須条件を満たさない' => [
                'attrs'     => ['title' => null],
                'expect'    => false,
                'attribute' => 'title',
                'messages'  => [
                    '件名を入力してください。',
                ],
            ],
            'NG : title 文字数上限を超えている' => [
                'attrs'     => ['title' => implode('', range(1, 101))],
                'expect'    => false,
                'attribute' => 'title',
                'messages'  => [
                    '件名には100文字以下の文字列を指定してください。',
                ],
            ],
            'OK : title が正常' => [
                'attrs'     => ['title' => 'titleA'],
                'expect'    => true,
                'attribute' => 'title',
                'messages'  => [],
            ],
            'NG : type 必須条件を満たさない' => [
                'attrs'     => ['type' => null],
                'expect'    => false,
                'attribute' => 'type',
                'messages'  => [
                    'お問い合わせ種類を入力してください。',
                ],
            ],
            'NG : type 不正な文字' => [
                'attrs'     => ['type' => '不正'],
                'expect'    => false,
                'attribute' => 'type',
                'messages'  => [
                    'お問い合わせ種類の値が不正です。',
                ],
            ],
            'OK : type が正常' => [
                'attrs'     => ['type' => ContactType::Service->value],
                'expect'    => true,
                'attribute' => 'type',
                'messages'  => [],
            ],
            'NG : message 必須条件を満たさない' => [
                'attrs'     => ['message' => null],
                'expect'    => false,
                'attribute' => 'message',
                'messages'  => [
                    'お問い合わせ内容を入力してください。',
                ],
            ],
            'NG : message 文字数上限を超えている' => [
                'attrs'     => ['message' => implode('', range(1, 501))],
                'expect'    => false,
                'attribute' => 'message',
                'messages'  => [
                    'お問い合わせ内容には500文字以下の文字列を指定してください。',
                ],
            ],
            'OK : message が正常' => [
                'attrs'     => ['message' => 'abcde'],
                'expect'    => true,
                'attribute' => 'message',
                'messages'  => [],
            ],
            'OK : caution が正常' => [
                'attrs'     => ['caution' => 'caution'],
                'expect'    => true,
                'attribute' => 'caution',
                'messages'  => [],
            ],
        ];
    }
}
