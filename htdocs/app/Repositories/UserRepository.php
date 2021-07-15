<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{

  public function count($options = [])
  {
      return User::where([
      ])->count();
  }

  public function findAll($options = [])
  {
      return User::with($this->__with($options))
          ->get();
  }

  public function findById($id, $options = [])
  {
      return User::with($this->__with($options))
          ->where([
              'id' => $id
          ])
          ->first();
  }

  private function __with($options = [])
  {
      $with = [];
      return $with;
  }

  public function store(
      $id,
      $name,
      $email,
      $emailVerifiedAt,
      $password
  ) {
      $user = new User();
      $user->id = $id;
      $user->name = $name;
      $user->email = $email;
      $user->email_verified_at = $emailVerifiedAt;
      $user->password = $password;

      $user->save();

      return $user;
  }

  public function update(
    $id,
    $name,
    $email,
    $emailVerifiedAt,
    $password
  ) {
      $user = $this->findById($id);
      $user->name = $name;
      $user->email = $email;
      $user->email_verified_at = $emailVerifiedAt;
      $user->password = $password;
      $user->save();

      return $user;
  }

  public function delete(
    $id
  ) {
      $user = $this->findById($id);
      $user->delete();

      return $user;
  }

}
