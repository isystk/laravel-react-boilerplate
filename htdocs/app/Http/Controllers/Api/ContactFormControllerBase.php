<?php

namespace App\Http\Controllers\Api;

use App\Enums\ErrorType;
use App\Http\Requests\StoreContactFormRequest;
use App\Services\ContactFormService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ContactFormControllerBase extends BaseApiController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * お問い合わせ内容を登録します。
     *
     * @param StoreContactFormRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function store(StoreContactFormRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $service = app(ContactFormService::class);
            $service->save();
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
