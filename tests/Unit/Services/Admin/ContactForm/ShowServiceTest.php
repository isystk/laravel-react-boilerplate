<?php

namespace Tests\Unit\Services\Admin\ContactForm;

use App\Services\Admin\ContactForm\ShowService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowServiceTest extends TestCase
{
    use RefreshDatabase;

    private ShowService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(ShowService::class);
    }

    /**
     * getContactFormImageのテスト
     */
    public function test_get_contact_form_image(): void
    {
        $contactForm1 = $this->createDefaultContactForm(['user_name' => 'user1', 'title' => 'title1']);
        $contactForm2 = $this->createDefaultContactForm(['user_name' => 'user2', 'title' => 'title1']);

        $contactFormImages = $this->service->getContactFormImage($contactForm1->id);
        $this->assertSame(0, $contactFormImages->count(), 'データがない状態で正常に動作することを始めにテスト');

        $expectContactForm1Image1 = $this->createDefaultContactFormImage(['contact_form_id' => $contactForm1->id]);
        $expectContactForm1Image2 = $this->createDefaultContactFormImage(['contact_form_id' => $contactForm1->id]);
        $this->createDefaultContactFormImage(['contact_form_id' => $contactForm2->id]);
        $expectContactFormImageIds = [$expectContactForm1Image1->id, $expectContactForm1Image2->id];

        $contactFormImages = $this->service->getContactFormImage($contactForm1->id);
        $contactFormImageIds = $contactFormImages->pluck('id')->all();

        $this->assertSame($expectContactFormImageIds, $contactFormImageIds,
            'contact_form_idで検索が出来ることをテスト');
    }
}
