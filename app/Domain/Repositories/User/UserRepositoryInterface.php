<?php

namespace App\Domain\Repositories\User;

use App\Domain\Entities\User;
use App\Domain\Repositories\BaseRepositoryInterface;
use App\Enums\UserStatus;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * 検索条件からデータを取得します。
     *
     * @param array{
     *   keyword : ?string,
     *   status : ?UserStatus,
     *   has_google : ?bool,
     *   sort_name : ?string,
     *   sort_direction : 'asc' | 'desc' | null,
     *   limit : ?int,
     * } $conditions
     * @return Collection<int, User>|LengthAwarePaginator<int, User>
     */
    public function getByConditions(array $conditions): Collection|LengthAwarePaginator;

    /**
     * Google IDからレコードを取得します。削除済みのレコードも対象とします。
     */
    public function findByGoogleIdWithTrashed(string $googleId): ?User;

    /**
     * 月別の新規ユーザー数を返します。
     *
     * @param  int                                                            $months 取得する月数（直近N ヶ月）
     * @return Collection<int, object{year_month: string, count: int|string}>
     */
    public function countByMonth(int $months = 12): Collection;
}
