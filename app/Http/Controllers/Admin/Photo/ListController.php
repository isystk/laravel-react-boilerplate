<?php

namespace App\Http\Controllers\Admin\Photo;

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

        $conditions = $service->convertConditionsFromRequest($request);
        $photos = $service->searchPhotoList($conditions);

        return view('admin.photo.index', compact('photos', 'request'));
    }

    /**
     * 画像一覧画面の削除処理
     */
    public function destroy(Request $request): RedirectResponse
    {
        // 上位管理者のみがアクセス可能
        $this->authorize('high-manager');

        $fileName = (string) $request->fileName;

        /** @var DestroyService $service */
        $service = app(DestroyService::class);
        $service->delete($fileName);

        return redirect(route('admin.photo'));
    }
}
