<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\UploadImage;

class StoreContactForm extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    public function validationData()
    {
        $all = parent::validationData();

        // imageBase64パラメータがあればUploadedFileオブジェクトに変換してimageFileパラメータに上書きする。
        if ($this->has('imageBase64') && $this->imageBase64 !== null) {
            $all['imageFile'] = UploadImage::converBase64($this->imageBase64);
        }

        return $all;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'your_name' => 'required|string|max:20',
            'title' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'gender' => 'required',
            'age' => 'required',
            'contact' => 'required|string|max:200',
            'url' => 'url|nullable',
            'caution' => 'required',
            'imageFile' => 'nullable|image|mimes:jpeg,png|max:100000000|dimensions:max_width=1200,max_height=1200', // ファイルのバリデーションよしなに。
            'imageBase64' => 'nullable|string', // 画像データをbase64で文字列としても受け入れる。バリデーションルールはimageFileが適用される。
            'fileName' => 'nullable|string', // 画像ファイル名
        ];
    }
}
