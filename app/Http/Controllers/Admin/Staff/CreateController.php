<?php

namespace App\Http\Controllers\Admin\Staff;

use App\Dto\Request\Admin\Staff\CreateDto;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\Staff\StoreRequest;
use App\Services\Admin\Staff\CreateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class CreateController extends BaseController
{
    /**
     * スタッフ登録画面の初期表示
     */
    public function create(): View
    {
        return view('admin.staff.create');
    }

    /**
     * スタッフ登録画面の登録処理
     *
     * @throws Throwable
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        /** @var CreateService $service */
        $service = app(CreateService::class);

        DB::beginTransaction();

        $dto = new CreateDto($request);

        try {
            $staff = $service->save($dto);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }

        return redirect(route('admin.staff.show', $staff));
    }
}
