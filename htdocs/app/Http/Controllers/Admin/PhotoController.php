<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use PhotoService;

class PhotoController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {

    $name = $request->input('name');

    $photos = PhotoService::searchPhoto($name);

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

    PhotoService::deletePhoto($type, $fileName);

    return redirect('/admin/photo');
  }
}
