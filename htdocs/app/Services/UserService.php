<?php

namespace App\Services;

use App\Enums\ErrorType;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\UserRepository;
use PDOException;

class UserService extends Service
{

    /**
     * @var UserRepository
     */
    protected $userRepository;

    public function __construct(
        Request        $request,
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
                'limit' => $limit
            ]);
    }

    /**
     * @param string $userId
     * @return object|null
     */
    public function find(string $userId): object|null
    {
        return $this->userRepository->findById($userId, []);
    }

    /**
     * @param string|null $userId
     * @return array<int, mixed>
     */
    public function save(string $userId = null): array
    {

        DB::beginTransaction();
        try {

            if ($userId) {
                // 変更

                $user = $this->userRepository->update(
                    $userId,
                    $this->request()->input('name'),
                    $this->request()->input('email')
                );

            } else {
                // 新規登録

                $user = $this->userRepository->store(
                    null,
                    $this->request()->input('name'),
                    $this->request()->input('email')
                );

                $id = $user->id;

            }

            DB::commit();

            return [$user, ErrorType::SUCCESS, null];
        } catch (PDOException $e) {
            DB::rollBack();
            return [false, ErrorType::DATABASE, $e];
        } catch (Exception $e) {
            DB::rollBack();
            return [false, ErrorType::FATAL, $e];
        }

    }

    /**
     * @param string $id
     * @return array<int, mixed>
     */
    public function delete(string $id): array
    {
        DB::beginTransaction();
        try {
            // ユーザテーブルを削除
            $user = $this->userRepository->delete($id);

            DB::commit();
            return [$user, ErrorType::SUCCESS, null];
        } catch (PDOException $e) {
            DB::rollBack();
            return [false, ErrorType::DATABASE, $e];
        } catch (Exception $e) {
            DB::rollBack();
            return [false, ErrorType::FATAL, $e];
        }
    }
}
