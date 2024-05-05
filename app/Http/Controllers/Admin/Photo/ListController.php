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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * 画像一覧画面の初期表示
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        /** @var IndexService $service */
        $service = app(IndexService::class);
        $photos = $service->searchPhotoList();

        return view('admin.photo.index', compact('photos', 'request'));
    }


    /**
     * 画像一覧画面の削除処理
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        $fileName = $request->fileName;

        /** @var DestroyService $service */
        $service = app(DestroyService::class);
        $service->delete($fileName);

        return redirect(route('admin.photo'));
    }
}
