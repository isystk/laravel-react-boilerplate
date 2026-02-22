<?php

namespace App\Http\Controllers\Api;

use App\Domain\Entities\User;
use App\Services\Api\Profile\DestroyService;
use App\Services\Api\Profile\UpdateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class ProfileController extends BaseApiController
{
    /**
     * プロフィール情報を更新します。
     */
    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'name'   => ['required', 'string', 'max:255'],
            'avatar' => ['nullable', 'string'],
        ]);

        /** @var User $user */
        $user   = $request->user();
        $name   = $request->get('name');
        $avatar = $request->get('avatar');

        $service = app(UpdateService::class);

        DB::beginTransaction();

        try {
            $dto = $service->update($user, $name, $avatar);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            return $this->getErrorJsonResponse($e);
        }

        return response()->json($dto);
    }

    /**
     * アカウントを削除します。
     */
    public function destroy(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $service = app(DestroyService::class);

        DB::beginTransaction();

        try {
            $service->destroy($user);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            return $this->getErrorJsonResponse($e);
        }

        return response()->json(['result' => true]);
    }
}
