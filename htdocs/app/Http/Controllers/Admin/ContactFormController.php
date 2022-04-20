<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ErrorType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreContactFormRequest;
use App\Models\ContactForm;

use App\Services\ContactFormService;

class ContactFormController extends Controller
{
  /**
   * @var ContactFormService
   */
  protected $contactFormService;

  public function __construct(ContactFormService $contactFormService)
  {
      $this->contactFormService = $contactFormService;
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {

    $search = $request->input('search');

    $contacts = $this->contactFormService->list();

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
  public function update(StoreContactFormRequest $request, $id)
  {

    [$contactForm, $type, $exception] = $this->contactFormService->save($id);
    if (!$contactForm) {
        if ($type === ErrorType::NOT_FOUND) {
            abort(400);
        }
        throw $exception ?? new \Exception(__('common.Unknown Error has occurred.'));
    }
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
    [$contactForm, $type, $exception] = $this->contactFormService->delete($id);
    if (!$contactForm) {
        if ($type === ErrorType::NOT_FOUND) {
            abort(400);
        }
        throw $exception ?? new \Exception(__('common.Unknown Error has occurred.'));
    }
    return redirect('/admin/contact');
  }
}
