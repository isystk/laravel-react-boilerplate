<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\PhotoService;

class PhotoController extends Controller
{
  /**
   * @var PhotoService
   */
  protected $photoService;

  public function __construct(PhotoService $photoService)
  {
      $this->photoService = $photoService;
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {

    $name = $request->input('name');

    $photos = $this->photoService->list();

    return view('admin.photo.index', compact('photos', 'name'));
  }


  /**
   * Remove the specified resource from storage.
   *
   * @return \Illuminate\Http\Response
   */
  public function destroy(Request $request)
  {

    $type = $request->input('type');
    $fileName = $request->input('fileName');

    $this->photoService->delete($type, $fileName);

    return redirect('/admin/photo');
  }
}
