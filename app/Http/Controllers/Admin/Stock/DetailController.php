<?php

namespace App\Http\Controllers\Admin\Stock;

use App\Domain\Entities\Stock;
use App\Http\Controllers\BaseController;
use App\Services\Admin\Stock\DestroyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DetailController extends BaseController
{

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
        // 上位管理者のみがアクセス可能
        $this->authorize('high-manager');
        DB::beginTransaction();
        try {
            /** @var DestroyService $service */
            $service = app(DestroyService::class);
            $service->delete($stock->id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return redirect(route('admin.stock'));
    }
}
