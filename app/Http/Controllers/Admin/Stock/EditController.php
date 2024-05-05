<?php

namespace App\Http\Controllers\Admin\Stock;

use App\Domain\Entities\Stock;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\Stock\UpdateRequest;
use App\Services\Admin\Stock\UpdateService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class EditController extends BaseController
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
     * 商品変更画面の初期表示
     *
     * @param Stock $stock
     * @return View
     */
    public function edit(Stock $stock): View
    {
        return view('admin.stock.edit', compact('stock'));
    }

    /**
     * 商品変更画面の登録処理
     *
     * @param UpdateRequest $request
     * @param Stock $stock
     * @return RedirectResponse
     * @throws Exception
     */
    public function update(UpdateRequest $request, Stock $stock): RedirectResponse
    {
        DB::beginTransaction();
        try {
            /** @var UpdateService $service */
            $service = app(UpdateService::class);
            $service->update($stock->id);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return redirect(route('admin.stock'));
    }
}
