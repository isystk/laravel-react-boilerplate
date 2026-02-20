<?php

namespace App\Http\Requests\Api\Contact;

use App\Enums\ContactType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreRequest extends FormRequest
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
            'caution' => [
                'required',
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
            'title'   => '件名',
            'type'    => 'お問い合わせ種類',
            'message' => 'お問い合わせ内容',
            'caution' => '注意事項',
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
