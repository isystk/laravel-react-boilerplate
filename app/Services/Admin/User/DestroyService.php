<?php

namespace App\Services\Admin\User;

use App\Domain\Repositories\User\UserRepository;
use App\Services\BaseService;
use Illuminate\Http\Request;

class DestroyService extends BaseService
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
     * @param int $id
     */
    public function delete(int $id): void
    {
        // ユーザテーブルを削除
        $this->userRepository->delete($id);
    }
}
