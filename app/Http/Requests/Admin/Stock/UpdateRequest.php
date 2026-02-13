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
            //            'imageFile' => [
            //                'nullable',
            //                'image',
            //                'mimes:jpeg,png',
            //                'max:10000',  // 10MB
            //                'dimensions:max_width=1200,max_height=1200',
            //            ],
            'imageBase64' => [
                'nullable',
                new Base64ImageRule(['jpeg']),
            ],
            // 画像データをbase64で文字列としても受け入れる。バリデーションルールはimageFileが適用される。
            'fileName' => [
                'nullable',
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
            'name'        => __('stock.Name'),
            'price'       => __('stock.Price'),
            'detail'      => __('stock.Detail'),
            'quantity'    => __('stock.Quantity'),
            'imageBase64' => __('stock.Image'),
        ];
    }
}
