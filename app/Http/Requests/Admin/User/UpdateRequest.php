<?php

namespace App\Http\Requests\Admin\User;

use App\Domain\Entities\User;
use App\Http\Controllers\Front\Fortify\PasswordValidationRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    use PasswordValidationRules;

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
     * @return array<string, array<string>>
     */
    public function rules(): array
    {
        $maxlength = config('const.maxlength.users');

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
                Rule::unique(User::class)->ignore($this->route('user')),
            ],
            'avatar' => [
                'nullable',
                'file',
                'image',
                'max:5120', // 5MB
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
        ];
    }
}
