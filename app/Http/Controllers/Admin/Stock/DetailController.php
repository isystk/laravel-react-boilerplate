<?php

namespace App\Http\Controllers\Admin\Stock;

use App\Domain\Entities\Stock;
use App\Http\Controllers\BaseController;
use App\Services\Admin\Stock\DestroyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class DetailController extends BaseController
{
    /**
     * 商品詳細画面の登録処理
     */
    public function show(Stock $stock): View
    {
        return view('admin.stock.show', compact([
            'stock',
        ]));
    }

    /**
     * 商品詳細画面の削除処理
     *
     * @throws Throwable
     */
    public function destroy(Stock $stock): RedirectResponse
    {
        /** @var DestroyService $service */
        $service = app(DestroyService::class);

        DB::beginTransaction();
        try {
            $service->delete($stock->id);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return redirect(route('admin.stock'));
    }
}
