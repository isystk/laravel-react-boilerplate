<?php

namespace App\Http\Requests\Admin\ContactForm;

use App\Utils\UploadImage;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
        if ($this->has('imageBase64') && $this->imageBase64 !== null) {
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
        $maxlength = config('const.maxlength.contact_forms');
        return [
            'your_name' => [
                'required',
                'string',
                'max:' . $maxlength['your_name']
            ],
            'title' => [
                'required',
                'string',
                'max:' . $maxlength['title']
            ],
            'email' => [
                'required',
                'email',
                'max:' . $maxlength['email']
            ],
            'gender' => [
                'required'
            ],
            'age' => [
                'required'
            ],
            'contact' => [
                'required',
                'string',
                'max:' . $maxlength['contact'],
            ],
            'url' => [
                'url',
                'nullable'
            ],
            'imageFile' => [
                'nullable',
                'image',
                'mimes:jpeg,png',
                'max:10000',  // 10MB
                'dimensions:max_width=1200,max_height=1200'
            ],
            // 画像データをbase64で文字列としても受け入れる。バリデーションルールはimageFileが適用される。
            'imageBase64' => [
                'nullable',
                'string',
            ],
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
            'your_name' => __('contact.Name'),
            'title' => __('contact.Title'),
            'email' => __('contact.EMail'),
            'gender' => __('contact.Gender'),
            'age' => __('contact.Age'),
            'contact' => __('contact.Contact'),
            'url' => __('contact.URL'),
            'imageFile' => __('contact.Image'),
        ];
    }

}
