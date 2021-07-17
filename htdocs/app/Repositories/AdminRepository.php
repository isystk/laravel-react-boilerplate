<?php

namespace App\Repositories;

use App\Models\Admin;

class AdminRepository
{

  public function count($options = [])
  {
      return Admin::where([
      ])->count();
  }

  public function findAll($options = [])
  {
      $query = Admin::with($this->__with($options));

      $limit = !empty($options['limit']) ? (int)$options['limit'] : null;
      return $limit > 0 ? $query->paginate($limit) : $query->get();
  }

  public function findById($id, $options = [])
  {
      return Admin::with($this->__with($options))
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
      $password
  ) {
      $admin = new Admin();
      $admin->id = $id;
      $admin->name = $name;
      $admin->email = $email;
      $admin->password = $password;

      $admin->save();

      return $admin;
  }

  public function update(
    $id,
    $name,
    $email,
    $password
  ) {
      $admin = $this->findById($id);
      $admin->name = $name;
      $admin->email = $email;
      $admin->password = $password;
      $admin->save();

      return $admin;
  }

  public function delete(
    $id
  ) {
      $admin = $this->findById($id);
      $admin->delete();

      return $admin;
  }

}
