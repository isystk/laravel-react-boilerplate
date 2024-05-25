<?php

namespace App\Http\Requests\Admin\Staff;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:' . $maxlength['name']
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
                'confirmed'
            ],
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
            'name' => __('staff.Name'),
            'email' => __('staff.EMail'),
            'password' => __('staff.Password'),
        ];
    }

}
