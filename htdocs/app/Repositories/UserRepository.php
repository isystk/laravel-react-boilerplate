<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class UserRepository extends BaseRepository
{
    /**
     * @return string
     */
    function model()
    {
        return User::class;
    }

    /**
     * @param string|null $name
     * @param string|null $email
     * @param array<string, mixed> $options
     * @return Collection|LengthAwarePaginator|array<User>
     */
    public function findAll(?string $name, ?string $email, array $options = []): Collection|LengthAwarePaginator|array
    {
        $query = $this->model->with($this->__with($options));

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
     * @param array<string, mixed>|array<int, string> $options
     * @return array<int, string>
     */
    private function __with(array $options = []): array
    {
        return [];
    }

}
