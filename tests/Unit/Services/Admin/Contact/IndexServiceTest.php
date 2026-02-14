<?php

namespace Tests\Unit\Services\Admin\Contact;

use App\Dto\Request\Admin\Contact\SearchConditionDto;
use App\Services\Admin\Contact\IndexService;
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

    public function test_searchContact(): void
    {
        $request = new Request([
            'user_name'      => null,
            'title'          => null,
            'sort_name'      => 'updated_at',
            'sort_direction' => 'asc',
            'limit'          => 20,
        ]);
        $default = new SearchConditionDto($request);

        $contacts = $this->service->searchContact($default)->getCollection();
        $this->assertSame(0, $contacts->count(), '引数がない状態でエラーにならないことを始めにテスト');

        $contact1 = $this->createDefaultContact(['user_name' => 'user1', 'title' => 'title1']);
        $contact2 = $this->createDefaultContact(['user_name' => 'user2', 'title' => 'title2']);

        $input           = clone $default;
        $input->userName = 'user2';
        $contacts        = $this->service->searchContact($input)->getCollection();
        $contactIds      = $contacts->pluck('id')->all();
        $this->assertSame([$contact2->id], $contactIds, 'user_nameで検索が出来ることをテスト');

        $input        = clone $default;
        $input->title = 'title2';
        $contacts     = $this->service->searchContact($input)->getCollection();
        $contactIds   = $contacts->pluck('id')->all();
        $this->assertSame([$contact2->id], $contactIds, 'titleで検索が出来ることをテスト');

        $input                = clone $default;
        $input->sortName      = 'id';
        $input->sortDirection = 'desc';
        $contacts             = $this->service->searchContact($input)->getCollection();
        $contactIds           = $contacts->pluck('id')->all();
        $this->assertSame([$contact2->id, $contact1->id], $contactIds,
            'ソート指定で検索が出来ることをテスト');
    }
}
