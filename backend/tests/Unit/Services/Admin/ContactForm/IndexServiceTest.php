<?php

namespace Tests\Unit\Services\Admin\ContactForm;

use App\Domain\Entities\ContactForm;
use App\Services\Admin\ContactForm\IndexService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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

        /** @var ContactForm $contactForm1 */
        $contactForm1 = ContactForm::factory(['user_name' => 'user1', 'title' => 'title1'])->create();
        /** @var ContactForm $contactForm2 */
        $contactForm2 = ContactForm::factory(['user_name' => 'user2', 'title' => 'title2'])->create();

        $input = $default;
        $input['user_name'] = 'user2';
        /** @var ContactForm $contactForms */
        $contactForms = $this->service->searchContactForm($input);
        $contactFormIds = $contactForms->pluck('id')->all();
        $this->assertSame([$contactForm2->id], $contactFormIds, 'user_nameで検索が出来ることをテスト');

        $input = $default;
        $input['title'] = 'title2';
        /** @var ContactForm $contactForms */
        $contactForms = $this->service->searchContactForm($input);
        $contactFormIds = $contactForms->pluck('id')->all();
        $this->assertSame([$contactForm2->id], $contactFormIds, 'titleで検索が出来ることをテスト');

        $input = $default;
        $input['sort_name'] = 'id';
        $input['sort_direction'] = 'desc';
        /** @var ContactForm $contactForms */
        $contactForms = $this->service->searchContactForm($input);
        $contactFormIds = $contactForms->pluck('id')->all();
        $this->assertSame([$contactForm2->id, $contactForm1->id], $contactFormIds, 'ソート指定で検索が出来ることをテスト');

    }
}
