<?php

namespace App\Repositories;

use App\Models\Admin;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class AdminRepository
{

    /**
     * @param array<string, mixed>|array<int, string> $options
     * @return int
     */
    public function count($options = []): int
    {
        return Admin::where([
        ])->count();
    }

    /**
     * @param array<string, mixed>|array<int, string> $options
     * @return Collection|LengthAwarePaginator
     */
    public function findAll($options = []): Collection|LengthAwarePaginator
    {
        $query = Admin::with($this->__with($options));

        $limit = !empty($options['limit']) ? (int)$options['limit'] : null;
        return $limit > 0 ? $query->paginate($limit) : $query->get();
    }

    /**
     * @param string $id
     * @param array<string, mixed>|array<int, string> $options
     * @return Admin|null
     */
    public function findById(string $id, array $options = []): Admin|null
    {
        return Admin::with($this->__with($options))
            ->where([
                'id' => $id
            ])
            ->first();
    }

    /**
     * @param array<string, mixed>|array<int, string> $options
     * @return array<int, string>
     */
    private function __with($options = [])
    {
        $with = [];
        return $with;
    }

    /**
     * @param ?string $id
     * @param string $name
     * @param string $email
     * @param string $password
     * @return Admin
     */
    public function store(
        ?string $id,
        string  $name,
        string  $email,
        string $password
    ): Admin
    {
        $admin = new Admin();
        $admin['id'] = $id;
        $admin['name'] = $name;
        $admin['email'] = $email;
        $admin['password'] = $password;

        $admin->save();

        return $admin;
    }

    /**
     * @param string $id
     * @param string $name
     * @param string $email
     * @param string $password
     * @return Admin
     */
    public function update(
        string $id,
        string $name,
        string $email,
        string $password
    ): Admin
    {
        $admin = $this->findById($id);
        $admin['name'] = $name;
        $admin['email'] = $email;
        $admin['password'] = $password;
        $admin->save();

        return $admin;
    }

    /**
     * @param string $id
     * @return Admin|null
     */
    public function delete(
        string $id
    ): ?Admin
    {
        $admin = $this->findById($id);
        $admin->delete();

        return $admin;
    }

}
