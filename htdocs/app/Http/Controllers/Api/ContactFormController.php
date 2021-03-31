<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Models\ContactForm;
use App\Models\ContactFormImage;
use App\Http\Requests\StoreContactForm;


class ContactFormController extends ApiController
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreContactForm $request)
    {
      try {
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

        $result = [
            'result'      => true,
        ];
      } catch (\Exception $e) {
          $result = [
              'result' => false,
              'error' => [
                  'messages' => [$e->getMessage()]
              ],
          ];
          return $this->resConversionJson($result, $e->getCode());
      }
      return $this->resConversionJson($result);
    }

}
