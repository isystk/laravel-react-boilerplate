<?php

namespace App\Services;

use App\Models\ContactFormImage;
use Illuminate\Support\Facades\DB;
use App\Services\UploadImage;
use App\Models\ContactForm;

class ContactFormService
{
  public function __construct()
  {
  }

  public function searchContactForm($search)
  {

    // dd($search);

    // エロくワント ORマッパー
    // $contacts = ContactForm::all();

    // クエリビルダ
    // $contacts = DB::table('contact_forms')
    //     ->select('id', 'your_name', 'title', 'created_at')
    //     ->orderBy('created_at', 'desc')
    //     ->orderBy('id')
    //     ->get();

    // 検索フォーム
    $query = DB::table('contact_forms');

    // もしキーワードがあったら
    if ($search !== null) {
      // 全角スペースを半角に
      $search_split = mb_convert_kana($search, 's');

      // 空白で区切る
      $search_split2 = preg_split('/[\s]+/', $search_split);

      // 単語をループで回す
      foreach ($search_split2 as $value) {
        $query->where('your_name', 'like', '%' . $value . '%');
      }
    }

    $query->select('id', 'your_name', 'title', 'created_at');
    $query->orderBy('created_at', 'desc');
    $query->orderBy('id', 'desc');
    $contacts = $query->paginate(20);

    // dd($contacts);
    return $contacts;
  }

  public function createContactForm($request)
  {

    // 画像ファイルを公開ディレクトリへ配置する。
    if ($request->has('imageBase64') && $request->imageBase64 !== null) {
      $file = $request->validated()['imageFile'];
      $fileName = time() . $request->fileName;

      //s3に画像をアップロード
      $file->storeAs('', $fileName);

      // $target_path = public_path('uploads/');
      // $file->move($target_path, $fileName);
    } else {
      $fileName = "";
    }

    DB::beginTransaction();
    try {    //

      // お問い合わせテーブルを登録
      $contact = new ContactForm;
      $contact->your_name = $request->input('your_name');
      $contact->title = $request->input('title');
      $contact->email = $request->input('email');
      $contact->url = $request->input('url');
      $contact->gender = $request->input('gender');
      $contact->age = $request->input('age');
      $contact->contact = $request->input('contact');
      $contact->save();

      $id = $contact->id;

      // お問い合わせ画像テーブルを登録
      if ($fileName !== "") {
        $contact_form_images = new ContactFormImage;
        $contact_form_images->file_name = $fileName;
        $contact_form_images->contact_form_id = $id;
        $contact_form_images->save();
      }

      DB::commit();
    } catch (\Exception $e) {
      DB::rollback();
    }
  }

  public function updateContactForm($request, $id)
  {

    // imageBase64パラメータがあればUploadedFileオブジェクトに変換してimageFileパラメータに上書きする。
    if ($request->has('imageBase64') && $request->imageBase64 !== null) {
      $request['imageFile'] = UploadImage::converBase64($request->imageBase64);
    }

    // 入力チェック
    $validatedData = $request->validate([
      'your_name' => 'required|string|max:20',
      'title' => 'required|string|max:50',
      'email' => 'required|email|max:255',
      'gender' => 'required',
      'age' => 'required',
      'contact' => 'required|string|max:200',
      'url' => 'url|nullable',
      'imageFile' => 'nullable|image|mimes:jpeg,png|max:100000000|dimensions:max_width=1200,max_height=1200', // ファイルのバリデーションよしなに。
      'imageBase64' => 'nullable|string', // 画像データをbase64で文字列としても受け入れる。バリデーションルールはimageFileが適用される。
      'fileName' => 'nullable|string', // 画像ファイル名
    ]);

    // 画像ファイルを公開ディレクトリへ配置する。
    if ($file = $request->imageFile) {
      $fileName = time() . $request->fileName;

      //s3に画像をアップロード
      $file->storeAs('', $fileName);

      // $target_path = public_path('uploads/');
      // $file->move($target_path, $fileName);
    } else {
      $fileName = "";
    }

    DB::beginTransaction();
    try {    //
      $contact = ContactForm::find($id);

      $contact->your_name = $request->input('your_name');
      $contact->title = $request->input('title');
      $contact->email = $request->input('email');
      $contact->url = $request->input('url');
      $contact->gender = $request->input('gender');
      $contact->age = $request->input('age');
      $contact->contact = $request->input('contact');

      // dd($your_name, $title);

      $contact->save();

      // お問い合わせ画像テーブルを登録（Delete→Insert）
      $contact_form_images = DB::table('contact_form_images')
        ->where('contact_form_id', $id);
      $contact_form_images->delete();
      if ($fileName !== "") {
        $contact_form_images = new ContactFormImage;
        $contact_form_images->file_name = $fileName;
        $contact_form_images->contact_form_id = $id;
        $contact_form_images->save();
      }

      DB::commit();
    } catch (\Exception $e) {
      DB::rollback();
    }
  }

  public function deleteContactForm($id)
  {

    DB::beginTransaction();
    try {    // お問い合わせ画像テーブルを削除
      $contact_form_images = DB::table('contact_form_images')
        ->where('contact_form_id', $id);
      $contact_form_images->delete();

      // お問い合わせテーブルを削除
      $contact = ContactForm::find($id);
      $contact->delete();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollback();
    }
  }
}
