<?php

namespace App\Http\Requests\Admin\Contact;

use App\Enums\ContactType;
use App\Rules\Base64ImageRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<string|Enum>>
     */
    public function rules(): array
    {
        $maxlength = config('const.maxlength.contacts');

        return [
            'title' => [
                'required',
                'string',
                'max:' . $maxlength['title'],
            ],
            'type' => [
                'required',
                new Enum(ContactType::class),
            ],
            'message' => [
                'required',
                'string',
                'max:' . $maxlength['message'],
            ],
            'image_base_64' => [
                'nullable',
                new Base64ImageRule(['jpeg']),
            ],
            // 画像データをbase64で文字列としても受け入れる。バリデーションルールはimageFileが適用される。
            'image_file_name' => [
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
            'user_name'     => '氏名',
            'title'         => '件名',
            'email'         => 'メールアドレス',
            'gender'        => '性別',
            'age'           => '年齢',
            'contact'       => 'お問い合わせ内容',
            'url'           => 'ホームページURL',
            'image_base_64' => '画像',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string>
     */
    public function messages(): array
    {
        return [
            '*.Illuminate\Validation\Rules\Enum' => ':attributeの値が不正です。',
        ];
    }
}
