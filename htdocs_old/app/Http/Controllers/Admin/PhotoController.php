<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use App\Services\PhotoService;
use Illuminate\View\View;

class PhotoController extends Controller
{
    /**
     * @var PhotoService
     */
    protected PhotoService $photoService;

    public function __construct(PhotoService $photoService)
    {
        $this->photoService = $photoService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {

        $name = $request->input('name');

        $photos = $this->photoService->list();

        return view('admin.photo.index', compact('photos', 'name'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {

        $type = $request->input('type');
        $fileName = $request->input('fileName');

        $this->photoService->delete($type, $fileName);

        return redirect('/admin/photo');
    }
}
