<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        $photos = [];

        $path= storage_path('public/uploads');
        $files = \File::files($path);
        foreach ($files as $v) {
          $photo = (object) [
            'type' => 'default',
            'fileName' => $v->getfileName()
          ];
          array_push($photos, $photo);
        }

        $path= storage_path('public/uploads/stock');
        $files = \File::files($path);
        foreach ($files as $v) {
          $photo = (object) [
            'type' => 'stock',
            'fileName' => $v->getfileName()
          ];
          array_push($photos, $photo);
        }
        // dd($photos);

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

      if ($type === 'stock') {
        $delPath = storage_path('public/uploads/stock/' . $fileName);
        Storage::delete($delPath);
      } else {
        $delPath = storage_path('public/uploads/' . $fileName);
        Storage::delete($delPath);
      }

      return redirect('/admin/photo');
    }
}
