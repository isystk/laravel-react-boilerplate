<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\ContactForm\StoreRequest;
use App\Services\Api\ContactForm\StoreService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ContactFormController extends BaseApiController
{

    /**
     * お問い合わせ内容を登録します。
     *
     * @param StoreRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function store(StoreRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            /** @var StoreService $service */
            $service = app(StoreService::class);
            $service->save($request);
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
