<?php

namespace Domain\Repositories\ContactForm;

use App\Domain\Entities\ContactForm;
use App\Domain\Repositories\ContactForm\ContactFormRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use tests\TestCase;

class ContactFormRepositoryTest extends TestCase
{

    use RefreshDatabase;

    private ContactFormRepository $repository;

    /**
     * 各テストの実行前に起動する。
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app(ContactFormRepository::class);
    }

    /**
     * インスタンスがテスト対象のクラスであることのテスト
     */
    public function testInstanceOf(): void
    {
        $this->assertInstanceOf(ContactFormRepository::class, $this->repository);
    }

    /**
     * getByConditionsのテスト
     */
    public function testGetByConditions(): void
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

        /** @var ContactForm $expectContactForm1 */
        $expectContactForm1 = ContactForm::factory(['user_name' => 'user1', 'title' => 'title1'])->create();
        /** @var ContactForm $expectContactForm2 */
        $expectContactForm2 = ContactForm::factory(['user_name' => 'user2', 'title' => 'title2'])->create();

        /** @var ContactForm $contactForm */
        $contactForm = $this->repository->getByConditions([
            ...$defaultConditions,
            'user_name' => 'user1'
        ])->first();
        $this->assertSame($expectContactForm1->id, $contactForm->id, 'user_nameで検索が出来ることをテスト');

        /** @var ContactForm $contactForm */
        $contactForm = $this->repository->getByConditions([
            ...$defaultConditions,
            'title' => 'title2'
        ])->first();
        $this->assertSame($expectContactForm2->id, $contactForm->id, 'titleで検索が出来ることをテスト');

        $contactForms = $this->repository->getByConditions([
            ...$defaultConditions,
            'limit' => 1
        ]);
        $this->assertSame(1, $contactForms->count(), 'limitで取得件数が指定出来ることをテスト');
    }
}
