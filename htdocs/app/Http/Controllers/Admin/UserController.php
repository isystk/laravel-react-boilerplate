<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Entities\User;
use App\Enums\ErrorType;
use App\Http\Controllers\Controller;
use App\Services\UserService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * @var UserService
     */
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request): View|Factory|Application
    {
        $users = $this->userService->list();

        return view('admin.user.index', compact('users', 'request'));
    }


    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return View
     */
    public function show(User $user): View
    {
        return view('admin.user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return View
     */
    public function edit(User $user): View
    {
        return view('admin.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
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
            $this->userService->save($user->id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return redirect('admin/user');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(User $user): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $this->userService->delete($user->id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return redirect('/admin/user');
    }
}
