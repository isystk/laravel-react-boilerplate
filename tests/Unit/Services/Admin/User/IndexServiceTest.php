<?php

namespace Tests\Unit\Services\Admin\User;

use App\Domain\Entities\User;
use App\Services\Admin\User\IndexService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class IndexServiceTest extends TestCase
{

    use RefreshDatabase;

    private IndexService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(IndexService::class);
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

        $user1 = $this->createDefaultUser([
            'name' => 'user1',
            'email' => 'user1@test.com',
        ]);
        $user2 = $this->createDefaultUser([
            'name' => 'user2',
            'email' => 'user2@test.com',
        ]);

        $input = $default;
        $input['name'] = 'user2';
        /** @var LengthAwarePaginator<int, User> $users */
        $users = $this->service->searchUser($input);
        $userIds = $users->pluck('id')->all();
        $this->assertSame([$user2->id], $userIds, 'nameで検索が出来ることをテスト');

        $input['email'] = 'user2@test.com';
        /** @var LengthAwarePaginator<int, User> $users */
        $users = $this->service->searchUser($input);
        $userIds = $users->pluck('id')->all();
        $this->assertSame([$user2->id], $userIds, 'emailで検索が出来ることをテスト');

        $input = $default;
        $input['sort_name'] = 'id';
        $input['sort_direction'] = 'desc';
        /** @var LengthAwarePaginator<int, User> $users */
        $users = $this->service->searchUser($input);
        $userIds = $users->pluck('id')->all();
        $this->assertSame([$user2->id, $user1->id], $userIds, 'ソート指定で検索が出来ることをテスト');
    }
}
