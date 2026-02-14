<?php

namespace App\Http\Requests\Admin\Stock;

use App\Rules\Base64ImageRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<string|Base64ImageRule>>
     */
    public function rules(): array
    {
        $maxlength = config('const.maxlength.stocks');

        return [
            'name' => [
                'required',
                'string',
                'max:' . $maxlength['name'],
            ],
            'price' => [
                'required',
                'numeric',
            ],
            'detail' => [
                'required',
                'string',
                'max:' . $maxlength['detail'],
            ],
            'quantity' => [
                'required',
                'numeric',
            ],
            'image_base_64' => [
                'nullable',
                new Base64ImageRule(['jpeg']),
            ],
            // 画像データをbase64で文字列としても受け入れる。バリデーションルールはimageFileが適用される。
            'image_file_name' => [
                'required',
                'string',
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string>
     */
    public function attributes(): array
    {
        return [
            'name'            => '商品名',
            'price'           => '価格',
            'detail'          => '商品説明',
            'quantity'        => '在庫数',
            'image_base_64'   => '商品画像',
            'image_file_name' => '商品画像',
        ];
    }
}
