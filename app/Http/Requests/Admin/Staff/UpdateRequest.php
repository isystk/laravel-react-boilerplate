<?php

namespace App\Http\Requests\Admin\Staff;

use App\Domain\Entities\Admin;
use App\Enums\AdminRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\Rules\Enum;

class UpdateRequest extends FormRequest
{
    use RefreshDatabase;

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
     * @return array<string, array<string|Enum|\Closure>>
     */
    public function rules(): array
    {
        /** @var Admin $staff */
        $staff = $this->staff;
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
                'unique:admins,email,' . $staff->id,
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
            'name' => __('staff.Name'),
            'email' => __('staff.EMail'),
            'password' => __('staff.Password'),
            'role' => __('staff.Role'),
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
