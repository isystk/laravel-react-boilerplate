<?php

namespace App\Http\Requests\Admin\Contact;

use Illuminate\Foundation\Http\FormRequest;

class ReplyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<string>>
     */
    public function rules(): array
    {
        $maxlength = config('const.maxlength.contact_replies');

        return [
            'body' => [
                'required',
                'string',
                'max:' . $maxlength['body'],
            ],
        ];
    }

    /**
     * Get the custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'body' => '返信内容',
        ];
    }
}
