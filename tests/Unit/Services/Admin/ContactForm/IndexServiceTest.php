<?php

namespace Tests\Unit\Services\Admin\ContactForm;

use App\Domain\Entities\ContactForm;
use App\Services\Admin\ContactForm\IndexService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
     * searchContactFormのテスト
     */
    public function testSearchContactForm(): void
    {
        $default = [
            'user_name' => null,
            'title' => null,
            'sort_name' => 'updated_at',
            'sort_direction' => 'asc',
            'limit' => 20,
        ];

        $contactForms = $this->service->searchContactForm($default);
        $this->assertSame(0, $contactForms->count(), '引数がない状態でエラーにならないことを始めにテスト');

        $contactForm1 = $this->createDefaultContactForm(['user_name' => 'user1', 'title' => 'title1']);
        $contactForm2 = $this->createDefaultContactForm(['user_name' => 'user2', 'title' => 'title2']);

        $input = $default;
        $input['user_name'] = 'user2';
        /** @var Collection<int, ContactForm> $contactForms */
        $contactForms = $this->service->searchContactForm($input);
        $contactFormIds = $contactForms->pluck('id')->all();
        $this->assertSame([$contactForm2->id], $contactFormIds, 'user_nameで検索が出来ることをテスト');

        $input = $default;
        $input['title'] = 'title2';
        /** @var Collection<int, ContactForm> $contactForms */
        $contactForms = $this->service->searchContactForm($input);
        $contactFormIds = $contactForms->pluck('id')->all();
        $this->assertSame([$contactForm2->id], $contactFormIds, 'titleで検索が出来ることをテスト');

        $input = $default;
        $input['sort_name'] = 'id';
        $input['sort_direction'] = 'desc';
        /** @var Collection<int, ContactForm> $contactForms */
        $contactForms = $this->service->searchContactForm($input);
        $contactFormIds = $contactForms->pluck('id')->all();
        $this->assertSame([$contactForm2->id, $contactForm1->id], $contactFormIds,
            'ソート指定で検索が出来ることをテスト');
    }
}
