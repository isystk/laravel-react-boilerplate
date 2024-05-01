<?php

namespace App\Services\Admin\User;

use App\Domain\Entities\User;
use App\Domain\Repositories\User\UserRepository;
use App\Services\BaseService;
use Illuminate\Http\Request;

class UpdateService extends BaseService
{

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    public function __construct(
        Request $request,
        UserRepository $userRepository
    )
    {
        parent::__construct($request);
        $this->userRepository = $userRepository;
    }

    /**
     * @param int $userId
     * @return User
     */
    public function update(int $userId): User
    {
        return $this->userRepository->update(
            $userId,
            [
                'name' => $this->request()->input('name'),
                'email' => $this->request()->input('email'),
            ]
        );
    }

}
