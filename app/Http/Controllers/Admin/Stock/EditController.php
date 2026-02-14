<?php

namespace App\Http\Controllers\Admin\Stock;

use App\Domain\Entities\Stock;
use App\Dto\Request\Admin\Stock\UpdateDto;
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
     * 商品変更画面の初期表示
     */
    public function edit(Stock $stock): View
    {
        return view('admin.stock.edit', compact([
            'stock',
        ]));
    }

    /**
     * 商品変更画面の登録処理
     *
     * @throws \Throwable
     */
    public function update(UpdateRequest $request, Stock $stock): RedirectResponse
    {
        /** @var UpdateService $service */
        $service = app(UpdateService::class);

        DB::beginTransaction();

        $dto = new UpdateDto($request);

        try {
            $service->update($stock, $dto);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }

        return redirect(route('admin.stock.show', $stock));
    }
}
