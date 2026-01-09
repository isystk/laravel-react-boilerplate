<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\PasswordChangeUpdateRequest;
use App\Services\Admin\PasswordChangeUpdateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Throwable;

class PasswordChangeController extends BaseController
{
    /**
     * パスワード変更画面の初期表示
     */
    public function index(): View
    {
        return view('admin.password_change');
    }

    /**
     * スタッフ変更画面の登録処理
     *
     * @throws Throwable
     */
    public function update(PasswordChangeUpdateRequest $request): RedirectResponse
    {
        $newPassword = Hash::make($request->password);

        // トランザクション開始
        DB::beginTransaction();

        try {
            $service = app(PasswordChangeUpdateService::class);
            $service->update($request->user()->id, $newPassword);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }

        // ログアウト処理
        Auth::guard('admin')->logout();
        $request->session()->flush();
        $request->session()->regenerate();

        return redirect(route('admin.login'));
    }
}
