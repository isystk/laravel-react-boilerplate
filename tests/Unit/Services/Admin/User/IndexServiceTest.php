<?php

namespace Tests\Unit\Services\Admin\User;

use App\Dto\Request\Admin\User\SearchConditionDto;
use App\Services\Admin\User\IndexService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\BaseTest;

class IndexServiceTest extends BaseTest
{
    use RefreshDatabase;

    private IndexService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(IndexService::class);
    }

    public function test_searchUser(): void
    {
        $request = new Request([
            'name' => null,
            'email' => null,
            'sort_name' => 'updated_at',
            'sort_direction' => 'asc',
            'limit' => 20,
        ]);
        $default = new SearchConditionDto($request);
        $users = $this->service->searchUser($default)->getCollection();
        $this->assertCount(0, $users, '引数がない状態でエラーにならないことを始めにテスト');

        $user1 = $this->createDefaultUser([
            'name' => 'user1',
            'email' => 'user1@test.com',
        ]);
        $user2 = $this->createDefaultUser([
            'name' => 'user2',
            'email' => 'user2@test.com',
        ]);

        $input = clone $default;
        $input->name = 'user2';
        $users = $this->service->searchUser($input)->getCollection();
        $userIds = $users->pluck('id')->all();
        $this->assertSame([$user2->id], $userIds, 'nameで検索が出来ることをテスト');

        $input = clone $default;
        $input->email = 'user2@test.com';
        $users = $this->service->searchUser($input)->getCollection();
        $userIds = $users->pluck('id')->all();
        $this->assertSame([$user2->id], $userIds, 'emailで検索が出来ることをテスト');

        $input = clone $default;
        $input->sortName = 'id';
        $input->sortDirection = 'desc';
        $users = $this->service->searchUser($input)->getCollection();
        $userIds = $users->pluck('id')->all();
        $this->assertSame([$user2->id, $user1->id], $userIds, 'ソート指定で検索が出来ることをテスト');
    }
}
