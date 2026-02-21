<?php

namespace App\Http\Controllers\Front\Auth;

use App\Domain\Entities\User;
use App\Enums\OperationLogType;
use App\Services\Common\OperationLogService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function __construct(
        private readonly OperationLogService $operationLogService,
    ) {}

    /**
     * Validate and create a newly registered user.
     *
     * @param array<string, string> $input
     *
     * @throws ValidationException
     */
    public function create(array $input): User
    {
        $maxlength = config('const.maxlength.users');
        Validator::make($input, [
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
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        $user = User::create([
            'name'     => $input['name'],
            'email'    => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        $this->operationLogService->logUserAction(
            $user->id,
            OperationLogType::UserAccountCreate,
            'アカウントを作成',
            request()->ip()
        );

        return $user;
    }
}
