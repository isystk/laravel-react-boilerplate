<?php

namespace App\Http\Controllers\Admin\ContactForm;

use App\Domain\Entities\ContactForm;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\ContactForm\UpdateRequest;
use App\Services\Admin\ContactForm\EditService;
use App\Services\Admin\ContactForm\UpdateService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class EditController extends BaseController
{

    /**
     * お問い合わせ変更画面の初期表示
     *
     * @param Request $request
     * @param ContactForm $contactForm
     * @return Response|View
     */
    public function edit(Request $request, ContactForm $contactForm)
    {
        $user = $request->user();
        if ('high-manager' !== $user->role) {
            // high-managerロール以外の場合
            return response('権限がありません', ResponseAlias::HTTP_FORBIDDEN);
        }

        /** @var EditService $service */
        $service = app(EditService::class);
        $contactFormImages = $service->getContactFormImage($contactForm->id);

        return view('admin.contact.edit', compact('contactForm', 'contactFormImages'));
    }

    /**
     * お問い合わせ変更画面の登録処理
     *
     * @param UpdateRequest $request
     * @param ContactForm $contactForm
     * @return RedirectResponse
     * @throws Exception
     */
    public function update(UpdateRequest $request, ContactForm $contactForm): RedirectResponse
    {
        DB::beginTransaction();
        try {
            /** @var UpdateService $service */
            $service = app(UpdateService::class);
            $service->update($contactForm->id, $request);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return redirect(route('admin.contact'));
    }
}
