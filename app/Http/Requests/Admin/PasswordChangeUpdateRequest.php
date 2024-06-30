<?php

namespace App\Http\Requests\Admin;

use App\Http\Controllers\Front\Auth\PasswordValidationRules;
use Illuminate\Foundation\Http\FormRequest;

class PasswordChangeUpdateRequest extends FormRequest
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
        $maxlength = config('const.maxlength.admins');
        return [
            'password' => [
                ...$this->passwordRules(),
                'max:' . $maxlength['password'],
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
            'password' => __('staff.Password'),
        ];
    }

}
