<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Entities\ContactForm;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactFormRequest;
use App\Services\ContactFormService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
     * @param Request $request
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
        DB::beginTransaction();
        try {
            $this->contactFormService->save($contact->id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
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
        DB::beginTransaction();
        try {
            $this->contactFormService->delete($contact->id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return redirect('/admin/contact');
    }
}
