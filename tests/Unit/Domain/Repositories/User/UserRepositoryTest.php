<?php

namespace Tests\Unit\Domain\Repositories\User;

use App\Domain\Entities\User;
use App\Domain\Repositories\User\UserRepositoryInterface;
use App\Enums\UserStatus;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\BaseTest;

class UserRepositoryTest extends BaseTest
{
    use RefreshDatabase;

    private UserRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = app(UserRepositoryInterface::class);
    }

    public function test_getByConditions(): void
    {
        $defaultConditions = [
            'keyword'        => null,
            'status'         => null,
            'has_google'     => null,
            'with_trashed'   => false,
            'sort_name'      => null,
            'sort_direction' => null,
            'limit'          => null,
        ];

        $users = $this->repository->getByConditions($defaultConditions);
        $this->assertSame(0, $users->count(), 'データがない状態で正常に動作することを始めにテスト');

        $expectUser1 = $this->createDefaultUser(['name' => 'user1', 'email' => 'user1@test.com']);
        $expectUser2 = $this->createDefaultUser(['name' => 'user2', 'email' => 'user2@test.com']);

        /** @var User $user */
        $user = $this->repository->getByConditions([
            ...$defaultConditions,
            'keyword' => 'user1',
        ])->first();
        $this->assertSame($expectUser1->id, $user->id, 'keywordで名前検索が出来ることをテスト');

        /** @var User $user */
        $user = $this->repository->getByConditions([
            ...$defaultConditions,
            'keyword' => 'user2@test.com',
        ])->first();
        $this->assertSame($expectUser2->id, $user->id, 'keywordでメールアドレス検索が出来ることをテスト');

        /** @var User $user */
        $user = $this->repository->getByConditions([
            ...$defaultConditions,
            'keyword' => (string) $expectUser1->id,
        ])->first();
        $this->assertSame($expectUser1->id, $user->id, 'keywordでID検索が出来ることをテスト');

        $users = $this->repository->getByConditions([
            ...$defaultConditions,
            'limit' => 1,
        ]);
        $this->assertSame(1, $users->count(), 'limitで取得件数が指定出来ることをテスト');
    }

    public function test_getByConditions_with_trashedで退会済みユーザーを含めて取得できること(): void
    {
        $defaultConditions = [
            'keyword'        => null,
            'status'         => null,
            'has_google'     => null,
            'with_trashed'   => false,
            'sort_name'      => 'id',
            'sort_direction' => 'asc',
            'limit'          => null,
        ];

        $activeUser  = $this->createDefaultUser();
        $deletedUser = $this->createDefaultUser();
        $deletedUser->delete();

        $users = $this->repository->getByConditions($defaultConditions);
        $this->assertSame([$activeUser->id], $users->pluck('id')->all(), 'デフォルトでは退会済みユーザーが含まれないことをテスト');

        $users = $this->repository->getByConditions([
            ...$defaultConditions,
            'with_trashed' => true,
        ]);
        $this->assertSame([$activeUser->id, $deletedUser->id], $users->pluck('id')->all(), 'with_trashedがtrueの場合は退会済みユーザーが含まれることをテスト');
    }

    public function test_getByConditions_statusで絞り込めること(): void
    {
        $defaultConditions = [
            'keyword'        => null,
            'status'         => null,
            'has_google'     => null,
            'with_trashed'   => false,
            'sort_name'      => null,
            'sort_direction' => null,
            'limit'          => null,
        ];

        $activeUser    = $this->createDefaultUser(['status' => UserStatus::Active]);
        $suspendedUser = $this->createDefaultUser(['status' => UserStatus::Suspended]);

        $users = $this->repository->getByConditions([
            ...$defaultConditions,
            'status' => UserStatus::Active,
        ]);
        $this->assertSame(1, $users->count());
        $this->assertSame($activeUser->id, $users->first()->id, '有効ユーザーのみ取得できることをテスト');

        $users = $this->repository->getByConditions([
            ...$defaultConditions,
            'status' => UserStatus::Suspended,
        ]);
        $this->assertSame(1, $users->count());
        $this->assertSame($suspendedUser->id, $users->first()->id, '停止ユーザーのみ取得できることをテスト');
    }

    public function test_getByConditions_has_googleで絞り込めること(): void
    {
        $defaultConditions = [
            'keyword'        => null,
            'status'         => null,
            'has_google'     => null,
            'with_trashed'   => false,
            'sort_name'      => null,
            'sort_direction' => null,
            'limit'          => null,
        ];

        $googleUser    = $this->createDefaultUser(['google_id' => 'google-abc-123']);
        $nonGoogleUser = $this->createDefaultUser(['google_id' => null]);

        $users = $this->repository->getByConditions([
            ...$defaultConditions,
            'has_google' => true,
        ]);
        $this->assertSame(1, $users->count());
        $this->assertSame($googleUser->id, $users->first()->id, 'Google連携ありのユーザーのみ取得できることをテスト');

        $users = $this->repository->getByConditions([
            ...$defaultConditions,
            'has_google' => false,
        ]);
        $this->assertSame(1, $users->count());
        $this->assertSame($nonGoogleUser->id, $users->first()->id, 'Google連携なしのユーザーのみ取得できることをテスト');
    }

    public function test_findByEmailWithTrashed_存在するメールアドレスの場合ユーザーが返却されること(): void
    {
        $user = $this->createDefaultUser([
            'email' => 'user@example.com',
        ]);

        $result = $this->repository->findByEmailWithTrashed('user@example.com');

        $this->assertNotNull($result);
        $this->assertSame($user->id, $result->id);
        $this->assertSame('user@example.com', $result->email);
    }

    public function test_findByEmailWithTrashed_存在しないメールアドレスの場合はnullが返却されること(): void
    {
        $this->createDefaultUser([
            'email' => 'user@example.com',
        ]);

        $result = $this->repository->findByEmailWithTrashed('not-exist@example.com');

        $this->assertNull($result);
    }

    public function test_findByGoogleIdWithTrashed_存在するGoogleIDの場合ユーザーが返却されること(): void
    {
        $user = $this->createDefaultUser([
            'google_id'  => 'google-abc-123',
            'avatar_url' => 'https://example.com/avatar.png',
        ]);

        $result = $this->repository->findByGoogleIdWithTrashed('google-abc-123');

        $this->assertNotNull($result);
        $this->assertSame($user->id, $result->id);
        $this->assertSame('google-abc-123', $result->google_id);
    }

    public function test_findByGoogleIdWithTrashed_存在しないGoogleIDの場合nullが返却されること(): void
    {
        $this->createDefaultUser([
            'google_id' => 'google-abc-123',
        ]);

        $result = $this->repository->findByGoogleIdWithTrashed('google-not-exist');

        $this->assertNull($result);
    }

    public function test_countByMonth_指定した期間の月別集計が取得できること(): void
    {
        Carbon::setTestNow('2026-02-21 12:00:00');

        $this->createDefaultUser(['created_at' => '2025-01-31 23:59:59']); // 範囲外
        $this->createDefaultUser(['created_at' => '2025-03-01 00:00:00']); // 12ヶ月前の開始月
        $this->createDefaultUser(['created_at' => '2025-03-02 00:00:00']); // 12ヶ月前の開始月
        $this->createDefaultUser(['created_at' => '2026-02-15 10:00:00']); // 今月
        $this->createDefaultUser(['created_at' => '2026-02-16 10:00:00']); // 今月
        $this->createDefaultUser(['created_at' => '2026-02-17 10:00:00']); // 今月

        $result = $this->repository->countByMonth(12);

        $this->assertInstanceOf(Collection::class, $result);

        $firstRow = $result->first();
        $this->assertSame('2025/03', $firstRow->year_month);
        $this->assertSame(2, $firstRow->count);

        $lastRow = $result->last();
        $this->assertSame('2026/02', $lastRow->year_month);
        $this->assertSame(3, $lastRow->count);
    }

    public function test_countByMonth_データが存在しない場合は空のコレクションを返すこと(): void
    {
        $result = $this->repository->countByMonth(12);

        $this->assertTrue($result->isEmpty());
    }
}
