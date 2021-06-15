<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactForm;

use ContactFormService;

class ContactFormController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {

    $search = $request->input('search');

    $contacts = ContactFormService::searchContactForm($search);

    return view('admin.contact.index', compact('contacts', 'search'));
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
    $contact = ContactForm::find($id);

    // $contactFormImages = $contact->contactFormImages;
    // dd($contactFormImages);

    return view('admin.contact.show', compact('contact'));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //
    $contact = ContactForm::find($id);

    return view('admin.contact.edit', compact('contact'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {

    ContactFormService::updateContactForm($request, $id);

    return redirect('/admin/contact');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    ContactFormService::deleteContactForm($id);

    return redirect('/admin/contact');
  }
}
