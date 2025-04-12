<?php

namespace Tests\Unit\Services\Admin\User;

use App\Domain\Entities\User;
use App\Services\Admin\User\IndexService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use tests\TestCase;

class IndexServiceTest extends TestCase
{

    use RefreshDatabase;

    private IndexService $service;

    /**
     * 各テストの実行前に起動する。
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(IndexService::class);
    }

    /**
     * インスタンスがテスト対象のクラスであることのテスト
     */
    public function testInstanceOf(): void
    {
        $this->assertInstanceOf(IndexService::class, $this->service);
    }

    /**
     * searchUserのテスト
     */
    public function testSearchUser(): void
    {
        $default = [
            'name' => null,
            'email' => null,
            'role' => null,
            'sort_name' => 'updated_at',
            'sort_direction' => 'asc',
            'limit' => 20,
        ];

        $users = $this->service->searchUser($default);
        $this->assertCount(0, $users->items(), '引数がない状態でエラーにならないことを始めにテスト');

        /** @var User $user1 */
        $user1 = User::factory([
            'name' => 'user1',
            'email' => 'user1@test.com',
        ])->create();
        /** @var User $user2 */
        $user2 = User::factory([
            'name' => 'user2',
            'email' => 'user2@test.com',
        ])->create();

        $input = $default;
        $input['name'] = 'user2';
        /** @var User $users */
        $users = $this->service->searchUser($input);
        $userIds = $users->pluck('id')->all();
        $this->assertSame([$user2->id], $userIds, 'nameで検索が出来ることをテスト');

        $input['email'] = 'user2@test.com';
        /** @var User $users */
        $users = $this->service->searchUser($input);
        $userIds = $users->pluck('id')->all();
        $this->assertSame([$user2->id], $userIds, 'emailで検索が出来ることをテスト');

        $input = $default;
        $input['sort_name'] = 'id';
        $input['sort_direction'] = 'desc';
        /** @var User $users */
        $users = $this->service->searchUser($input);
        $userIds = $users->pluck('id')->all();
        $this->assertSame([$user2->id, $user1->id], $userIds, 'ソート指定で検索が出来ることをテスト');
    }
}
