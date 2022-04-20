<?php

namespace App\Services;

use App\Enums\ErrorType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\UserRepository;

class UserService extends Service
{

  /**
   * @var UserRepository
   */
  protected $userRepository;

  public function __construct(
    Request $request,
    UserRepository $userRepository
) {
    parent::__construct($request);
    $this->userRepository = $userRepository;
  }

  public function list($limit = 20)
  {
    return $this->userRepository->findAll(
      $this->request()->name,
      $this->request()->email,
      [
      'limit'=>$limit
    ]);
  }

  public function find($userId)
  {
    return $this->userRepository->findById($userId, []);
  }

  public function save($userId=null)
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
    } catch (\PDOException $e) {
        DB::rollBack();
        return [false, ErrorType::DATABASE, $e];
    } catch (\Exception $e) {
        DB::rollBack();
        return [false, ErrorType::FATAL, $e];
    }

  }

  public function delete($id)
  {
    DB::beginTransaction();
    try {
        // ユーザテーブルを削除
        $user = $this->userRepository->delete($id);

        DB::commit();
        return [$user, ErrorType::SUCCESS, null];
    } catch (\PDOException $e) {
        DB::rollBack();
        return [false, ErrorType::DATABASE, $e];
    } catch (\Exception $e) {
        DB::rollBack();
        return [false, ErrorType::FATAL, $e];
    }
  }
}
