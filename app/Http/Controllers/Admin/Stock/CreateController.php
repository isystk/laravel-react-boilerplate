<?php

namespace App\Http\Controllers\Admin\Stock;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\Stock\StoreRequest;
use App\Services\Admin\Stock\CreateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class CreateController extends BaseController
{
    /**
     * 商品登録画面の初期表示
     */
    public function create(): View
    {
        return view('admin.stock.create');
    }

    /**
     * 商品登録画面の登録処理
     *
     * @throws Throwable
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        /** @var CreateService $service */
        $service = app(CreateService::class);

        DB::beginTransaction();
        try {
            $stock = $service->save($request);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return redirect(route('admin.stock.show', $stock));
    }
}
