<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\ContactForm\StoreRequest;
use App\Services\Api\ContactForm\StoreService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class ContactFormController extends BaseApiController
{
    /**
     * お問い合わせ内容を登録します。
     *
     * @throws Throwable
     */
    public function store(StoreRequest $request): JsonResponse
    {
        /** @var StoreService $service */
        $service = app(StoreService::class);
        DB::beginTransaction();
        try {
            $service->save($request);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            return $this->getErrorJsonResponse($e);
        }

        return response()->json([
            'result' => true,
        ]);
    }
}
