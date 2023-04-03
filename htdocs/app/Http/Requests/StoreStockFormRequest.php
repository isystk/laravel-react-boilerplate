<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Utils\UploadImage;

class StoreStockFormRequest extends FormRequest
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
     * @return array<string, string>
     */
    public function rules()
    {
        return [
            //
            'name' => 'required|string|max:20',
            'price' => 'required|numeric',
            'detail' => 'required|string|max:200',
            'quantity' => 'required|numeric',
            'imageFile' => 'nullable|image|mimes:jpeg,png|max:100000000|dimensions:max_width=1200,max_height=1200', // ファイルのバリデーションよしなに。
            'imageBase64' => 'nullable|string', // 画像データをbase64で文字列としても受け入れる。バリデーションルールはimageFileが適用される。
            'fileName' => 'nullable|string', // 画像ファイル名
        ];
    }


    /**
     * 項目名
     *
     * @return array<string, string>
     */
    public function attributes()
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
