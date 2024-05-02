<?php

namespace App\Services\Admin\ContactForm;

use App\Domain\Repositories\ContactForm\ContactFormImageRepository;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class ShowService extends BaseService
{
    /**
     * @var ContactFormImageRepository
     */
    protected ContactFormImageRepository $contactFormImageRepository;

    public function __construct(
        Request $request,
        ContactFormImageRepository $contactFormImageRepository
    )
    {
        parent::__construct($request);
        $this->contactFormImageRepository = $contactFormImageRepository;
    }

    /**
     * @param int $contactFormId
     * @return Collection
     */
    public function getContactFormImage(int $contactFormId): Collection
    {
        return $this->contactFormImageRepository->getByContactFormId($contactFormId);
    }
}
