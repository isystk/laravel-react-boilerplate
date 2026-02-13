<?php

namespace App\Http\Controllers\Admin\Staff;

use App\Domain\Entities\Admin;
use App\Dto\Request\Admin\Staff\UpdateDto;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\Staff\UpdateRequest;
use App\Services\Admin\Staff\UpdateService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class EditController extends BaseController
{
    /**
     * スタッフ変更画面の初期表示
     */
    public function edit(Admin $staff): View
    {
        $staff->password = Hash::make($staff->password);

        return view('admin.staff.edit', compact([
            'staff',
        ]));
    }

    /**
     * スタッフ変更画面の登録処理
     *
     * @throws Throwable
     */
    public function update(UpdateRequest $request, Admin $staff): RedirectResponse
    {
        /** @var UpdateService $service */
        $service = app(UpdateService::class);

        DB::beginTransaction();

        $dto = new UpdateDto($request);

        try {
            $service->update($staff->id, $dto);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }

        return redirect(route('admin.staff.show', $staff));
    }
}
