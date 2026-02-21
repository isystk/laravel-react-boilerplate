<?php

namespace Tests\Unit\Domain\Repositories\User;

use App\Domain\Entities\User;
use App\Domain\Repositories\User\UserRepositoryInterface;
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
            'name'           => null,
            'email'          => null,
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
            'name' => 'user1',
        ])->first();
        $this->assertSame($expectUser1->id, $user->id, 'nameで検索が出来ることをテスト');

        /** @var User $user */
        $user = $this->repository->getByConditions([
            ...$defaultConditions,
            'email' => 'user2@test.com',
        ])->first();
        $this->assertSame($expectUser2->id, $user->id, 'emailで検索が出来ることをテスト');

        $users = $this->repository->getByConditions([
            ...$defaultConditions,
            'limit' => 1,
        ]);
        $this->assertSame(1, $users->count(), 'limitで取得件数が指定出来ることをテスト');
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
