<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class PasswordChangeUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<string|Password>>
     */
    public function rules(): array
    {
        $maxlength = config('const.maxlength.admins');

        return [
            'password' => [
                'required',
                'string',
                Password::default(),
                'confirmed',
                'max:' . $maxlength['password'],
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
            'password' => 'パスワード',
        ];
    }
}
