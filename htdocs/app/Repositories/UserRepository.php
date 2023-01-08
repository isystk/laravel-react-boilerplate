<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class UserRepository
{

    /**
     * @param array<string, mixed> $options
     * @return mixed
     */
    public function count(array $options = []): mixed
    {
        return User::where([
        ])->count();
    }

    /**
     * @param string|null $name
     * @param string|null $email
     * @param array<string, mixed> $options
     * @return Collection|LengthAwarePaginator|array<User>
     */
    public function findAll(?string $name, ?string $email, array $options = []): Collection|LengthAwarePaginator|array
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

    /**
     * @param string $id
     * @param array<string> $options
     * @return User|null
     */
    public function findById(string $id, array $options = []): User|null
    {
        return User::with($this->__with($options))
            ->where([
                'id' => $id
            ])
            ->first();
    }


    /**
     * @param array<string, mixed>|array<int, string> $options
     * @return array<int, string>
     */
    private function __with(array $options = []): array
    {
        return [];
    }

    /**
     * @param int|null $id
     * @param string $name
     * @param string $email
     * @return User
     */
    public function store(
        ?int $id,
        string $name,
        string $email
    ): User
    {
        $user = new User();
        $user['id'] = $id;
        $user['name'] = $name;
        $user['email'] = $email;

        $user->save();

        return $user;
    }

    /**
     * @param string $id
     * @param string $name
     * @param string $email
     * @return User|null
     */
    public function update(
        string $id,
        string $name,
        string $email
    ): ?object
    {
        $user = $this->findById($id);
        $user['name'] = $name;
        $user['email'] = $email;
        $user->save();

        return $user;
    }

    /**
     * @param string $id
     * @return User|null
     */
    public function delete(
        string $id
    ): ?object
    {
        $user = $this->findById($id);
        $user->delete();

        return $user;
    }

}
