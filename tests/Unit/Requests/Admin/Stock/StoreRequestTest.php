<?php

namespace Tests\Unit\Requests\Admin\Stock;

use App\Http\Requests\Admin\Stock\StoreRequest;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTest;

class StoreRequestTest extends BaseTest
{
    use RefreshDatabase;

    private StoreRequest $request;

    /**
     * @var array<string, int|string>
     */
    private array $baseRequest;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request     = new StoreRequest;
        $dummyImage        = UploadedFile::fake()->image('test.jpg');
        $base64String      = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($dummyImage->path()));
        $fileName          = $dummyImage->getClientOriginalName();
        $this->baseRequest = [
            'name'        => 'user1',
            'price'       => 1234,
            'detail'      => 'abcde',
            'quantity'    => 1,
            'imageBase64' => $base64String,
            'fileName'    => $fileName,
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
        $this->request->merge($requestData);
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
            'NG : name 必須条件を満たさない' => [
                'attrs'     => ['name' => null],
                'expect'    => false,
                'attribute' => 'name',
                'messages'  => [
                    '商品名を入力してください。',
                ],
            ],
            'NG : name 文字数上限を超えている' => [
                'attrs'     => ['name' => implode('', range(1, 51))],
                'expect'    => false,
                'attribute' => 'name',
                'messages'  => [
                    '商品名には50文字以下の文字列を指定してください。',
                ],
            ],
            'OK : name が正常' => [
                'attrs'     => ['name' => 'user1'],
                'expect'    => true,
                'attribute' => 'name',
                'messages'  => [],
            ],
            'NG : price 必須条件を満たさない' => [
                'attrs'     => ['price' => null],
                'expect'    => false,
                'attribute' => 'price',
                'messages'  => [
                    '価格を入力してください。',
                ],
            ],
            'NG : price 数値ではない' => [
                'attrs'     => ['price' => 'abced'],
                'expect'    => false,
                'attribute' => 'price',
                'messages'  => [
                    '価格には数値を指定してください。',
                ],
            ],
            'OK : price が正常' => [
                'attrs'     => ['price' => 12345],
                'expect'    => true,
                'attribute' => 'price',
                'messages'  => [],
            ],
            'NG : detail 必須条件を満たさない' => [
                'attrs'     => ['detail' => null],
                'expect'    => false,
                'attribute' => 'detail',
                'messages'  => [
                    '商品説明を入力してください。',
                ],
            ],
            'NG : detail 文字数上限を超えている' => [
                'attrs'     => ['detail' => implode('', range(1, 501))],
                'expect'    => false,
                'attribute' => 'detail',
                'messages'  => [
                    '商品説明には500文字以下の文字列を指定してください。',
                ],
            ],
            'OK : detail が正常' => [
                'attrs'     => ['detail' => 'abcde'],
                'expect'    => true,
                'attribute' => 'detail',
                'messages'  => [],
            ],
            'NG : quantity 必須条件を満たさない' => [
                'attrs'     => ['quantity' => null],
                'expect'    => false,
                'attribute' => 'quantity',
                'messages'  => [
                    '在庫数を入力してください。',
                ],
            ],
            'NG : quantity 数値ではない' => [
                'attrs'     => ['quantity' => 'abced'],
                'expect'    => false,
                'attribute' => 'quantity',
                'messages'  => [
                    '在庫数には数値を指定してください。',
                ],
            ],
            'OK : quantity が正常' => [
                'attrs'     => ['quantity' => 12345],
                'expect'    => true,
                'attribute' => 'quantity',
                'messages'  => [],
            ],
            'NG : imageBase64 画像として不正' => [
                'attrs'     => ['imageBase64' => '不正'],
                'expect'    => false,
                'attribute' => 'imageBase64',
                'messages'  => [
                    '商品画像は画像データとして正しくありません。',
                ],
            ],
            'NG : imageBase64 異なる画像形式' => [
                'attrs' => [
                    'imageBase64' => (static function () {
                        $image = UploadedFile::fake()->image('test.png');

                        return 'data:image/png;base64,' . base64_encode(file_get_contents($image->getPathname()));
                    })(),
                ],
                'expect'    => false,
                'attribute' => 'imageBase64',
                'messages'  => [
                    '商品画像は画像データとして正しくありません。',
                ],
            ],
            'OK : imageBase64 が正常' => [
                'attrs' => [
                    'imageBase64' => (static function () {
                        $image = UploadedFile::fake()->image('test.jpg');

                        return 'data:image/jpeg;base64,' . base64_encode(file_get_contents($image->getPathname()));
                    })(),
                ],
                'expect'    => true,
                'attribute' => 'imageBase64',
                'messages'  => [],
            ],
        ];
    }
}
