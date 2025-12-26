<?php

namespace Tests\Unit\Services\Admin\ContactForm;

use App\Dto\Request\Admin\ContactForm\SearchConditionDto;
use App\Services\Admin\ContactForm\IndexService;
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

    /**
     * searchContactFormのテスト
     */
    public function test_search_contact_form(): void
    {
        $request = new Request([
            'user_name' => null,
            'title' => null,
            'sort_name' => 'updated_at',
            'sort_direction' => 'asc',
            'limit' => 20,
        ]);
        $default = new SearchConditionDto($request);

        $contactForms = $this->service->searchContactForm($default)->getCollection();
        $this->assertSame(0, $contactForms->count(), '引数がない状態でエラーにならないことを始めにテスト');

        $contactForm1 = $this->createDefaultContactForm(['user_name' => 'user1', 'title' => 'title1']);
        $contactForm2 = $this->createDefaultContactForm(['user_name' => 'user2', 'title' => 'title2']);

        $input = clone $default;
        $input->userName = 'user2';
        $contactForms = $this->service->searchContactForm($input)->getCollection();
        $contactFormIds = $contactForms->pluck('id')->all();
        $this->assertSame([$contactForm2->id], $contactFormIds, 'user_nameで検索が出来ることをテスト');

        $input = clone $default;
        $input->title = 'title2';
        $contactForms = $this->service->searchContactForm($input)->getCollection();
        $contactFormIds = $contactForms->pluck('id')->all();
        $this->assertSame([$contactForm2->id], $contactFormIds, 'titleで検索が出来ることをテスト');

        $input = clone $default;
        $input->sortName = 'id';
        $input->sortDirection = 'desc';
        $contactForms = $this->service->searchContactForm($input)->getCollection();
        $contactFormIds = $contactForms->pluck('id')->all();
        $this->assertSame([$contactForm2->id, $contactForm1->id], $contactFormIds,
            'ソート指定で検索が出来ることをテスト');
    }
}
