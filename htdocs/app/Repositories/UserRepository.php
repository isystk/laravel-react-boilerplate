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

  public function findAll($name, $email, $options = [])
  {
      $query = User::with($this->__with($options));

      if (!empty($name)) {
        $query->where('name', 'like', '%' . $name . '%');
      }
      if (!empty($email)) {
        $query
        ->where([
            'email' => $email
        ]);
      }

      $limit = !empty($options['limit']) ? (int)$options['limit'] : null;
      return $limit > 0 ? $query->paginate($limit) : $query->get();
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
      $email
  ) {
      $user = new User();
      $user->id = $id;
      $user->name = $name;
      $user->email = $email;

      $user->save();

      return $user;
  }

  public function update(
    $id,
    $name,
    $email
  ) {
      $user = $this->findById($id);
      $user->name = $name;
      $user->email = $email;
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
