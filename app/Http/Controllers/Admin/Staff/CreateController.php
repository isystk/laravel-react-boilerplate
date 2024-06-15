<?php

namespace App\Http\Controllers\Admin\Staff;

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
     * 商品登録画面の初期表示
     *
     * @return View
     */
    public function create(): View
    {
        // 上位管理者のみがアクセス可能
        $this->authorize('high-manager');
        return view('admin.staff.create');
    }

    /**
     * 商品登録画面の登録処理
     *
     * @param StoreRequest $request
     * @return RedirectResponse
     * @throws Throwable
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            /** @var CreateService $service */
            $service = app(CreateService::class);
            $service->save($request);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        return redirect(route('admin.staff'));
    }

}
