<?php

namespace App\Services;

use App\Domain\Entities\User;
use App\Domain\Repositories\User\UserRepository;
use App\Enums\ErrorType;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDOException;

class UserService extends BaseService
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
     * @param int $limit
     * @return Collection|LengthAwarePaginator|array<string>
     */
    public function list(int $limit = 20): Collection|LengthAwarePaginator|array
    {
        return $this->userRepository->findAll(
            $this->request()->name,
            $this->request()->email,
            [
                'limit' => $limit,
            ]);
    }

    /**
     * @param int $userId
     * @return object|null
     */
    public function find(int $userId): object|null
    {
        return $this->userRepository->getById($userId);
    }

    /**
     * @param int|null $userId
     * @return User
     */
    public function save(int $userId = null): User
    {
        if ($userId) {
            // 変更

            $user = $this->userRepository->update(
                $userId,
                [
                    'name' => $this->request()->input('name'),
                    'email' => $this->request()->input('email'),
                ]
            );
        } else {
            // 新規登録

            $user = $this->userRepository->create(
                [
                    'name' => $this->request()->input('name'),
                    'email' => $this->request()->input('email'),
                ],
            );

            $id = $user->id;
        }

        return $user;
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
