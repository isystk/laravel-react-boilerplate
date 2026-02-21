<?php

namespace App\Domain\Repositories\User;

use App\Domain\Entities\User;
use App\Domain\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected function model(): string
    {
        return User::class;
    }

    /**
     * {@inheritDoc}
     */
    public function getByConditions(array $conditions): Collection|LengthAwarePaginator
    {
        $query = $this->model->with('avatarImage')->select();

        if (!is_null($conditions['name'] ?? null)) {
            $query->where('name', 'like', '%' . $conditions['name'] . '%');
        }
        if (!is_null($conditions['email'] ?? null)) {
            $query->where('email', 'like', '%' . $conditions['email'] . '%');
        }

        $sortColumn = $this->validateSortColumn(
            $conditions['sort_name'] ?? '',
            ['id', 'name', 'email', 'created_at', 'updated_at'],
        );
        if ($sortColumn !== null) {
            $query->orderBy($sortColumn, $conditions['sort_direction'] ?? 'asc');
        }

        if (!is_null($conditions['limit'] ?? null)) {
            /** @var LengthAwarePaginator<int, User> */
            return $query->paginate($conditions['limit']);
        }

        /** @var Collection<int, User> */
        return $query->get();
    }

    /**
     * {@inheritDoc}
     */
    public function findByGoogleIdWithTrashed(string $googleId): ?User
    {
        /** @var ?User */
        return User::withTrashed()->where('google_id', $googleId)->first();
    }

    /**
     * {@inheritDoc}
     */
    public function countByMonth(int $months = 12): Collection
    {
        $from = Carbon::now()->startOfMonth()->subMonths($months - 1);

        /** @var Collection<int, object{year_month: string, count: int|string}> */
        return $this->model
            ->selectRaw("DATE_FORMAT(created_at, '%Y/%m') as `year_month`, COUNT(*) as `count`")
            ->where('created_at', '>=', $from)
            ->groupByRaw("DATE_FORMAT(created_at, '%Y/%m')")
            ->orderByRaw("DATE_FORMAT(created_at, '%Y/%m') ASC")
            ->get();
    }
}
