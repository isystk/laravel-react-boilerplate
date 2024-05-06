<?php

namespace Domain\Repositories\ContactForm;

use App\Domain\Entities\ContactForm;
use App\Domain\Entities\ContactFormImage;
use App\Domain\Repositories\ContactForm\ContactFormImageRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactFormImageRepositoryTest extends TestCase
{
    /**
     * 各テストの実行後にテーブルを空にする。
     */
    use RefreshDatabase;

    private ContactFormImageRepository $repository;

    /**
     * 各テストの実行前に起動する。
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app(ContactFormImageRepository::class);
    }

    /**
     * インスタンスがテスト対象のクラスであることのテスト
     */
    public function testInstanceOf(): void
    {
        $this->assertInstanceOf(ContactFormImageRepository::class, $this->repository);
    }

    /**
     * getByConditionsのテスト
     */
    public function testGetByConditions(): void
    {
        /** @var ContactForm $contactForm1 */
        $contactForm1 = ContactForm::factory(['user_name' => 'user1', 'title' => 'title1'])->create();
        /** @var ContactForm $contactForm2 */
        $contactForm2 = ContactForm::factory(['user_name' => 'user2', 'title' => 'title1'])->create();

        $contactFormImages = $this->repository->getByContactFormId($contactForm1->id);
        $this->assertSame(0, $contactFormImages->count(), 'データがない状態で正常に動作することを始めにテスト');

        /** @var ContactFormImage $expectContactForm1Image1 */
        $expectContactForm1Image1 = ContactFormImage::factory(['contact_form_id' => $contactForm1->id])->create();
        /** @var ContactFormImage $expectContactForm1Image2 */
        $expectContactForm1Image2 = ContactFormImage::factory(['contact_form_id' => $contactForm1->id])->create();
        /** @var ContactFormImage $expectContactForm2Image1 */
        $expectContactForm2Image1 = ContactFormImage::factory(['contact_form_id' => $contactForm2->id])->create();
        $expectContactFormImageIds = [$expectContactForm1Image1->id, $expectContactForm1Image2->id];

        /** @var ContactFormImage $contactFormImages */
        $contactFormImages = $this->repository->getByContactFormId($contactForm1->id);
        $contactFormImageIds = $contactFormImages->pluck('id')->all();

        $this->assertSame($expectContactFormImageIds, $contactFormImageIds, 'contact_form_idで検索が出来ることをテスト');
    }
}
