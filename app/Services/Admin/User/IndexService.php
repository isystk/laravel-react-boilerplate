<?php

namespace App\Services\Admin\User;

use App\Domain\Entities\User;
use App\Domain\Repositories\User\UserRepository;
use App\Services\BaseService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class IndexService extends BaseService
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
     * @return LengthAwarePaginator<User>
     */
    public function searchUser(): LengthAwarePaginator
    {
        $limit = 20;
        return $this->userRepository->getByConditions([
            'name' => $this->request()->name,
            'email' => $this->request()->email,
            'sort_name' => $this->request()->sort_name ?? 'updated_at',
            'sort_direction' => $this->request()->sort_direction ?? 'desc',
            'limit' => $limit,
        ]);
    }

}
