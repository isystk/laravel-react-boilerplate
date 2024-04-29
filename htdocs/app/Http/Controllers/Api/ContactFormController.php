<?php

namespace App\Http\Controllers\Api;

use App\Enums\ErrorType;
use App\Http\Controllers\ApiController;
use App\Http\Requests\StoreContactFormRequest;
use App\Services\ContactFormService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ContactFormController extends ApiController
{
    /**
     * @var ContactFormService
     */
    protected ContactFormService $contactFormService;

    public function __construct(ContactFormService $contactFormService)
    {
        $this->contactFormService = $contactFormService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreContactFormRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function store(StoreContactFormRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->contactFormService->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return $this->resConversionJson([
            'result' => true,
        ]);
    }
}
