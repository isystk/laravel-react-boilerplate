<?php

namespace App\Http\Requests\Admin\Staff;

use App\Enums\AdminRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;

class StoreRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function validationData(): array
    {
        return parent::validationData();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<string|Password|Enum>>
     */
    public function rules(): array
    {
        $maxlength = config('const.maxlength.admins');

        return [
            'name' => [
                'required',
                'string',
                'max:' . $maxlength['name'],
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:' . $maxlength['email'],
                'unique:admins,email',
            ],
            'password' => [
                'required',
                'string',
                'max:' . $maxlength['password'],
                Password::default(),
                'confirmed',
            ],
            'role' => [
                'required',
                new Enum(AdminRole::class),
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
            'name'     => '氏名',
            'email'    => 'メールアドレス',
            'password' => 'パスワード',
            'role'     => '権限',
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
