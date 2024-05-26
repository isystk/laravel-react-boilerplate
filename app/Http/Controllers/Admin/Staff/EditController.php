<?php

namespace App\Http\Controllers\Admin\Staff;

use App\Domain\Entities\Admin;
use App\Domain\Entities\User;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\Staff\UpdateRequest;
use App\Services\Admin\Staff\UpdateService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EditController extends BaseController
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
     * 顧客変更画面の初期表示
     *
     * @param Admin $staff
     * @return View
     */
    public function edit(Admin $staff): View
    {
        $staff->password = Hash::make($staff->password);
        return view('admin.staff.edit', compact('staff'));
    }

    /**
     * 顧客変更画面の登録処理
     *
     * @param UpdateRequest $request
     * @param Admin $staff
     * @return RedirectResponse
     * @throws Exception
     */
    public function update(UpdateRequest $request, Admin $staff): RedirectResponse
    {
        DB::beginTransaction();
        try {
            /** @var UpdateService $service */
            $service = app(UpdateService::class);
            $service->update($staff->id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return redirect(route('admin.staff'));
    }

}
