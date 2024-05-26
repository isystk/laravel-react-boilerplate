<?php

namespace App\Http\Requests\Admin\Staff;

use App\Domain\Entities\Admin;
use App\Enums\AdminRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
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
        /** @var Admin $staff */
        $staff = $this->route('staff');
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
                'unique:admins,email,' . $staff->id,
            ],
            'role' => [
                'required',
                new Enum(AdminRole::class),
                function($attribute, $value, $fail) use ($staff) {
                    if ($this->role !== $staff->role &&
                        $staff->id === auth()->user()->id) {
                        // 自分の権限は変更させない
                        $fail(__('validation.cannot be changed for yourself.'));
                    }
                }
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
            'role' => __('staff.Role'),
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            '*.Illuminate\Validation\Rules\Enum' => ':attribute の値が不正です。',
        ];
    }

}
