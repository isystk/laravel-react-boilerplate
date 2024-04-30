<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Entities\User;
use App\Enums\ErrorType;
use App\Http\Controllers\BaseController;
use App\Services\UserService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserBaseController extends BaseController
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
     * 顧客一覧画面の初期表示
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request): View|Factory|Application
    {
        $service = app(UserService::class);
        $users = $service->list();

        return view('admin.user.index', compact('users', 'request'));
    }


    /**
     * 顧客詳細画面の初期表示
     *
     * @param User $user
     * @return View
     */
    public function show(User $user): View
    {
        return view('admin.user.show', compact('user'));
    }

    /**
     * 顧客変更画面の初期表示
     *
     * @param User $user
     * @return View
     */
    public function edit(User $user): View
    {
        return view('admin.user.edit', compact('user'));
    }

    /**
     * 顧客変更画面の登録処理
     *
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     * @throws Exception
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $service = app(UserService::class);
            $service->save($user->id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return redirect(route('admin.user'));
    }

    /**
     * 顧客詳細画面の削除処理
     *
     * @param User $user
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(User $user): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $service = app(UserService::class);
            $service->delete($user->id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return redirect(route('admin.user'));
    }
}
