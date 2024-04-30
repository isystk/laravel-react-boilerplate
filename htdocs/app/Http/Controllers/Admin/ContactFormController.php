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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * お問い合わせ一覧の初期表示
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $service = app(ContactFormService::class);
        $contacts = $service->list();

        return view('admin.contact.index', compact('contacts', 'request'));
    }

    /**
     * お問い合わせ詳細画面の初期表示
     *
     * @param ContactForm $contact
     * @return View
     */
    public function show(ContactForm $contact): View
    {
         return view('admin.contact.show', compact('contact'));
    }

    /**
     * お問い合わせ変更画面の初期表示
     *
     * @param ContactForm $contact
     * @return View
     */
    public function edit(ContactForm $contact): View
    {
        return view('admin.contact.edit', compact('contact'));
    }

    /**
     * お問い合わせ変更画面の登録処理
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
