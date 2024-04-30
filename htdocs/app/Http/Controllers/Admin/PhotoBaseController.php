<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\PhotoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PhotoBaseController extends BaseController
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
        $service = app(PhotoService::class);
        $photos = $service->list();

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
        $type = $request->type;
        $fileName = $request->fileName;

        $service = app(PhotoService::class);
        $service->delete($type, $fileName);

        return redirect(route('admin.photo'));
    }
}
