<?php

namespace App\Http\Controllers\Admin\Photo;

use App\Dto\Request\Admin\Photo\SearchConditionDto;
use App\Http\Controllers\BaseController;
use App\Services\Admin\Photo\DestroyService;
use App\Services\Admin\Photo\IndexService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListController extends BaseController
{
    /**
     * 画像一覧画面の初期表示
     */
    public function index(Request $request): View
    {
        /** @var IndexService $service */
        $service = app(IndexService::class);

        $conditions  = new SearchConditionDto($request);
        $displayDtos = $service->searchPhotoList($conditions);

        return view('admin.photo.index', compact([
            'displayDtos',
        ]));
    }

    /**
     * 画像一覧画面の削除処理
     */
    public function destroy(Request $request): RedirectResponse
    {
        $imageId = (int) $request->imageId;

        /** @var DestroyService $service */
        $service = app(DestroyService::class);
        $service->delete($imageId);

        return redirect(route('admin.photo'));
    }
}
