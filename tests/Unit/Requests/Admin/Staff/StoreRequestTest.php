<?php

namespace Requests\Admin\Staff;

use App\Enums\AdminRole;
use App\Http\Requests\Admin\Staff\StoreRequest;
use Exception;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
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
            'name' => 'user1',
            'email' => 'user1@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => AdminRole::Manager->value,
        ];
    }

    /**
     * @param array<string> $attrs 変更する値の配列
     * @param boolean $expect 期待されるバリデーション結果
     * @param string $attribute 属性の名称
     * @param array<string> $messages 期待されるエラーメッセージ
     * @throws Exception
     */
    #[Test]
    #[Group('validate')]
    #[DataProvider('dataValidate')]
    public function validate(array $attrs, bool $expect, string $attribute, array $messages): void
    {
        //リクエストデータ作成
        $requestData = [...$this->baseRequest, ...$attrs];
        $this->request->merge($requestData);
        //バリデーションルール取得
        $rules = $this->request->rules();
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
            'NG : name 必須条件を満たさない' => [
                'attrs' => ['name' => null],
                'expect' => false,
                'attribute' => 'name',
                'messages' => [
                    '氏名を入力してください。',
                ],
            ],
            'NG : name 文字数上限を超えている' => [
                'attrs' => ['name' => implode("", range(1, 51))],
                'expect' => false,
                'attribute' => 'name',
                'messages' => [
                    '氏名には50文字以下の文字列を指定してください。',
                ],
            ],
            'OK : name が正常' => [
                'attrs' => ['name' => 'user1'],
                'expect' => true,
                'attribute' => 'name',
                'messages' => [],
            ],
            'NG : email 必須条件を満たさない' => [
                'attrs' => ['email' => null],
                'expect' => false,
                'attribute' => 'email',
                'messages' => [
                    'メールアドレスを入力してください。',
                ],
            ],
            'OK : email 文字数上限を超えている' => [
                'attrs' => ['email' => implode("", range(1, 56)) . '@test.com'],
                'expect' => false,
                'attribute' => 'email',
                'messages' => [
                    'メールアドレスには64文字以下の文字列を指定してください。',
                ],
            ],
            'NG : email メールアドレスとして不正' => [
                'attrs' => ['email' => 'abcde'],
                'expect' => false,
                'attribute' => 'email',
                'messages' => [
                    'メールアドレスには正しい形式のメールアドレスを指定してください。',
                ],
            ],
            'OK : email が正常' => [
                'attrs' => ['email' => 'user1@test.com'],
                'expect' => true,
                'attribute' => 'email',
                'messages' => [],
            ],
            'NG : password 必須条件を満たさない' => [
                'attrs' => ['password' => null],
                'expect' => false,
                'attribute' => 'password',
                'messages' => [
                    'パスワードを入力してください。',
                ],
            ],
            'NG : password 文字数上限を超えている' => [
                'attrs' => [
                    'password' => implode("", range(1, 13)),
                    'password_confirmation' => implode("", range(1, 13)),
                ],
                'expect' => false,
                'attribute' => 'password',
                'messages' => [
                    'パスワードには12文字以下の文字列を指定してください。',
                ],
            ],
            'NG : password 確認用パスワードと一致しない' => [
                'attrs' => [
                    'password' => 'password1',
                    'password_confirmation' => 'password2',
                ],
                'expect' => false,
                'attribute' => 'password',
                'messages' => [
                    'パスワードが確認用の値と一致しません。',
                ],
            ],
            'NG : password パスワードには8文字未満' => [
                'attrs' => [
                    'password' => '1234567',
                    'password_confirmation' => '1234567',
                ],
                'expect' => false,
                'attribute' => 'password',
                'messages' => [
                    'パスワードには8文字以上の文字列を指定してください。',
                ],
            ],
            'OK : password が正常' => [
                'attrs' => [
                    'password' => 'password',
                    'password_confirmation' => 'password',
                ],
                'expect' => true,
                'attribute' => 'password',
                'messages' => [],
            ],
            'NG : role 必須条件を満たさない' => [
                'attrs' => ['role' => null],
                'expect' => false,
                'attribute' => 'role',
                'messages' => [
                    '権限を入力してください。',
                ],
            ],
            'NG : role 不正な文字' => [
                'attrs' => ['role' => '不正'],
                'expect' => false,
                'attribute' => 'role',
                'messages' => [
                    '権限の値が不正です。',
                ],
            ],
            'OK : role が正常' => [
                'attrs' => ['role' => AdminRole::Manager->value],
                'expect' => true,
                'attribute' => 'role',
                'messages' => [],
            ],
        ];
    }
}
