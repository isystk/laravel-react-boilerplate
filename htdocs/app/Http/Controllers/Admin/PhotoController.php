<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\CSV;

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

        $files = Storage::files();
        foreach ($files as $file) {
          $photo = (object) [
            'type' => 'default',
            'fileName' => basename($file)
          ];
          array_push($photos, $photo);
        }

        $files = Storage::files('stock');
        foreach ($files as $file) {
          $photo = (object) [
            'type' => 'stock',
            'fileName' => basename($file)
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
        $delPath = storage_path('stock/' . $fileName);
        Storage::delete($delPath);
      } else {
        Storage::delete($fileName);
      }

      return redirect('/admin/photo');
    }
}
