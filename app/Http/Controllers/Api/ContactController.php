<?php

namespace App\Http\Controllers\Api;

use App\Domain\Entities\User;
use App\Dto\Request\Api\Contact\CreateDto;
use App\Http\Requests\Api\Contact\StoreRequest;
use App\Services\Api\Contact\StoreService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class ContactController extends BaseApiController
{
    /**
     * お問い合わせ内容を登録します。
     *
     * @throws Throwable
     */
    public function store(StoreRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        /** @var StoreService $service */
        $service = app(StoreService::class);
        DB::beginTransaction();

        $dto = new CreateDto($request);

        try {
            $service->save($user, $dto);
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
