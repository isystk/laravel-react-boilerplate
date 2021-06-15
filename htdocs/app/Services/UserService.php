<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserService
{

  public function __construct()
  {
  }

  public function searchUser($name, $email, $hasPaging)
  {

    // 検索フォーム
    $query = DB::table('users');

    // もしキーワードがあったら
    if ($name !== null) {
      $query->where('name', 'like', '%' . $name . '%');
    }
    if ($email !== null) {
      $query->where('email', '=', $email);
    }

    $query->select('id', 'name', 'email', 'created_at');
    $query->orderBy('id');
    $query->orderBy('created_at', 'desc');
    if ($hasPaging) {
      $users = $query->paginate(20);
    } else {
      $users = $query->get();
    }

    // dd($users);

    return $users;
  }

  // public function createUser($request)
  // {

  //   DB::beginTransaction();
  //   try {    //
  //     DB::commit();
  //   } catch (\Exception $e) {
  //     DB::rollback();
  //   }
  // }

  public function updateUser($request, $id)
  {

    // 入力チェック
    $validatedData = $request->validate([
      'name' => 'required|string|max:20',
      'email' => 'required|email|max:255',
    ]);

    DB::beginTransaction();
    try {    //
      //
      $user = User::find($id);

      $user->name = $request->input('name');
      $user->email = $request->input('email');

      $user->save();

      DB::commit();
    } catch (\Exception $e) {
      DB::rollback();
    }
  }

  public function deleteUser($id)
  {
    DB::beginTransaction();
    try {    //

      // ユーザーテーブルを削除
      $user = User::find($id);
      $user->delete();

      DB::commit();
    } catch (\Exception $e) {
      DB::rollback();
    }
  }
}
