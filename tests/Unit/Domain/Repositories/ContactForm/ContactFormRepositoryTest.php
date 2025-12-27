<?php

namespace Domain\Repositories\ContactForm;

use App\Domain\Entities\ContactForm;
use App\Domain\Repositories\ContactForm\ContactFormRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class ContactFormRepositoryTest extends BaseTest
{
    use RefreshDatabase;

    private ContactFormRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = app(ContactFormRepository::class);
    }

    public function test_get_by_conditions(): void
    {
        $defaultConditions = [
            'user_name' => null,
            'title' => null,
            'sort_name' => null,
            'sort_direction' => null,
            'limit' => null,
        ];

        $stocks = $this->repository->getByConditions($defaultConditions);
        $this->assertSame(0, $stocks->count(), 'データがない状態で正常に動作することを始めにテスト');

        $expectContactForm1 = $this->createDefaultContactForm(['user_name' => 'user1', 'title' => 'title1']);
        $expectContactForm2 = $this->createDefaultContactForm(['user_name' => 'user2', 'title' => 'title2']);

        /** @var ContactForm $contactForm */
        $contactForm = $this->repository->getByConditions([
            ...$defaultConditions,
            'user_name' => 'user1',
        ])->first();
        $this->assertSame($expectContactForm1->id, $contactForm->id, 'user_nameで検索が出来ることをテスト');

        /** @var ContactForm $contactForm */
        $contactForm = $this->repository->getByConditions([
            ...$defaultConditions,
            'title' => 'title2',
        ])->first();
        $this->assertSame($expectContactForm2->id, $contactForm->id, 'titleで検索が出来ることをテスト');

        $contactForms = $this->repository->getByConditions([
            ...$defaultConditions,
            'limit' => 1,
        ]);
        $this->assertSame(1, $contactForms->count(), 'limitで取得件数が指定出来ることをテスト');
    }
}
