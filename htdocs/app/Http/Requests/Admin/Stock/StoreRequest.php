<?php

namespace App\Http\Requests\Admin\Stock;

use App\Utils\UploadImage;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function validationData(): array
    {
        $all = parent::validationData();

        // imageBase64パラメータがあればUploadedFileオブジェクトに変換してimageFileパラメータに上書きする。
        if ($this->has('imageBase64')) {
            $all['imageFile'] = UploadImage::convertBase64($this->imageBase64);
        }

        return $all;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<string>>
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
            'imageFile' => [
                'required',
                'image',
                'mimes:jpeg,png',
                'max:10000',  // 10MB
                'dimensions:max_width=1200,max_height=1200',
            ],
            'imageBase64' => [
                'nullable',
                'string',
            ],
            // 画像データをbase64で文字列としても受け入れる。バリデーションルールはimageFileが適用される。
            'fileName' => [
                'nullable',
                'string',
            ]
        ];
    }


    /**
     * 項目名
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => __('stock.Name'),
            'price' => __('stock.Price'),
            'detail' => __('stock.Detail'),
            'quantity' => __('stock.Quantity'),
            'imageFile' => __('stock.Image'),
        ];
    }

}
