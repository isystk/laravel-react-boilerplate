<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactForm;
use App\Models\ContactFormImage;
use Auth;
use App\Http\Requests\StoreContactForm;

class ContactFormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        return view('contact.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreContactForm $request)
    {
        // 画像ファイルを公開ディレクトリへ配置する。
        if ($request->has('imageBase64')) {
            $file = $request->validated()['imageFile'];
            $fileName = time() . $request->fileName;
            $target_path = public_path('uploads/');
            $file->move($target_path, $fileName);
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
        $contact_form_images = new ContactFormImage;
        $contact_form_images->file_name = $fileName;
        $contact_form_images->contact_form_id = $id;
        $contact_form_images->save();

        return redirect('/contact/complete');
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function complete()
    {
        //
        return view('contact.complete');
    }
}
