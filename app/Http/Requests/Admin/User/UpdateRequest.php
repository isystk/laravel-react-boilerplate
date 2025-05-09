<?php

namespace App\Http\Requests\Admin\User;

use App\Http\Controllers\Front\Auth\PasswordValidationRules;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    use PasswordValidationRules;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('high-manager');
    }

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
//                Rule::unique(User::class),
            ],
//            'password' => $this->passwordRules(),
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
            'name' => __('user.Name'),
            'email' => __('user.EMail'),
            'password' => __('user.Password'),
        ];
    }

}
