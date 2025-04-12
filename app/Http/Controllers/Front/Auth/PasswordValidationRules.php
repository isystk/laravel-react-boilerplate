<?php

namespace App\Http\Controllers\Front\Auth;

use Illuminate\Validation\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array<int, array<string|Password>|string>
     */
    protected function passwordRules(): array
    {
        return [
            'required',
            'string',
            Password::default(),
            'confirmed'
        ];
    }
}
