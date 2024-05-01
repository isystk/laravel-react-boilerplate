<?php

namespace App\Http\Controllers\Admin\Stock;

use App\Domain\Entities\Stock;
use App\Http\Controllers\BaseController;
use App\Services\Admin\Stock\StockService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DetailController extends BaseController
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
     * 商品詳細画面の登録処理
     *
     * @param Stock $stock
     * @return View
     */
    public function show(Stock $stock): View
    {
        return view('admin.stock.show', compact('stock'));
    }

    /**
     * 商品詳細画面の削除処理
     *
     * @param Stock $stock
     * @return RedirectResponse
     * @throws \Exception
     */
    public function destroy(Stock $stock): RedirectResponse
    {
        DB::beginTransaction();
        try {
            /** @var StockService $service */
            $service = app(StockService::class);
            $service->delete($stock->id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return redirect(route('admin.stock'));
    }
}
