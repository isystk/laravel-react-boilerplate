<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Stock;

class StockService
{
  public function __construct()
  {
  }

  public function searchStock($name, $hasPaging)
  {

    // 検索フォーム
    $query = DB::table('stocks');
    // もしキーワードがあったら
    if ($name !== null) {
      $query->where('name', 'like', '%' . $name . '%');
    }

    $query->select('id', 'name', 'detail', 'price', 'quantity', 'created_at');
    $query->orderBy('id');
    $query->orderBy('created_at', 'desc');
    if ($hasPaging) {
      $stocks = $query->paginate(20);
    } else {
      $stocks = $query->get();
    }

    // dd($stocks);

    return $stocks;
  }

  public function createStock($request)
  {

    // 画像ファイルを公開ディレクトリへ配置する。
    if ($request->has('imageBase64')) {
      $tmpFile = $request->validated()['imageFile'];

      $fileName = $request->fileName;

      //s3に画像をアップロード
      $tmpFile->storeAs('stock', $fileName);

      // //ストレージにも画像を保存
      // $target_path = storage_path('uploads/stock/');
      // $tmpFile->move($target_path, $fileName);
    } else {
      $fileName = "";
    }

    DB::beginTransaction();
    try {    //
      $stock = new Stock;
      $stock->name = $request->input('name');
      $stock->detail = $request->input('detail');
      $stock->price = $request->input('price');
      $stock->quantity = $request->input('quantity');

      if ($fileName !== "") {
        $stock->imgpath = $fileName;
      }

      $stock->save();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollback();
    }
  }

  public function updateStock($request, $id)
  {

    // imageBase64パラメータがあればUploadedFileオブジェクトに変換してimageFileパラメータに上書きする。
    if ($request->has('imageBase64')) {
      $request['imageFile'] = UploadImage::converBase64($request->imageBase64);
    }

    // 入力チェック
    $validatedData = $request->validate([
      'name' => 'required|string|max:20',
      'price' => 'required|numeric',
      'detail' => 'required|string|max:200',
      'quantity' => 'required|numeric',
      'imageFile' => 'nullable|image|mimes:jpeg,png|max:100000000|dimensions:max_width=1200,max_height=1200', // ファイルのバリデーションよしなに。
      'imageBase64' => 'nullable|string', // 画像データをbase64で文字列としても受け入れる。バリデーションルールはimageFileが適用される。
      'fileName' => 'nullable|string', // 画像ファイル名
    ]);

    // 画像ファイルを公開ディレクトリへ配置する。
    if ($file = $request->imageFile) {
      $fileName = $request->fileName;

      //s3に画像をアップロード
      $file->storeAs('stock', $fileName);

      // // //ストレージにも画像を保存
      // $target_path = public_path('uploads/stock/');
      // $file->move($target_path, $fileName);
    } else {
      $fileName = "";
    }

    DB::beginTransaction();
    try {    //
      //
      $stock = Stock::find($id);

      $stock->name = $request->input('name');
      $stock->detail = $request->input('detail');
      $stock->price = $request->input('price');
      $stock->quantity = $request->input('quantity');

      if ($fileName !== "") {
        $stock->imgpath = $fileName;
      }

      $stock->save();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollback();
    }
  }

  public function deleteStock($id)
  {
    DB::beginTransaction();
    try {    //
      // ユーザーテーブルを削除
      $stock = Stock::find($id);
      $stock->delete();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollback();
    }
  }
}
