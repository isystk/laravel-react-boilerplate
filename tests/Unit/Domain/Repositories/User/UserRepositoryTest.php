<?php

namespace Domain\Repositories\User;

use App\Domain\Entities\User;
use App\Domain\Repositories\User\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class UserRepositoryTest extends BaseTest
{
    use RefreshDatabase;

    private UserRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = app(UserRepository::class);
    }

    public function test_getByConditions(): void
    {
        $defaultConditions = [
            'name' => null,
            'email' => null,
            'sort_name' => null,
            'sort_direction' => null,
            'limit' => null,
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
}
