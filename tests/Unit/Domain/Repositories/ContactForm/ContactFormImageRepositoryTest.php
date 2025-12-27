<?php

namespace Domain\Repositories\ContactForm;

use App\Domain\Repositories\ContactForm\ContactFormImageRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class ContactFormImageRepositoryTest extends BaseTest
{
    use RefreshDatabase;

    private ContactFormImageRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app(ContactFormImageRepository::class);
    }

    public function test_getByContactFormId(): void
    {
        $contactForm1 = $this->createDefaultContactForm(['user_name' => 'user1', 'title' => 'title1']);
        $contactForm2 = $this->createDefaultContactForm(['user_name' => 'user2', 'title' => 'title1']);

        $contactFormImages = $this->repository->getByContactFormId($contactForm1->id);
        $this->assertSame(0, $contactFormImages->count(), 'データがない状態で正常に動作することを始めにテスト');

        $expectContactForm1Image1 = $this->createDefaultContactFormImage(['contact_form_id' => $contactForm1->id]);
        $expectContactForm1Image2 = $this->createDefaultContactFormImage(['contact_form_id' => $contactForm1->id]);
        $this->createDefaultContactFormImage(['contact_form_id' => $contactForm2->id]);
        $expectContactFormImageIds = [$expectContactForm1Image1->id, $expectContactForm1Image2->id];

        $contactFormImages = $this->repository->getByContactFormId($contactForm1->id);
        $contactFormImageIds = $contactFormImages->pluck('id')->all();

        $this->assertSame($expectContactFormImageIds, $contactFormImageIds,
            'contact_form_idで検索が出来ることをテスト');
    }
}
