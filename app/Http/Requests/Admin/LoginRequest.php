<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<string>>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'string',
                'email',
            ],
            'password' => [
                'required',
                'string',
            ],
            // reCaptchaによる認証チェックはコメントアウトしておく
//            'g-recaptcha-response' => [
//                'required',
//                'recaptchav3:login,0.5'
//            ],
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
            'email' => __('user.EMail'),
            'password' => __('user.Password'),
        ];
    }

}
