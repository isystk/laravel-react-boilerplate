<?php

namespace Tests\Unit\Services\Admin\User;

use App\Dto\Request\Admin\User\SearchConditionDto;
use App\Enums\UserStatus;
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
            'keyword'        => null,
            'status'         => null,
            'has_google'     => null,
            'sort_name'      => 'updated_at',
            'sort_direction' => 'asc',
            'limit'          => 20,
        ]);
        $default = new SearchConditionDto($request);
        $users   = $this->service->searchUser($default)->getCollection();
        $this->assertCount(0, $users, '引数がない状態でエラーにならないことを始めにテスト');

        $user1 = $this->createDefaultUser([
            'name'  => 'user1',
            'email' => 'user1@test.com',
        ]);
        $user2 = $this->createDefaultUser([
            'name'  => 'user2',
            'email' => 'user2@test.com',
        ]);

        $input          = clone $default;
        $input->keyword = 'user2';
        $users          = $this->service->searchUser($input)->getCollection();
        $userIds        = $users->pluck('id')->all();
        $this->assertSame([$user2->id], $userIds, 'keywordで名前検索が出来ることをテスト');

        $input          = clone $default;
        $input->keyword = 'user2@test.com';
        $users          = $this->service->searchUser($input)->getCollection();
        $userIds        = $users->pluck('id')->all();
        $this->assertSame([$user2->id], $userIds, 'keywordでメールアドレス検索が出来ることをテスト');

        $input          = clone $default;
        $input->keyword = (string) $user1->id;
        $users          = $this->service->searchUser($input)->getCollection();
        $userIds        = $users->pluck('id')->all();
        $this->assertContains($user1->id, $userIds, 'keywordでID検索が出来ることをテスト');

        $input                = clone $default;
        $input->sortName      = 'id';
        $input->sortDirection = 'desc';
        $users                = $this->service->searchUser($input)->getCollection();
        $userIds              = $users->pluck('id')->all();
        $this->assertSame([$user2->id, $user1->id], $userIds, 'ソート指定で検索が出来ることをテスト');
    }

    public function test_searchUser_statusで絞り込めること(): void
    {
        $request = new Request([
            'sort_name'      => 'id',
            'sort_direction' => 'asc',
            'limit'          => 20,
        ]);
        $default = new SearchConditionDto($request);

        $activeUser    = $this->createDefaultUser(['status' => UserStatus::Active]);
        $suspendedUser = $this->createDefaultUser(['status' => UserStatus::Suspended]);

        $input         = clone $default;
        $input->status = UserStatus::Active;
        $users         = $this->service->searchUser($input)->getCollection();
        $this->assertSame([$activeUser->id], $users->pluck('id')->all(), '有効ユーザーのみ取得できることをテスト');

        $input         = clone $default;
        $input->status = UserStatus::Suspended;
        $users         = $this->service->searchUser($input)->getCollection();
        $this->assertSame([$suspendedUser->id], $users->pluck('id')->all(), '停止ユーザーのみ取得できることをテスト');
    }

    public function test_searchUser_has_googleで絞り込めること(): void
    {
        $request = new Request([
            'sort_name'      => 'id',
            'sort_direction' => 'asc',
            'limit'          => 20,
        ]);
        $default = new SearchConditionDto($request);

        $googleUser    = $this->createDefaultUser(['google_id' => 'google-abc-123']);
        $nonGoogleUser = $this->createDefaultUser(['google_id' => null]);

        $input            = clone $default;
        $input->hasGoogle = true;
        $users            = $this->service->searchUser($input)->getCollection();
        $this->assertSame([$googleUser->id], $users->pluck('id')->all(), 'Google連携ありのユーザーのみ取得できることをテスト');

        $input            = clone $default;
        $input->hasGoogle = false;
        $users            = $this->service->searchUser($input)->getCollection();
        $this->assertSame([$nonGoogleUser->id], $users->pluck('id')->all(), 'Google連携なしのユーザーのみ取得できることをテスト');
    }
}
