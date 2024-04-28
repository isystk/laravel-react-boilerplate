<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ErrorType;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\StoreContactFormRequest;
use App\Models\ContactForm;

use App\Services\ContactFormService;
use Illuminate\View\View;

class ContactFormController extends Controller
{
    /**
     * @var ContactFormService
     */
    protected ContactFormService $contactFormService;

    public function __construct(ContactFormService $contactFormService)
    {
        $this->contactFormService = $contactFormService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(Request $request): View
    {

        $search = $request->input('search');

        $contacts = $this->contactFormService->list();

        return view('admin.contact.index', compact('contacts', 'search'));
    }

    /**
     * Display the specified resource.
     *
     * @param ContactForm $contact
     * @return View
     */
    public function show(ContactForm $contact): View
    {
        // $contactFormImages = $contact->contactFormImages;
        // dd($contactFormImages);

        return view('admin.contact.show', compact('contact'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ContactForm $contact
     * @return View
     */
    public function edit(ContactForm $contact): View
    {
        return view('admin.contact.edit', compact('contact'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreContactFormRequest $request
     * @param ContactForm $contact
     * @return RedirectResponse
     * @throws Exception
     */
    public function update(StoreContactFormRequest $request, ContactForm $contact): RedirectResponse
    {

        [$contactForm, $type, $exception] = $this->contactFormService->save($contact->id);
        if (!$contactForm) {
            if ($type === ErrorType::NOT_FOUND) {
                abort(400);
            }
            throw $exception ?? new Exception(__('common.Unknown Error has occurred.'));
        }
        return redirect('/admin/contact');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ContactForm $contact
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(ContactForm $contact): RedirectResponse
    {
        [$contactForm, $type, $exception] = $this->contactFormService->delete($contact->id);
        if (!$contactForm) {
            if ($type === ErrorType::NOT_FOUND) {
                abort(400);
            }
            throw $exception ?? new Exception(__('common.Unknown Error has occurred.'));
        }
        return redirect('/admin/contact');
    }
}
